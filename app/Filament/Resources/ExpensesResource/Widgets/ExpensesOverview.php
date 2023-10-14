<?php

namespace App\Filament\Resources\ExpensesResource\Widgets;

use App\Models\Expenses;
use App\Models\ExpensesBalanceExchange;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpensesOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '5s';
    protected function getColumns(): int
    {

        return 4;
    }
    protected function getStats(): array
    {
        $startDate = Carbon::now()->startOfMonth();

        // Get the last day of the current month
        $endDate = Carbon::now()->endOfMonth();
        return [
            Stat::make('د.ع ', number_format(Expenses::where('priceType', 1)->sum('amount'), 0))
                ->description('کۆی گشتی خەرجییەکان')
                ->color('danger')
                ->descriptionIcon('fas-coins'),
            Stat::make('$ ', number_format(Expenses::where('priceType', 0)->sum('amount'), 2))
                ->description('کۆی گشتی خەرجییەکان')
                ->descriptionIcon('fas-hand-holding-dollar')
                ->color('danger'),
            Stat::make('د.ع ', number_format(Expenses::where('priceType', 1)->whereBetween('created_at', [$startDate, $endDate])->sum('amount'), 0))
                ->description('کۆی گشتی خەرجییەکان ئەم مانگە')
                ->color('danger')
                ->descriptionIcon('fas-coins'),
            Stat::make('$ ', number_format(Expenses::where('priceType', 0)->whereBetween('created_at', [$startDate, $endDate])->sum('amount'), 2))
                ->description('کۆی گشتی خەرجییەکان ئەم مانگە')
                ->descriptionIcon('fas-hand-holding-dollar')
                ->color('danger'),

        ];
    }
}