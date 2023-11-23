<?php

namespace App\Filament\Resources\ReturnProductsResource\Pages;

use App\Filament\Resources\ReturnProductsResource;
use App\Models\Currencies;
use App\Models\CustomerPayments;
use App\Models\Customers;
use App\Models\LossedProducts;
use App\Models\SellingInvoice;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Carbon;

class ManageReturnProducts extends ManageRecords
{
    protected static string $resource = ReturnProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function ($record) {

                if ($record->status) {
                    unset($record['customers_id']);
                    unset($record['status']);
                    unset($record['id']);
                    LossedProducts::create([
                        'sallingPrice' => $record->sallingPrice,
                        'qty' => $record->qty,
                        'selling_products_id' => $record->selling_products_id
                    ]);
                }
            }),
        ];
    }
}
