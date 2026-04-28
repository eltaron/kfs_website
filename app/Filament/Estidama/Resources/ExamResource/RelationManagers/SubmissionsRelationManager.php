<?php

namespace App\Filament\Estidama\Resources\ExamResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $modelLabel = 'نتيجة متقدم';
    protected static ?string $pluralModelLabel = 'نتائج المتقدمين';
    protected static ?string $title = 'سجل التقديم والنتائج';

    // This is a read-only view, so we disable all modification actions
    public function canCreate(): bool
    {
        return false;
    }
    public function canAttach(): bool
    {
        return false;
    }
    public function canEdit($record): bool
    {
        return false;
    }
    public function canDelete($record): bool
    {
        return true;
    } // Allow deleting a submission

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المتقدم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.national_id')
                    ->label('الرقم القومي')
                    ->searchable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('الدرجة')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),

                Tables\Columns\IconColumn::make('passed')
                    ->label('الحالة')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-x-circle')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('تاريخ التقديم')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('passed')->label('حالة النجاح'),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                // You could add a ViewAction here to see the user's full answers
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ])
            ->defaultSort('score', 'desc'); // Show highest scores first
    }
}
