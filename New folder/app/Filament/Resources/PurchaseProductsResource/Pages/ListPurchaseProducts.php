<?php

namespace App\Filament\Resources\PurchaseProductsResource\Pages;

use App\Filament\Resources\PurchaseProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseProducts extends ListRecords
{
    protected static string $resource = PurchaseProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
