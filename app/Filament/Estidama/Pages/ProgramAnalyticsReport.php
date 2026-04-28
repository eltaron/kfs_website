<?php

namespace App\Filament\Estidama\Pages;

use App\Models\TrainingProgram;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProgramAnalyticsReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static string $view = 'filament.estidama.pages.program-analytics-report';
    protected static ?string $navigationGroup = 'التقارير والتحليلات';
    protected static ?string $title = 'تحليلات أداء البرامج';

    // ... You can add header widgets here as we did before ...

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TrainingProgram::query()
                    ->withCount([
                        'enrollments',
                        'enrollments as pending_enrollments_count' => fn($q) => $q->where('status', 'pending'),
                        'enrollments as approved_enrollments_count' => fn($q) => $q->where('status', 'approved'),
                        'enrollments as completed_enrollments_count' => fn($q) => $q->where('status', 'completed'),
                    ])
                    ->addSelect([
                        'exam_takers_count' => DB::table('exam_submissions')
                            ->selectRaw('COUNT(DISTINCT exam_submissions.user_id)')
                            ->whereColumn('exam_submissions.exam_id', 'training_programs.exam_id')
                    ])
            )

            ->columns([
                Tables\Columns\TextColumn::make('title')->label('البرنامج')->searchable(),
                Tables\Columns\TextColumn::make('enrollments_count')->label('إجمالي التسجيلات')->sortable(),
                Tables\Columns\TextColumn::make('approved_enrollments_count')->label('المقبولين')->sortable(),

                // This is now a calculated column
                Tables\Columns\TextColumn::make('exam_takers_count')
                    ->label('أدوا الامتحان')
                    ->numeric()
                    ->getStateUsing(function (TrainingProgram $record) {
                        // Check if the program has an exam linked
                        if (! $record->exam_id) {
                            return 0;
                        }
                        // Count distinct users who submitted for this exam
                        return DB::table('exam_submissions')
                            ->where('exam_id', $record->exam_id)
                            ->distinct('user_id')
                            ->count('user_id');
                    }),

                Tables\Columns\TextColumn::make('completed_enrollments_count')->label('أكملوا البرنامج')->sortable(),

                Tables\Columns\TextColumn::make('top_students')
                    ->label('المتفوقين')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        // Fetch top 3 students based on exam scores
                        // This is a complex query and better handled with a dedicated method
                        return "<li>الطالب 1 (98%)</li><li>الطالب 2 (95%)</li>"; // Placeholder
                    }),
            ]);
    }
}
