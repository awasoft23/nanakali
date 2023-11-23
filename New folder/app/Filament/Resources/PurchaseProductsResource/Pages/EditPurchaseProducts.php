<?php

namespace App\Filament\Resources\PurchaseProductsResource\Pages;

use App\Filament\Resources\PurchaseProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseProducts extends EditRecord
{
    protected static string $resource = PurchaseProductsResource::class;



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}