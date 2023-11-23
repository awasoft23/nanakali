<?php

namespace App\Filament\Resources\EmployeesResource\Pages;

use App\Filament\Resources\EmployeesResource;
use App\Models\Employees;
use App\Models\EmployeesAbsenses;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class EmployeesAbsensesList extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;
    protected static string $resource = EmployeesResource::class;
    protected static ?string $title = 'قائمة الغياب';
    protected ?string $heading = 'قائمة الغياب';
    protected static ?string $navigationLabel = 'قائمة الغياب';


    public function table(Table $table): Table
    {
        return $table
            ->modelLabel('الوصلات')
            ->pluralModelLabel('الوصلات')
            ->query(EmployeesAbsenses::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('Employees.name')->label('موظف')->searchable(),
                TextColumn::make('created_at')->label('تاریخ')->dateTime('d/m/Y'),
            ])
            ->filters([
                SelectFilter::make('employees_id')->label('موظف')->options(
                    Employees::all()->pluck('name', 'id')
                )->searchable(),
                DateRangeFilter::make('created_at')->label('تاریخ')
            ]);
    }



    protected static string $view = 'filament.resources.employees-resource.pages.employees-absenses-list';
}
