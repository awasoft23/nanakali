<?php

namespace App\Filament\Resources\AvaliablesResource\Pages;

use App\Filament\Resources\AvaliablesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvaliables extends EditRecord
{
    protected static string $resource = AvaliablesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
