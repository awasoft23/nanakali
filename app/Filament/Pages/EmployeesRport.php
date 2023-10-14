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
    protected static ?string $title = 'ڕاپۆرتی فەرمانبەرەکان';
    protected static ?string $navigationGroup = 'ڕاپۆرتەکان';
    protected static ?int $navigationSort = 50;
    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Employees::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو'),
                Tables\Columns\TextColumn::make('phoneNumber')
                    ->label('ژمارەی مۆبایل')
                    ->copyable(),
                Tables\Columns\TextColumn::make('IDCardNumber')
                    ->label('ژمارەی ناسنامە'),
                Tables\Columns\TextColumn::make('DateOfWork')
                    ->label('بەرواری دەستبەکاربوون')
                    ->date('d/m/y'),
                Tables\Columns\TextColumn::make('totalAbsense')
                    ->label('کۆی غیابات')
                    ->numeric(0)
                ,
                Tables\Columns\TextColumn::make('salary')
                    ->label('مووچە')
                    ->formatStateUsing(fn($state, Employees $record) => $record->salaryType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('کۆی گشتی دۆلاری ئەمریکی')->using(function (Builder $query) {
                            return $query->where('salaryType', 0)->sum('salary');
                        })->numeric(2),
                        Summarizer::make()->label('کۆی گشتی دیناری عێراقی')->using(function (Builder $query) {
                            return $query->where('salaryType', 1)->sum('salary');
                        })->numeric(0)
                    ]),

            ]);
    }

    protected static string $view = 'filament.pages.employees-rport';
}