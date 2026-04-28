<?php

namespace App\Filament\Estidama\Resources;

use App\Filament\Estidama\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'إدارة التدريب';
    protected static ?string $modelLabel = 'تسجيل';
    protected static ?string $pluralModelLabel = 'التسجيلات والموافقات';
    protected static ?int $navigationSort = 2;

    // Disable creating enrollments from admin panel
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        // A simple form just for changing the status
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('حالة التسجيل')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                        'completed' => 'مكتمل',
                    ])
                    ->required()
                    ->native(false),
                Forms\Components\FileUpload::make('certificate_path')
                    ->label('رفع شهادة إتمام البرنامج')
                    ->directory('certificates')
                    // Show only if status is 'completed'
                    ->visible(fn($get) => $get('status') === 'completed'),

                Forms\Components\Select::make('user.badges')
                    ->label('منح شارات للمستخدم')
                    ->multiple()
                    ->relationship('user.badges', 'name')
                    ->preload()
                    ->visible(fn($get) => $get('status') === 'completed'),
                // You could add a field for notes or rejection reason here
                Forms\Components\Textarea::make('notes')
                    ->label('ملاحظات (سبب الرفض)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المسجل')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.national_id')
                    ->label('الرقم القومي')
                    ->searchable(),

                Tables\Columns\TextColumn::make('trainingProgram.title')
                    ->label('البرنامج التدريبي')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                        'primary' => 'completed',
                    ]),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d M, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                        'completed' => 'مكتمل',
                    ])->label('تصفية حسب الحالة'),

                Tables\Filters\SelectFilter::make('training_program_id')
                    ->relationship('trainingProgram', 'title')
                    ->searchable()
                    ->preload()
                    ->label('تصفية حسب البرنامج'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تغيير الحالة'),
                Tables\Actions\Action::make('approve')
                    ->label('موافقة')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn(Enrollment $record) => $record->update(['status' => 'approved']))
                    ->visible(fn(Enrollment $record): bool => $record->status === 'pending')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('موافقة على المحدد')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn($records) => $records->each->update(['status' => 'approved']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }

    // ===== NAVIGATION BADGE & NOTIFICATION COUNT =====
    public static function getNavigationBadge(): ?string
    {
        // Shows a count of pending enrollments
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // ===== GLOBAL SEARCH CONFIGURATION =====
    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'user.national_id', 'trainingProgram.title'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "تسجيل: " . $record->user->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return ['للبرنامج' => $record->trainingProgram->title];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'edit'  => Pages\EditEnrollment::route('/{record}/edit'), // For updating status
        ];
    }
}
