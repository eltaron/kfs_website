<?php

namespace App\Filament\Estidama\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\In;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $modelLabel = 'سؤال';
    protected static ?string $pluralModelLabel = 'أسئلة الامتحان';
    protected static ?string $title = 'بنك الأسئلة';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('question_text')
                    ->label('نص السؤال')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Repeater::make('options')
                    ->label('الخيارات')
                    ->schema([
                        Forms\Components\TextInput::make('option_text')
                            ->label('نص الخيار')
                            ->required(),
                    ])
                    ->addActionLabel('أضف خيارًا جديدًا')
                    ->minItems(2)
                    ->reorderableWithDragAndDrop()
                    ->columnSpanFull(),

                Forms\Components\Select::make('correct_answer')
                    ->label('الإجابة الصحيحة')
                    ->required()
                    // Dynamically populate options from the Repeater
                    ->options(function (Forms\Get $get) {
                        $options = $get('options');
                        if (!$options) {
                            return [];
                        }
                        // Create an associative array where key and value are the same
                        return array_combine(
                            array_column($options, 'option_text'),
                            array_column($options, 'option_text')
                        );
                    })
                    ->searchable()
                    ->native(false)
                    // Rule to ensure the selected answer is one of the provided options
                    ->rule(function (Forms\Get $get): \Closure {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $options = array_column($get('options'), 'option_text');
                            if (!in_array($value, $options)) {
                                $fail('الإجابة الصحيحة المختارة يجب أن تكون واحدة من الخيارات المتاحة.');
                            }
                        };
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->label('نص السؤال')
                    ->limit(60)
                    ->wrap(),

                Tables\Columns\TextColumn::make('correct_answer')
                    ->label('الإجابة الصحيحة'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة سؤال جديد'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }
}
