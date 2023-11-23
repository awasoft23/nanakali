<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use App\Filament\Resources\ExpensesResource\Widgets\ExpensesOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpensesResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            ExpensesOverview::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->mutateFormDataUsing(function (array $data): array {

                $data['user_name'] = auth()->user()->name;

                return $data;

            }),
        ];
    }
}