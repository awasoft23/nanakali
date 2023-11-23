<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ExpensesResource\Widgets\ExpensesOverview;
use App\Filament\Resources\RecieptsResource\Widgets\PartnersOverview;
use App\Filament\Widgets\AwaTechReport;
use App\Filament\Widgets\BudgetReport;
use App\Filament\Widgets\Charts\DinarExpenses;
use App\Filament\Widgets\Charts\Expenses as ChartsExpenses;
use App\Filament\Widgets\CustomersReport;
use App\Filament\Widgets\EmployeeExpenses;
use App\Filament\Widgets\EmployeesReport;
use App\Filament\Widgets\EpensesReport;
use App\Filament\Widgets\Expenses;
use App\Filament\Widgets\PurchasingRepot;
use App\Filament\Widgets\SallingRepot;
use App\Filament\Widgets\VendorsReport;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'fas-gauge';
    protected static ?string $title = 'لوحة التحكم';
    protected function getHeaderWidgets(): array
    {
        return [
            AwaTechReport::class,
            BudgetReport::class,
            CustomersReport::class,
            EmployeesReport::class,
            EmployeeExpenses::class,
            EpensesReport::class,
            PurchasingRepot::class,
            SallingRepot::class,
            VendorsReport::class,
            ChartsExpenses::class,
            DinarExpenses::class,
            ExpensesOverview::class,
            PartnersOverview::class,
        ];
    }
    protected static string $view = 'filament.pages.dashboard';
}