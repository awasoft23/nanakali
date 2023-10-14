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
    protected static ?string $title = ' لیستی غیابات';
    protected ?string $heading = ' لیستی غیابات';
    protected static ?string $navigationLabel = ' لیستی غیابات';


    public function table(Table $table): Table
    {
        return $table
            ->modelLabel(' پارە وەرگرتنەکان')
            ->pluralModelLabel(' پارە وەرگرتنەکان')
            ->query(EmployeesAbsenses::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('Employees.name')->label('فەرمانبەر')->searchable(),
                TextColumn::make('created_at')->label('بەروار')->dateTime('d/m/Y'),
            ])
            ->filters([
                SelectFilter::make('employees_id')->label('فەرمانبەر')->options(
                    Employees::all()->pluck('name', 'id')
                )->searchable(),
                DateRangeFilter::make('created_at')->label('بەروار')
            ]);
    }



    protected static string $view = 'filament.resources.employees-resource.pages.employees-absenses-list';
}