<?php

namespace App\Filament\Resources\PurchaseProductsResource\Pages;

use App\Filament\Resources\PurchaseProductsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseProducts extends CreateRecord
{
    protected static string $resource = PurchaseProductsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}