<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'الماليات والتقارير';
    protected static ?string $modelLabel = 'معاملة مالية';
    protected static ?string $pluralModelLabel = 'سجل المعاملات المالية';

    // Disable creating or editing transactions from the admin panel.
    // They are created automatically by the system.
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('تفاصيل المعاملة')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')->label('رقم المعاملة (UUID)'),
                        Infolists\Components\TextEntry::make('amount')->label('المبلغ')->money('EGP'),
                        // Infolists\Components\BadgeEntry::make('status')->label('الحالة')
                        //     ->colors(['warning' => 'pending', 'success' => 'completed', 'danger' => 'failed']),
                        Infolists\Components\TextEntry::make('completed_at')->label('تاريخ الإتمام')->dateTime(),
                    ])->columns(2),
                Infolists\Components\Section::make('معلومات الدفع')
                    ->schema([
                        Infolists\Components\TextEntry::make('payment_gateway')->label('بوابة الدفع'),
                        Infolists\Components\TextEntry::make('gateway_transaction_id')->label('رقم المعاملة في البوابة')->copyable(),
                    ])->columns(2),
                Infolists\Components\Section::make('معلومات المستفيد')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')->label('اسم المستخدم'),
                        Infolists\Components\TextEntry::make('user.national_id')->label('الرقم القومي'),
                    ])->columns(2),
                Infolists\Components\Section::make('تفاصيل مرتبطة')
                    ->schema([
                        Infolists\Components\TextEntry::make('transactionable_type')
                            ->label('نوع الطلب')
                            ->formatStateUsing(fn(string $state): string => class_basename($state)),
                        Infolists\Components\TextEntry::make('transactionable_id')->label('معرف الطلب'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable(),

                Tables\Columns\TextColumn::make('transactionable.service.title') // Example for a polymorphic relation
                    ->label('تفاصيل')
                    ->formatStateUsing(function ($record) {
                        $transactable = $record->transactionable;
                        if (!$transactable) return 'غير محدد';
                        // Add more models here as you integrate payments
                        if ($transactable instanceof \App\Models\ServiceSubmission) return "طلب خدمة: " . $transactable->service->title;
                        if ($transactable instanceof \App\Models\Enrollment) return "تسجيل برنامج: " . $transactable->trainingProgram->title;
                        return class_basename($transactable);
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('EGP')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('الحالة')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('تاريخ الدفع')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'قيد الانتظار', 'completed' => 'مكتملة', 'failed' => 'فشلت']),
                // You can add a date filter here
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Print Receipt action from the previous request
                Tables\Actions\Action::make('print_receipt')
                    ->label('طباعة إيصال')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Transaction $record): string => route('receipt.print', $record))
                    ->openUrlInNewTab()
                    ->visible(fn(Transaction $record) => $record->status === 'completed'),
            ])
            ->bulkActions([]) // Bulk actions are not needed for a log
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'), // Only index and view
        ];
    }
}
