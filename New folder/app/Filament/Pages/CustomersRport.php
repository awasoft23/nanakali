<?php

namespace App\Filament\Pages;

use App\Models\Customers;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class CustomersRport extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-user-gear';
    protected static ?string $title = 'ڕاپۆرتی کڕیارەکان';
    protected static ?string $navigationGroup = 'ڕاپۆرتەکان';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Customers::query())
            ->columns([
                TextColumn::make('name')
                    ->label('ناو'),
                TextColumn::make('phone')
                    ->label('ژمارەی مۆبایل'),
                TextColumn::make('address')
                    ->label('ناونیشان'),
                TextColumn::make('selling_invoice_sum_amount')->sum('SellingInvoice', 'amount')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(0))
                    ->label('بڕی پسولەکان'),
                TextColumn::make('selling_invoice_sum_paymented')->sum('SellingInvoice', 'paymented')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(0))
                    ->label('واصل کراو'),
                TextColumn::make('created_at')
                    ->suffix(' $ ')
                    ->formatStateUsing(fn($record) => number_format($record->selling_invoice_sum_amount - $record->selling_invoice_sum_paymented))
                    ->summarize([
                        Summarizer::make()->using(function (Builder $query) {
                            $query1 = clone $query;
                            return $query->sum('selling_invoice_sum_amount') - $query1->sum('selling_invoice_sum_paymented');
                        })->numeric(2)->label('کۆی گشتی')
                    ])
                    ->label('قەرز'),
            ])
        ;
    }

    protected static string $view = 'filament.pages.customers-rport';
}