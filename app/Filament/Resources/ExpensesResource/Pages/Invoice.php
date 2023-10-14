<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use App\Models\Expenses;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = ExpensesResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'پسولە';
    public $data, $invoiceProducts, $customerDebt;
    public function mount(): void
    {
        $id = request('record');
        $this->data = Expenses::join('expenses_types', 'expenses_types.id', 'expenses.expenses_type_id')->where('expenses.id', $id)->get()->first();
        if (!$this->data) {
            abort(404);
        }

    }
    protected static string $view = 'filament.resources.expenses-resource.pages.invoice';
}