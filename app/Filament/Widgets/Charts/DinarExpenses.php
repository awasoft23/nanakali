<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Expenses as ModelsExpenses;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DinarExpenses extends ChartWidget
{
    protected static ?string $heading = "المصاريف بالدينار العراقي";
    public ?string $filter = '1';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        switch ($activeFilter) {
            case '1':
                $data = Trend::query(ModelsExpenses::query()->where('priceType', '!=', '$'))
                    ->between(
                        start: now()->startOfDay(),
                        end: now()->endOfDay(),
                    )
                    ->perHour()
                    ->sum('amount');
                return [
                    'datasets' => [
                        [
                            'label' => 'المصاریف',
                            'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        ],
                    ],
                    'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('H')),
                ];
                break;
            case '2':
                $data = Trend::query(ModelsExpenses::query()->where('priceType', '!=', '$'))
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->sum('amount');
                return [
                    'datasets' => [
                        [
                            'label' => 'المصاریف',
                            'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        ],
                    ],
                    'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('D')),
                ];
                break;
            case '3':
                $data = Trend::query(ModelsExpenses::query()->where('priceType', '!=', '$'))
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perDay()
                    ->sum('amount');
                return [
                    'datasets' => [
                        [
                            'label' => 'المصاریف',
                            'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        ],
                    ],
                    'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('d')),
                ];
                break;
            case '4':
                $data = Trend::query(ModelsExpenses::query()->where('priceType', '!=', '$'))
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('amount');
                return [
                    'datasets' => [
                        [
                            'label' => 'المصاریف',
                            'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        ],
                    ],
                    'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('m')),
                ];
                break;
            default:
                $data = Trend::query(ModelsExpenses::query()->where('priceType', '!=', '$'))
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('amount');
                return [
                    'datasets' => [
                        [
                            'label' => 'المصاریف',
                            'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        ],
                    ],
                    'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('m')),
                ];
                break;



        }


    }
    protected function getFilters(): ?array
    {
        return [
            '1' => 'اليوم',
            '2' => 'هذا الاسبوع',
            '3' => 'هذا الشهر',
            '4' => 'هذا العام',
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
