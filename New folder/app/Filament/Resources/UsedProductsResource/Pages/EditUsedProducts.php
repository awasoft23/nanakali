<?php

namespace App\Filament\Resources\UsedProductsResource\Pages;

use App\Filament\Resources\UsedProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsedProducts extends EditRecord
{
    protected static string $resource = UsedProductsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}