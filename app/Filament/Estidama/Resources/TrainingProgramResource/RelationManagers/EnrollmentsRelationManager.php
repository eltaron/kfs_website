<?php

namespace App\Filament\Estidama\Resources\TrainingProgramResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Enrollment;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $modelLabel = 'تسجيل';
    protected static ?string $pluralModelLabel = 'المسجلون في البرنامج';

    protected static ?string $title = 'المسجلون في البرنامج';

    // Disable creating/attaching enrollments from here, it should come from users.
    public function canCreate(): bool
    {
        return false;
    }
    public function canAttach(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        // Simple form just to update status
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                        'completed' => 'مكتمل',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name') // This won't be visible but helps Filament identify records
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المتدرب')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.national_id')
                    ->label('الرقم القومي')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('حالة التسجيل')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                        'primary' => 'completed',
                    ]),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->label('تاريخ التسجيل')
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'approved' => 'مقبول',
                        'rejected' => 'مرفوض',
                        'completed' => 'مكتمل',
                    ])
            ])
            ->headerActions([]) // No "create" or "attach" buttons
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Or DetachAction if you don't want to delete the enrollment record
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
