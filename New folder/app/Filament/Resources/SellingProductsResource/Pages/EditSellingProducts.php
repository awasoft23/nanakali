<?php

namespace App\Filament\Resources\SellingProductsResource\Pages;

use App\Filament\Resources\SellingProductsResource;
use Filament\Resources\Pages\EditRecord;

class EditSellingProducts extends EditRecord
{
    protected static string $resource = SellingProductsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}