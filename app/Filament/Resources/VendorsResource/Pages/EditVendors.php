<?php

namespace App\Filament\Resources\VendorsResource\Pages;

use App\Filament\Resources\VendorsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendors extends EditRecord
{
    protected static string $resource = VendorsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}