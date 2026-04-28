<?php

namespace App\Filament\Estidama\Resources;

use App\Filament\Estidama\Resources\ExamResource\Pages;
use App\Filament\Estidama\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'إدارة الامتحانات';
    protected static ?string $modelLabel = 'امتحان';
    protected static ?string $pluralModelLabel = 'الامتحانات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الامتحان الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان الامتحان')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('training_program_id')
                            ->relationship('trainingProgram', 'title')
                            ->label('مرتبط بالبرنامج التدريبي (اختياري)')
                            ->helperText('يمكنك ترك هذا الحقل فارغًا لإنشاء امتحان عام.')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('duration_minutes')
                            ->label('مدة الامتحان (بالدقائق)')
                            ->required()
                            ->numeric()
                            ->minValue(1),

                        Forms\Components\TextInput::make('passing_score')
                            ->label('درجة النجاح (%)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->suffix('%'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الامتحان')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('trainingProgram.title')
                    ->label('البرنامج المرتبط')
                    ->placeholder('امتحان عام')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('المدة')
                    ->numeric()
                    ->sortable()
                    ->suffix(' دقيقة'),

                Tables\Columns\TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('عدد الأسئلة'),

                Tables\Columns\TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label('عدد المتقدمين'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // To manage questions and see submissions
            RelationManagers\QuestionsRelationManager::class,
            RelationManagers\SubmissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}
