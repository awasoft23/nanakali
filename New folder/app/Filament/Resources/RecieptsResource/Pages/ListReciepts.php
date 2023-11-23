<?php

namespace App\Filament\Resources\RecieptsResource\Pages;

use App\Filament\Resources\RecieptsResource;
use App\Filament\Resources\RecieptsResource\Widgets\PartnersOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReciepts extends ListRecords
{
    protected static string $resource = RecieptsResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PartnersOverview::class
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}