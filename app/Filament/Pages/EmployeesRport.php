<?php

namespace App\Filament\Pages;

use App\Models\Employees;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class EmployeesRport extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'fas-users-viewfinder';
    protected static ?string $title ='تقرير الموظف';
    protected static ?string $navigationGroup = 'تقاریر';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Employees::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم'),
                Tables\Columns\TextColumn::make('phoneNumber')
                    ->label('رقم الهاتف')
                    ->copyable(),
                Tables\Columns\TextColumn::make('IDCardNumber')
                    ->label('رقم الهویة'),
                Tables\Columns\TextColumn::make('DateOfWork')
                    ->label('تاريخ البدء')
                    ->date('d/m/y'),
                Tables\Columns\TextColumn::make('totalAbsense')
                    ->label('الغياب التام')
                    ->numeric(0)
                ,
                Tables\Columns\TextColumn::make('salary')
                    ->label('راتب')
                    ->formatStateUsing(fn($state, Employees $record) => $record->salaryType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('مجموع دولار الامریکی')->using(function (Builder $query) {
                            return $query->where('salaryType', 0)->sum('salary');
                        })->numeric(2),
                        Summarizer::make()->label('إجمالي الدينار العراقي')->using(function (Builder $query) {
                            return $query->where('salaryType', 1)->sum('salary');
                        })->numeric(0)
                    ]),

            ]);
    }

    protected static string $view = 'filament.pages.employees-rport';
}
