<?php

namespace App\Filament\Resources\VendorsResource\Pages;

use App\Filament\Resources\VendorsResource;
use Filament\Resources\Pages\ViewRecord;

class ViewVendors extends ViewRecord
{
    protected static string $resource = VendorsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}