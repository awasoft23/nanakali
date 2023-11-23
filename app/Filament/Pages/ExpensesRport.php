<?php

namespace App\Filament\Pages;

use App\Models\Expenses;
use App\Models\ExpensesTypes;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ExpensesRport extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-layer-group';
    protected static ?string $title = 'تقرير المصاريف';
    protected static ?string $navigationGroup = 'تقاریر';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Expenses::query())
            ->columns([
                TextColumn::make('ExpensesTypes.ExpenseType')
                    ->label('نوع الالمصاریف'),
                TextColumn::make('note')
                    ->label('الملاحظة')
                ,
                TextColumn::make('amount')

                    ->label('کمیة')
                    ->formatStateUsing(fn($state, Expenses $record) => $record->priceType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('مجموع دولار الامریکی')->using(function (Builder $query) {
                            return $query->where('priceType', 0)->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('إجمالي الدينار العراقي')->using(function (Builder $query) {
                            return $query->where('priceType', 1)->sum('amount');
                        })->numeric(0)
                    ])
                ,
                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->date('d/m/y')

                ,
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('تاریخ'),
                SelectFilter::make('expenses_type_id')->options(ExpensesTypes::all()->pluck('ExpenseType', 'id'))->label('نوع الالمصاریف'),
                SelectFilter::make('priceType')->options([
                    0 => 'الدولار الأمريكي',
                    1 => 'الدينار العراقي'
                ])->label('نوع العملة')
            ], layout: FiltersLayout::AboveContent);
    }




    protected static string $view = 'filament.pages.expenses-rport';
}
