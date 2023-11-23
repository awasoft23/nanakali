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
    protected static ?string $title = ' لیستی دانانی پارە';

    protected ?string $heading = ' لیستی دانانی پارە';
    protected static ?string $navigationLabel = ' لیستی دانانی پارە';
    protected static string $resource = ExpensesResource::class;


    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(' دانانی پارە')
            ->pluralModelLabel(' دانانی پارە')
            ->query(ModelsExpensesBalanceExchange::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('note')->label('تێبینی')->searchable(),
                TextColumn::make('amount')
                    ->suffix(fn($record) => ' ' . $record->priceType . ' ')
                    ->numeric(2)
                    ->label('بڕی پارە')
                    ->searchable()
                    ->summarize([
                        Summarizer::make()->label('دۆلاری ئەمریکی')->using(function (Builder $query) {
                            return $query->where('priceType', '$')->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('دیناری عێراقی')->using(function (Builder $query) {
                            return round($query->where('priceType', '!=', '$')->sum(DB::raw('amount')) / 250) * 250;
                        })->numeric(0),

                    ]),
                TextColumn::make('created_at')->label('کات و بەروار')->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([

                DateRangeFilter::make('created_at')->label('بەروار')
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