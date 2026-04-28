<?php

namespace App\Filament\Estidama\Resources;

use App\Filament\Estidama\Resources\ExamQuestionResource\Pages;
use App\Models\ExamQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExamQuestionResource extends Resource
{
    protected static ?string $model = ExamQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'إدارة الامتحانات';
    protected static ?string $modelLabel = 'سؤال';
    protected static ?string $pluralModelLabel = 'بنك الأسئلة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('exam_id')
                            ->relationship('exam', 'title')
                            ->label('ينتمي إلى امتحان')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\RichEditor::make('question_text')
                            ->label('نص السؤال')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Repeater::make('options')
                            ->label('الخيارات')
                            ->schema([
                                Forms\Components\TextInput::make('option_text')->label('نص الخيار')->required(),
                            ])
                            ->addActionLabel('أضف خيارًا جديدًا')
                            ->minItems(2)
                            ->reorderableWithDragAndDrop()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('correct_answer')
                            ->label('الإجابة الصحيحة')
                            ->required()
                            ->options(function (Forms\Get $get) {
                                $options = collect($get('options'))
                                    ->pluck('option_text')
                                    ->filter(fn($value) => filled($value))
                                    ->values();

                                return $options->mapWithKeys(fn($value) => [$value => $value])->toArray();
                            })

                            ->native(false)
                            ->searchable(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('نص السؤال')
                    ->limit(50)
                    ->wrap() // Allows text to wrap to the next line
                    ->searchable(),

                Tables\Columns\TextColumn::make('exam.title')
                    ->label('الامتحان')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('options')
                    ->label('عدد الخيارات')
                    ->formatStateUsing(fn(?array $state): int => count($state ?? [])) // Count the items in the options array
                    ->numeric(),

                Tables\Columns\TextColumn::make('correct_answer')
                    ->label('الإجابة الصحيحة')
                    ->limit(30)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('exam_id')
                    ->relationship('exam', 'title')
                    ->label('تصفية حسب الامتحان'),
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

    // We don't need relations for this resource itself
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExamQuestions::route('/'),
            'create' => Pages\CreateExamQuestion::route('/create'),
            'edit' => Pages\EditExamQuestion::route('/{record}/edit'),
        ];
    }
}
