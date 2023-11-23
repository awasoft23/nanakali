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
    protected static ?string $title = 'ڕاپۆرتی خەرجییەکان';
    protected static ?string $navigationGroup = 'ڕاپۆرتەکان';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Expenses::query())
            ->columns([
                TextColumn::make('ExpensesTypes.ExpenseType')
                    ->label('جۆری خەرجی'),
                TextColumn::make('note')
                    ->label('تێبینی')
                ,
                TextColumn::make('amount')

                    ->label('بڕ')
                    ->formatStateUsing(fn($state, Expenses $record) => $record->priceType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('کۆی گشتی دۆلاری ئەمریکی')->using(function (Builder $query) {
                            return $query->where('priceType', 0)->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('کۆی گشتی دیناری عێراقی')->using(function (Builder $query) {
                            return $query->where('priceType', 1)->sum('amount');
                        })->numeric(0)
                    ])
                ,
                TextColumn::make('created_at')
                    ->label('بەروار')
                    ->date('d/m/y')

                ,
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار'),
                SelectFilter::make('expenses_type_id')->options(ExpensesTypes::all()->pluck('ExpenseType', 'id'))->label('جۆری خەرجی'),
                SelectFilter::make('priceType')->options([
                    0 => 'دۆلاری ئەمریکی',
                    1 => 'دیناری عێراقی'
                ])->label('جۆری دراو')
            ], layout: FiltersLayout::AboveContent);
    }




    protected static string $view = 'filament.pages.expenses-rport';
}