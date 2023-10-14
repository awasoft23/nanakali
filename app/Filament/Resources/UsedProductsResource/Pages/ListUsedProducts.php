<?php

namespace App\Filament\Resources\UsedProductsResource\Pages;

use App\Filament\Resources\UsedProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsedProducts extends ListRecords
{
    protected static string $resource = UsedProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
