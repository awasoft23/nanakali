<?php

namespace App\Filament\Resources\SellingInvoiceResource\Pages;

use App\Filament\Resources\SellingInvoiceResource;
use App\Models\Currencies;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSellingInvoice extends CreateRecord
{
    protected static string $resource = SellingInvoiceResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dolarPrice'] = Currencies::find(1)->dinarPrice;

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}