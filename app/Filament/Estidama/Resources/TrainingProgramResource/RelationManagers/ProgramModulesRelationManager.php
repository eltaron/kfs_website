<?php

namespace App\Filament\Estidama\Resources\TrainingProgramResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProgramModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'programModules';

    protected static ?string $modelLabel = 'وحدة تعليمية';
    protected static ?string $pluralModelLabel = 'الوحدات التعليمية للبرنامج';
    protected static ?string $title = 'الوحدات التعليمية للبرنامج';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الوحدة/الدرس')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('description')
                    ->label('وصف موجز للوحدة')
                    ->columnSpanFull(),

                Forms\Components\Select::make('type')
                    ->label('نوع الوحدة')
                    ->options([
                        'offline_lecture' => 'محاضرة (أوفلاين)',
                        'video' => 'فيديو مسجل',
                        'pdf'   => 'ملف PDF',
                        'quiz'  => 'اختبار قصير (Quiz)',
                    ])
                    ->required()
                    ->default('offline_lecture'),

                // This field appears only if type is 'video' or 'pdf'
                Forms\Components\FileUpload::make('content_path')
                    ->label('ملف المحتوى (فيديو أو PDF)')
                    ->directory('program-modules')
                    ->visible(fn($get) => in_array($get('type'), ['video', 'pdf'])),

                Forms\Components\TextInput::make('order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الوحدة'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('النوع')
                    ->colors([
                        'gray' => 'offline_lecture',
                        'danger' => 'video',
                        'warning' => 'pdf',
                        'success' => 'quiz',
                    ]),

                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة وحدة جديدة'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order'); // Enable drag-and-drop reordering
    }
}
