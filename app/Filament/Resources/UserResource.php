<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'الإدارة';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $pluralModelLabel = 'المستخدمون';
    protected static ?int $navigationSort = -1; // Place at the top of the group

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        // Tab for Core User/Admin Data
                        Forms\Components\Tabs\Tab::make('بيانات الحساب')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('الاسم الكامل')
                                    ->required(),

                                Forms\Components\TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('password')
                                    ->label('كلمة مرور جديدة (اختياري)')
                                    ->password()
                                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn(string $context): bool => $context === 'create'),

                                Forms\Components\Select::make('roles')
                                    ->label('الأدوار')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload()
                                    ->searchable(),
                            ]),

                        // Tab for Citizen Specific Data & Approval
                        Forms\Components\Tabs\Tab::make('بيانات المواطن والمراجعة')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('حالة الحساب')
                                    ->options([
                                        'pending' => 'قيد المراجعة',
                                        'approved' => 'مقبول',
                                        'rejected' => 'مرفوض'
                                    ])
                                    ->required()
                                    ->default('approved')
                                    ->native(false),

                                Forms\Components\Section::make('البيانات المسجلة (للقراءة فقط)')
                                    ->description('هذه هي البيانات التي قام المواطن بإدخالها أثناء التسجيل.')
                                    ->schema([
                                        Forms\Components\TextInput::make('national_id')
                                            ->label('الرقم القومي')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\TextInput::make('phone')
                                            ->label('رقم الهاتف')
                                            ->disabled()
                                            ->dehydrated(false),

                                        Forms\Components\TextInput::make('address')
                                            ->label('العنوان')
                                            ->disabled()
                                            ->dehydrated(false),

                                        // عرض الصورة بشكل أفضل
                                        Forms\Components\Group::make([
                                            Forms\Components\Placeholder::make('national_id_image_label')
                                                ->label('صورة البطاقة المرفقة'),

                                            // Forms\Components\Actions::make([
                                            //     Forms\Components\Actions\Action::make('view_image')
                                            //         ->label('عرض الصورة')
                                            //         ->icon('heroicon-o-eye')
                                            //         ->url(fn(User $record) => $record->national_id_image
                                            //             ? (filter_var($record->national_id_image, FILTER_VALIDATE_URL)
                                            //                 ? $record->national_id_image
                                            //                 : asset('storage/' . $record->national_id_image))
                                            //             : '#')
                                            //         ->openUrlInNewTab()
                                            //         ->visible(fn(User $record) => filled($record->national_id_image))
                                            //         ->color('primary'),
                                            // ]),
                                        ])->columnSpanFull(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('الأدوار')->badge()->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ التسجيل')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'قيد المراجعة', 'approved' => 'مقبول', 'rejected' => 'مرفوض']),
                Tables\Filters\SelectFilter::make('roles')->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(User $record): bool => auth()->user()->id !== $record->id), // Prevent deleting yourself
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
