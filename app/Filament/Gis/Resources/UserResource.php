<?php

namespace App\Filament\Gis\Resources;

use App\Filament\Gis\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'إعدادات النظام والأمان';
    protected static ?string $navigationLabel = 'إدارة الموظفين';
    protected static ?string $modelLabel = 'موظف / مستخدم';
    protected static ?string $pluralModelLabel = 'سجل الموظفين والصلاحيات';
    protected static ?int $navigationSort = 101;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('البيانات الشخصية والوظيفية')
                    ->description('تأكد من إدخال الرقم القومي الصحيح لربط الموظف بسجل شؤون العاملين.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم الكامل')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني المهني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('national_id')
                            ->label('الرقم القومي (14 رقم)')
                            ->required()
                            ->length(14)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrated(fn($state) => filled($state)) // لا يتم التحديث إذا كان الحقل فارغاً
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->helperText('اترك الحقل فارغاً في حالة عدم الرغبة في تغيير كلمة المرور الحالية.'),

                        // ربط الأدوار الوظيفية (Spatie Roles)
                        Forms\Components\Select::make('roles')
                            ->label('الصفة الوظيفية (الدور)')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn($record) => match ($record->name) {
                                'director' => 'مدير الإدارة الهندسية',
                                'engineer' => 'مهندس التنظيم',
                                'technical' => 'الفني الهندسي',
                                'geospatial' => 'مدير وحدة الجيومكانية الفرعية',
                                default => $record->name
                            })
                            ->native(false),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الموظف')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('national_id')
                    ->label('الرقم القومي')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                // عرض الدور الوظيفي بأسماء عربية ملونة
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('الصفة الوظيفية')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'director' => 'danger',
                        'engineer' => 'warning',
                        'technical' => 'info',
                        'geospatial' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'director' => 'مدير الإدارة',
                        'engineer' => 'مهندس تنظيم',
                        'technical' => 'فني هندسي',
                        'geospatial' => 'مدير جيومكاني',
                        default => $state
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التعيين الرقمي')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلترة الموظفين حسب أدوارهم
                Tables\Filters\SelectFilter::make('roles')
                    ->label('تصفية بالصفة الوظيفية')
                    ->relationship('roles', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لا يوجد موظفين مسجلين')
            ->emptyStateDescription('قم بإضافة الموظفين وتعيين أدوارهم الوظيفية للتحكم في صلاحيات المنظومة.');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
