<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'إدارة الأقسام';
    protected static ?string $modelLabel = 'خدمة';
    protected static ?string $pluralModelLabel = 'دليل الخدمات';
    protected static ?int $navigationSort = 32;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')->tabs([
                // TAB 1: Main Info
                Forms\Components\Tabs\Tab::make('المعلومات الأساسية')->schema([
                    Forms\Components\Select::make('parent_id')
                        ->relationship('parent', 'title')->label('الفئة الرئيسية')
                        ->searchable()->preload(),
                    Forms\Components\TextInput::make('title')->label('اسم الخدمة')->required(),
                    Forms\Components\TextInput::make('icon')->label('أيقونة Font Awesome'),
                    Forms\Components\RichEditor::make('description')->label('الوصف التفصيلي'),
                    Forms\Components\TextInput::make('link')->label('رابط خارجي (إن وجد)')->url(),
                ])->columns(2),

                // TAB 2: Dynamic Form Builder
                Forms\Components\Tabs\Tab::make('باني نماذج الطلبات')
                    ->visible(fn($record) => $record && $record->parent_id !== null)
                    ->schema([
                        Forms\Components\Repeater::make('form_fields')
                            ->label('حقول نموذج طلب الخدمة')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('اسم الحقل (ID برمجي)')
                                        ->required()
                                        ->placeholder('مثال: user_type'),

                                    Forms\Components\TextInput::make('label')
                                        ->label('عنوان الحقل (يظهر للمواطن)')
                                        ->required()
                                        ->placeholder('مثال: صفة مقدم الطلب'),

                                    Forms\Components\Select::make('type')
                                        ->label('نوع الحقل')
                                        ->options([
                                            'text' => 'نصي',
                                            'textarea' => 'نص طويل',
                                            'number' => 'رقم',
                                            'date' => 'تاريخ',
                                            'file' => 'ملف/صورة',
                                            'select' => 'قائمة منسدلة (Select Box)', // إضافة الـ Select
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->live(), // لتحديث الواجهة فور اختيار النوع
                                ]),

                                // --- سيكشن خاص بإعدادات القائمة المنسدلة ---
                                Forms\Components\Section::make('خيارات القائمة')
                                    ->visible(fn(Forms\Get $get) => $get('type') === 'select')
                                    ->schema([
                                        Forms\Components\Repeater::make('options')
                                            ->label('الخيارات المتاحة')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')->label('النص المعروض')->required(),
                                                Forms\Components\TextInput::make('value')->label('القيمة (Value)')->required(),
                                            ])
                                            ->columns(2)
                                            ->grid(2)
                                            ->addActionLabel('أضف خياراً جديداً'),
                                    ]),

                                // --- سيكشن المنطق الشرطي (الأسئلة المعتمدة على اختيارات) ---
                                Forms\Components\Section::make('المنطق الشرطي (التبعية)')
                                    ->description('استخدم هذا الجزء إذا كنت تريد لهذا الحقل أن يظهر فقط بناءً على إجابة سؤال سابق')
                                    ->collapsible()
                                    ->collapsed()
                                    ->schema([
                                        Forms\Components\Toggle::make('is_conditional')
                                            ->label('هذا الحقل مشروط؟')
                                            ->live(),

                                        Forms\Components\Grid::make(2)
                                            ->visible(fn(Forms\Get $get) => $get('is_conditional'))
                                            ->schema([
                                                Forms\Components\TextInput::make('depends_on')
                                                    ->label('يعتمد على الحقل (Name)')
                                                    ->helperText('اكتب "اسم الحقل" الذي تريد مراقبته'),

                                                Forms\Components\TextInput::make('depends_on_value')
                                                    ->label('القيمة المطلوبة للإظهار')
                                                    ->helperText('الحقل سيظهر فقط إذا كانت قيمة الحقل السابق تساوي هذه القيمة'),
                                            ]),
                                    ]),

                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Toggle::make('is_required')->label('هل الحقل إجباري؟'),
                                    Forms\Components\TextInput::make('validation_rules')->label('قواعد التحقق')->placeholder('مثال: max:255'),
                                ]),
                            ])
                            ->itemLabel(fn(array $state): ?string => $state['label'] ?? null) // لعرض اسم الحقل على الكارت من الخارج
                            ->columnSpanFull()
                            ->collapsible()
                            ->collapsed(false)
                            ->addActionLabel('أضف حقل/سؤال جديد'),
                    ]),
                // TAB 3: PRICING
                Forms\Components\Tabs\Tab::make('التسعير')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Forms\Components\TextInput::make('base_price')
                            ->label('سعر الخدمة الأساسي (جنيه مصري)')
                            ->numeric()->required()->default(0)
                            ->prefix('EGP'),

                        Forms\Components\Toggle::make('has_vat')
                            ->label('تطبق ضريبة القيمة المضافة (14%)')
                            ->default(true),

                        Forms\Components\TextInput::make('martyr_stamp_fee')
                            ->label('قيمة طابع الشهداء (افتراضي 5 جنيه)')
                            ->numeric()->required()->default(5.00)
                            ->prefix('EGP'),

                        Forms\Components\TextInput::make('sms_fee')
                            ->label('تكلفة خدمة الرسائل (افتراضي 10 جنيه)')
                            ->numeric()->required()->default(10.00)
                            ->prefix('EGP'),
                    ])->columns(2),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Tables\Grouping\Group::make('parent.title')
                    ->label('الفئة الرئيسية')
                    ->collapsible(),
            ])

            ->defaultGroup('parent.title')

            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الخدمة')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->parent_id) {
                            return new \Illuminate\Support\HtmlString('&nbsp;&nbsp;&nbsp;&nbsp;↳ ' . e($state));
                        }
                        return $state;
                    })
                    ->html(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->getStateUsing(
                        fn($record) =>
                        $record->parent_id ? 'خدمة فرعية' : 'خدمة رئيسية'
                    )
                    ->colors([
                        'primary' => 'خدمة رئيسية',
                        'gray' => 'خدمة فرعية',
                    ]),

                Tables\Columns\IconColumn::make('link')
                    ->label('له رابط؟')
                    ->boolean()
                    ->trueIcon('heroicon-o-link'),

                // Tables\Columns\IconColumn::make('icon')
                //     ->label('أيقونة')
                //     ->icon(fn($state) => $state)
                //     ->color('primary'),

                Tables\Columns\TextColumn::make('children_count')
                    ->counts('children')
                    ->label('الخدمات الفرعية')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('parent_services')
                    ->label('إظهار الخدمات الرئيسية فقط')
                    ->query(fn(Builder $query) => $query->whereNull('parent_id'))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
