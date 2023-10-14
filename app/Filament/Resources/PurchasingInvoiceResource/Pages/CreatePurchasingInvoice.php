<?php

namespace App\Filament\Resources\PurchasingInvoiceResource\Pages;

use App\Filament\Resources\PurchasingInvoiceResource;
use App\Models\Currencies;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchasingInvoice extends CreateRecord
{
    protected static string $resource = PurchasingInvoiceResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dolarPrice'] = Currencies::find(1)->dinarPrice;

        return $data;
    }
}