<?php

namespace App\Filament\Estidama\Resources;

use App\Filament\Estidama\Resources\TrainingApplicationResource\Pages;
use App\Models\TrainingApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TrainingApplicationResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'إدارة التدريب';
    protected static ?string $modelLabel = 'طلب تسجيل';
    protected static ?string $pluralModelLabel = 'طلبات التسجيل';
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false; // Applications come from the frontend
    }

    // Define an Infolist for the View page for a clean read-only experience.
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('معلومات الطلب')
                    ->schema([
                        Infolists\Components\TextEntry::make('trainingProgram.title')->label('مقدم على برنامج'),
                        Infolists\Components\BadgeEntry::make('status')->label('حالة الطلب')
                            ->colors([
                                'warning' => 'pending',
                                'success' => 'approved',
                                'danger' => 'rejected'
                            ]),
                        Infolists\Components\TextEntry::make('created_at')->label('تاريخ التقديم')->dateTime(),
                    ])->columns(3),

                Infolists\Components\Section::make('البيانات الشخصية')
                    ->schema([
                        Infolists\Components\TextEntry::make('applicant_name')->label('الاسم'),
                        Infolists\Components\TextEntry::make('national_id')->label('الرقم القومي')->copyable(),
                        Infolists\Components\TextEntry::make('applicant_email')->label('البريد الإلكتروني')->copyable(),
                        Infolists\Components\TextEntry::make('phone')->label('رقم الهاتف')->copyable(),
                        Infolists\Components\TextEntry::make('gender')->label('الجنس'),
                        Infolists\Components\TextEntry::make('educational_qualification')->label('المؤهل الدراسي'),
                        Infolists\Components\TextEntry::make('specialization')->label('التخصص'),
                        Infolists\Components\TextEntry::make('highest_degree')->label('أعلى مؤهل'),
                    ])->columns(2),

                Infolists\Components\Section::make('معلومات العمل')
                    ->schema([
                        Infolists\Components\TextEntry::make('employment_status')->label('جهة العمل'),
                        Infolists\Components\TextEntry::make('current_position')->label('الوظيفة الحالية'),
                        Infolists\Components\TextEntry::make('job_address')->label('عنوان الوظيفة'),
                    ])->columns(2),

                Infolists\Components\Section::make('المرفقات والإجابات')
                    ->schema([
                        Infolists\Components\TextEntry::make('has_taken_previous_courses')
                            ->label('هل حصل على دورات سابقة؟')
                            ->formatStateUsing(fn(bool $state) => $state ? 'نعم' : 'لا'),
                        Infolists\Components\TextEntry::make('previous_courses_names')
                            ->label('أسماء الدورات السابقة')
                            ->placeholder('لم يذكر')
                            ->visible(fn($record) => $record->has_taken_previous_courses),

                        Infolists\Components\ImageEntry::make('national_id_front_image')->label('صورة البطاقة (وجه أمامي)')->width('100%')->columnSpan(1),
                        Infolists\Components\ImageEntry::make('national_id_back_image')->label('صورة البطاقة (وجه خلفي)')->width('100%')->columnSpan(1),
                    ])->columns(2),
            ]);
    }

    public static function form(Form $form): Form
    {
        // A simple form just for the admin to change the application status
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('تغيير حالة الطلب')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                    ])
                    ->required()->native(false),

                Forms\Components\Textarea::make('rejection_reason')
                    ->label('سبب الرفض (في حالة الرفض)')
                    ->rows(4)
                    // Show this field only if status is 'rejected'
                    ->visible(fn($get) => $get('status') === 'rejected'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicant_name')->label('اسم المتقدم')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('national_id')->label('الرقم القومي')->searchable(),
                Tables\Columns\TextColumn::make('trainingProgram.title')->label('البرنامج')->searchable()->sortable()->limit(30),
                Tables\Columns\BadgeColumn::make('status')->label('الحالة')
                    ->colors(['warning' => 'pending', 'success' => 'approved', 'danger' => 'rejected']),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ التقديم')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['pending' => 'قيد الانتظار', 'approved' => 'مقبول', 'rejected' => 'مرفوض']),
                Tables\Filters\SelectFilter::make('training_program_id')->relationship('trainingProgram', 'title')->label('البرنامج'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->label('تغيير الحالة'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // ===== NAVIGATION BADGE & NOTIFICATION COUNT =====
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger'; // Red badge for urgent review
    }

    // ===== GLOBAL SEARCH CONFIGURATION =====
    public static function getGloballySearchableAttributes(): array
    {
        return ['applicant_name', 'national_id', 'trainingProgram.title'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "طلب تسجيل: " . $record->applicant_name;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingApplications::route('/'),
            // Create page is disabled
            'view'  => Pages\ViewTrainingApplication::route('/{record}'),
            'edit'  => Pages\EditTrainingApplication::route('/{record}/edit'),
        ];
    }
}
