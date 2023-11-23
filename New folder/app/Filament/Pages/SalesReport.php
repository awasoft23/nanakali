<?php

namespace App\Filament\Pages;

use App\Models\SellingInvoiceProducts;
use App\Models\SellingProducts;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class SalesReport extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-bag-shopping';
    protected static ?string $title = 'ڕاپۆرتی فرۆشتن';
    protected static ?string $navigationGroup = 'ڕاپۆرتەکان';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(SellingInvoiceProducts::query())
            ->columns([
                TextColumn::make('SellingInvoice.id')->label('ژ. پسولە'),
                TextColumn::make('SellingProducts.name')
                    ->label('ناوی کاڵا'),
                TextColumn::make('SellingProducts.code')
                    ->label('کۆدی کاڵا'),
                TextColumn::make('SellingProducts.colorCofe')
                    ->label('ڕەنگ'),
                TextColumn::make('sallingPrice')
                    ->suffix(' $ ')
                    ->label('نرخ'),
                TextColumn::make('qty')
                    ->formatStateUsing(fn($state, $record) => number_format($state, 0) . ' - ' . SellingProducts::find($record->selling_products_id)->unit)
                    ->label('بڕ'),
                TextColumn::make('total')
                    ->label('کۆی گشتی')
                    ->formatStateUsing(fn($record) => '$ ' . number_format($record->qty * $record->sallingPrice, 2))
                    ->summarize(
                        [
                            Summarizer::make()->label(' کۆی گشتی')->using(
                                function (Builder $query) {
                                    return $query->sum(DB::raw('(qty * sallingPrice)'));
                                }
                            )->numeric(2),

                        ]
                    ),
                TextColumn::make('created_at')->date('d/m/y')->label('بەروار')

            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار')
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }



    protected static string $view = 'filament.pages.sales-report';
}