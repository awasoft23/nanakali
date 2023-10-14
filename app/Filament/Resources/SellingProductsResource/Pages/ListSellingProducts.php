<?php

namespace App\Filament\Resources\SellingProductsResource\Pages;

use App\Filament\Resources\SellingProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellingProducts extends ListRecords
{
    protected static string $resource = SellingProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
