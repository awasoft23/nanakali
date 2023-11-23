<?php

namespace App\Filament\Resources\SalaryResource\Pages;

use App\Filament\Resources\SalaryResource;
use App\Models\Employees;
use App\Models\Expenses;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSalaries extends ManageRecords
{
    protected static string $resource = SalaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->after(function($record){
                $emp = Employees::find($record->employees_id);
                Expenses::create([
                    'expenses_type_id' => 1,
                    'note' => 'راتب الموظف: ' . $emp->name,
                    'priceType' => $emp->salaryType,
                    'amount' => $record->amount,
                    'user_name' => auth()->user()->name,
                    'name' => $emp->name
                ]);
            }),
        ];
    }
}
