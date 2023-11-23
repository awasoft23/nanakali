<?php

namespace App\Filament\Pages;

use App\Models\Employees;
use App\Models\EmployeesAbsenses;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class EmployeesAbsensesList extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-user-xmark';
    protected static ?string $title = 'تقریر الغیابات';
    protected static ?string $navigationGroup = 'تقاریر';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(EmployeesAbsenses::query()->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('Employees.name')
                    ->label('اسم'),
                Tables\Columns\TextColumn::make('Employees.IDCardNumber')
                    ->label('رقم الهویة'),
                TextColumn::make('created_at')->label('تاریخ')->date('d/m/y')
            ])->filters([
                    DateRangeFilter::make('created_at')->label('تاریخ'),
                    SelectFilter::make('employees_id')->options(Employees::all()->pluck('name', 'id'))->label('موظف')
                ], layout: FiltersLayout::AboveContent);
    }
    protected static string $view = 'filament.pages.employees-absenses-list';
}