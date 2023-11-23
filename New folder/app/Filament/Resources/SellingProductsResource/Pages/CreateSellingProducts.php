<?php

namespace App\Filament\Resources\SellingProductsResource\Pages;

use App\Filament\Resources\SellingProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSellingProducts extends CreateRecord
{
    protected static string $resource = SellingProductsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}