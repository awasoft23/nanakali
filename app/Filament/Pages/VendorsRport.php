<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Vendors;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class VendorsRport extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-building-user';
    protected static ?string $title = 'تقارير البائعين';
    protected static ?string $navigationGroup = 'تقاریر';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Vendors::query())
            ->columns([
                TextColumn::make('name')
                    ->label('اسم'),
                TextColumn::make('phone')
                    ->label('رقم الهاتف'),
                TextColumn::make('address')
                    ->label('عنوان'),
                TextColumn::make('purchasing_invoice_sum_amount')->sum('PurchasingInvoice', 'amount')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(0))
                    ->label('مجموع الفواتیر'),
                TextColumn::make('purchasing_invoice_sum_paymented')->sum('PurchasingInvoice', 'paymented')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(0))
                    ->label('مبلغ واصل'),
                TextColumn::make('created_at')
                    ->suffix(' $ ')
                    ->formatStateUsing(fn($record) => number_format($record->purchasing_invoice_sum_amount - $record->purchasing_invoice_sum_paymented))
                    ->summarize([
                        Summarizer::make()->using(function (Builder $query) {
                            $query1 = clone $query;
                            return $query->sum('purchasing_invoice_sum_amount') - $query1->sum('purchasing_invoice_sum_paymented');
                        })->numeric(2)->label('مجموع')
                    ])
                    ->label('دین'),
            ])
        ;
    }
    protected static string $view = 'filament.pages.vendors-rport';
}