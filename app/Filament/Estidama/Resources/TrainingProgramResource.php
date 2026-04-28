<?php

namespace App\Filament\Estidama\Resources;

use App\Filament\Estidama\Resources\TrainingProgramResource\Pages;
use App\Filament\Estidama\Resources\TrainingProgramResource\RelationManagers;
use App\Models\TrainingProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TrainingProgramResource extends Resource
{
    protected static ?string $model = TrainingProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'إدارة التدريب';
    protected static ?string $modelLabel = 'برنامج تدريبي';
    protected static ?string $pluralModelLabel = 'البرامج التدريبية';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('MainTabs')
                    ->columnSpanFull()
                    ->tabs([
                        // Tab 1: Basic Program Details
                        Forms\Components\Tabs\Tab::make('تفاصيل البرنامج')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Select::make('training_center_id')
                                    ->relationship('trainingCenter', 'name')
                                    ->label('المركز التدريبي')
                                    ->searchable()->preload()->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->label('اسم المركز الجديد')->required(),
                                    ]),

                                Forms\Components\TextInput::make('title')->label('عنوان البرنامج')->required()->maxLength(255)->columnSpanFull(),

                                Forms\Components\RichEditor::make('description')->label('وصف تفصيلي للبرنامج')->required()->columnSpanFull(),
                            ]),

                        // Tab 2: Image, Dates, and Status
                        Forms\Components\Tabs\Tab::make('الصورة والتواريخ')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Forms\Components\FileUpload::make('image')->label('صورة البرنامج')->image()->directory('programs')->required()->columnSpanFull(),
                                Forms\Components\DatePicker::make('start_date')->label('تاريخ بدء البرنامج')->required()->native(false),
                                Forms\Components\DatePicker::make('end_date')->label('تاريخ انتهاء البرنامج')->required()->after('start_date')->native(false),
                                Forms\Components\Select::make('status')
                                    ->label('حالة البرنامج')
                                    ->options(['open' => 'مفتوح للتسجيل', 'ongoing' => 'جاري', 'closed' => 'مغلق'])
                                    ->required()->native(false)->default('open'),
                            ])->columns(2),

                        // Tab 3: Final Exam
                        Forms\Components\Tabs\Tab::make('الامتحان النهائي')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                Forms\Components\Select::make('exam_id')
                                    ->relationship('finalExam', 'title')
                                    ->label('اختر الامتحان الشامل لهذا البرنامج (اختياري)')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('يمكنك إنشاء امتحان جديد من صفحة "الامتحانات".')
                                    ->createOptionForm([ // Quick-create a new exam from a modal
                                        Forms\Components\TextInput::make('title')->label('عنوان الامتحان الجديد')->required(),
                                        Forms\Components\TextInput::make('duration_minutes')->label('مدة الامتحان (بالدقائق)')->numeric()->required(),
                                        Forms\Components\TextInput::make('passing_score')->label('درجة النجاح (%)')->numeric()->required()->minValue(1)->maxValue(100),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('صورة')->square()->width(60),
                Tables\Columns\TextColumn::make('title')->label('عنوان البرنامج')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('trainingCenter.name')->label('المركز التدريبي')->badge(),
                Tables\Columns\BadgeColumn::make('status')->label('الحالة')
                    ->colors(['primary' => 'open', 'success' => 'ongoing', 'warning' => 'closed']),
                Tables\Columns\IconColumn::make('exam_id')->label('له امتحان؟')
                    ->boolean()->trueIcon('heroicon-o-check-circle')->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('enrollments_count')->counts('enrollments')->label('عدد المسجلين')->sortable(),
                Tables\Columns\TextColumn::make('start_date')->label('يبدأ في')->date()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['open' => 'مفتوح', 'ongoing' => 'جاري', 'closed' => 'مغلق']),
                Tables\Filters\SelectFilter::make('training_center_id')->relationship('trainingCenter', 'name')->label('المركز التدريبي'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ])
            ->defaultSort('start_date', 'desc');
    }

    // ... (rest of the file: getNavigationBadge, getGlobalSearch, etc.)

    public static function getRelations(): array
    {
        return [
            RelationManagers\EnrollmentsRelationManager::class,
            RelationManagers\ProgramModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingPrograms::route('/'),
            'create' => Pages\CreateTrainingProgram::route('/create'),
            'edit' => Pages\EditTrainingProgram::route('/{record}/edit'),
        ];
    }
}
