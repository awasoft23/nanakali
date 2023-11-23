<?php

namespace App\Filament\Resources\RecieptsResource\Pages;

use App\Filament\Resources\RecieptsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReciepts extends CreateRecord
{
    protected static string $resource = RecieptsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}