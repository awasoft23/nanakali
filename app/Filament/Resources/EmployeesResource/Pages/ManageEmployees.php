<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use App\Models\Employees;
use App\Models\EmployeesAbsenses;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;

class ManageEmployees extends ManageRecords
{
    protected static string $resource = EmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('list')->label('لیستی غیابات')->color(Color::Gray)->icon('far-square-check')->url('/employees/absenses')->openUrlInNewTab(),
            Action::make('absesne')->label('غیابات')->hidden(auth()->user()->role == 1)->form([
                Select::make('id')->label('فەرمانبەر')->searchable()->options(Employees::all()->pluck('name', 'id'))
            ])->action(function (array $data) {
                $record = Employees::find($data['id']);
                $record->totalAbsense = $record->totalAbsense + 1;
                $record->monthAbsense = $record->monthAbsense + 1;
                $record->save();
                EmployeesAbsenses::create([
                    'employees_id' => $data['id']
                ]);

            })->requiresConfirmation()
                ->modalButton('غیابدان')
                ->modalIcon('fas-bolt')
                ->modalDescription('فەرمانبەر هەڵبژێرە بۆ ئەوەی کرداری غیابدان بە سەرکەوتووی ئەنجام بدەیت.')
                ->icon('fas-bolt')
                ->color(Color::Red),
            Actions\CreateAction::make(),
        ];
    }
}