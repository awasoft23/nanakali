<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use App\Models\ExpensesBalanceExchange as ModelsExpensesBalanceExchange;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ExpensesBalanceExchange extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;
    protected static ?string $title = ' قائمة الودائع';

    protected ?string $heading = ' قائمة الودائع';
    protected static ?string $navigationLabel = ' قائمة الودائع';
    protected static string $resource = ExpensesResource::class;


    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(' راس المال')
            ->pluralModelLabel(' راس المال')
            ->query(ModelsExpensesBalanceExchange::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('note')->label('الملاحظة')->searchable(),
                TextColumn::make('amount')
                    ->suffix(fn($record) => ' ' . $record->priceType . ' ')
                    ->numeric(2)
                    ->label('مبلغ من المال')
                    ->searchable()
                    ->summarize([
                        Summarizer::make()->label('الدولار الأمريكي')->using(function (Builder $query) {
                            return $query->where('priceType', '$')->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('الدينار العراقي')->using(function (Builder $query) {
                            return round($query->where('priceType', '!=', '$')->sum(DB::raw('amount')) / 250) * 250;
                        })->numeric(0),

                    ]),
                TextColumn::make('created_at')->label('الوقت و التاريخ')->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([

                DateRangeFilter::make('created_at')->label('تاریخ')
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    protected static string $view = 'filament.resources.expenses-resource.pages.expenses-balance-exchange';
}
