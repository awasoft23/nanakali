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
    protected static ?string $title = "تقرير الشراء";
    protected static ?string $navigationGroup = 'تقاریر';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(PurchasingInvoiceProducts::query())
            ->columns([
                TextColumn::make('PurchaseProducts.code')->label('كود المواد'),
                TextColumn::make('PurchaseProducts.name')->label('اسم المواد'),
                TextColumn::make('purchase_price')->label('سعر الشراء')->prefix(' $ ')->numeric(2),
                TextColumn::make('qty')->label('رقم')
                    ->formatStateUsing(fn($state, PurchasingInvoiceProducts $record) => number_format($state, 0) . ' - ' . PurchaseProducts::find($record->purchase_products_id)->unit),
                TextColumn::make('total')
                    ->label('مجموع')
                    ->formatStateUsing(fn(PurchasingInvoiceProducts $record) => '$ ' . number_format($record->qty * $record->purchase_price, 2))
                    ->summarize(
                        Summarizer::make()->label('مجموع')->using(
                            function (Builder $query) {
                                return $query->sum(DB::raw('(qty * purchase_price)'));
                            }
                        )->numeric(2)
                    ),
                TextColumn::make('created_at')->date('d/m/y')->label('تاریخ')


            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('تاریخ')
            ], layout: FiltersLayout::AboveContent);
    }



    protected static string $view = 'filament.pages.purchasing-rport';
}
