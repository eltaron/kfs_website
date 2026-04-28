<?php

namespace App\Filament\Pages;

use App\Filament\Exports\TransactionExporter;
use App\Models\Transaction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Concerns\InteractsWithHeaderActions;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class FinancialReport extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithHeaderActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'الماليات والتقارير';
    protected static ?string $navigationLabel = 'التقرير المالي';
    protected static ?string $title = 'التقرير المالي الشامل';

    protected static string $view = 'filament.pages.financial-report';

    public ?array $filterData = [];

    public function mount(): void
    {
        $this->form->fill();
    }
     public static function canAccess(): bool
    {
        // يمكنك هنا وضع أسماء الأدوار التي يحق لها دخول هذه الصفحة تحديداً
        return auth()->user()->hasAnyRole([
           'super_admin', 
            "الإدارة المالية",
        ]);
    }
    /**
     * 🔹 Filters Form (Top of Page)
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema([
                    Grid::make(3)->schema([
                        DatePicker::make('date_from')->label('من تاريخ'),
                        DatePicker::make('date_to')->label('إلى تاريخ'),
                        Select::make('status')
                            ->label('حالة المعاملات')
                            ->options([
                                'completed' => 'مكتملة',
                                'failed' => 'فشلت',
                                'pending' => 'قيد الانتظار',
                            ]),
                    ]),
                ])
                ->statePath('filterData')
                ->live(),
        ];
    }

    /**
     * 🔹 Header Actions (Export)
     */
    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('export')
                ->label('تصدير التقرير (Excel)')
                ->exporter(TransactionExporter::class),
        ];
    }

    /**
     * 🔹 Header Stats
     */
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\FinancialReportStats::class,
        ];
    }

    /**
     * 🔹 Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getFilteredTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('المستخدم'),
                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->money('EGP')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->label('الحالة'),
                Tables\Columns\TextColumn::make('completed_at')->label('تاريخ الإتمام')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('receipt.print', $record))
                    ->openUrlInNewTab(),
            ]);
    }

    /**
     * 🔹 Filtered Query
     */
    public function getFilteredTableQuery(): Builder
    {
        return Transaction::query()
            ->with('user')
            ->when(
                $this->filterData['date_from'] ?? null,
                fn($q, $date) => $q->whereDate('completed_at', '>=', $date)
            )
            ->when(
                $this->filterData['date_to'] ?? null,
                fn($q, $date) => $q->whereDate('completed_at', '<=', $date)
            )
            ->when(
                $this->filterData['status'] ?? null,
                fn($q, $status) => $q->where('status', $status)
            );
    }
}
