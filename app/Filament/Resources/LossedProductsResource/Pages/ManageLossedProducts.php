<?php

namespace App\Filament\Resources\LossedProductsResource\Pages;

use App\Filament\Resources\LossedProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLossedProducts extends ManageRecords
{
    protected static string $resource = LossedProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
