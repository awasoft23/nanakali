<?php

namespace App\Filament\Pages;

use App\Models\PurchaseProducts;
use App\Models\PurchasingInvoiceProducts;
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

class PurchasingRport extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-truck-ramp-box';
    protected static ?string $title = 'ڕاپۆرتی کڕین';
    protected static ?string $navigationGroup = 'ڕاپۆرتەکان';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(PurchasingInvoiceProducts::query())
            ->columns([
                TextColumn::make('PurchaseProducts.code')->label('کۆدی کاڵا'),
                TextColumn::make('PurchaseProducts.name')->label('ناوی کاڵا'),
                TextColumn::make('purchase_price')->label('نرخی کڕین')->prefix(' $ ')->numeric(2),
                TextColumn::make('qty')->label('ژمارە')
                    ->formatStateUsing(fn($state, PurchasingInvoiceProducts $record) => number_format($state, 0) . ' - ' . PurchaseProducts::find($record->purchase_products_id)->unit),
                TextColumn::make('total')
                    ->label('کۆی گشتی')
                    ->formatStateUsing(fn(PurchasingInvoiceProducts $record) => '$ ' . number_format($record->qty * $record->purchase_price, 2))
                    ->summarize(
                        Summarizer::make()->label('کۆی گشتی')->using(
                            function (Builder $query) {
                                return $query->sum(DB::raw('(qty * purchase_price)'));
                            }
                        )->numeric(2)
                    ),
                TextColumn::make('created_at')->date('d/m/y')->label('بەروار')


            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار')
            ], layout: FiltersLayout::AboveContent);
    }



    protected static string $view = 'filament.pages.purchasing-rport';
}