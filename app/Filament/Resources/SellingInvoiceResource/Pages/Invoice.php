<?php

namespace App\Filament\Resources\SellingInvoiceResource\Pages;

use App\Filament\Resources\SellingInvoiceResource;
use App\Models\SellingInvoice;
use App\Models\SellingInvoiceProducts;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = SellingInvoiceResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'پسولە';
    public $data, $invoiceProducts, $customerDebt;
    public function mount(): void
    {
        $id = request('record');
        $this->data = SellingInvoice::join('customers', 'customers.id', 'selling_invoices.customers_id')
            ->Select('customers.name', 'customers.phone', 'selling_invoices.id', 'selling_invoices.created_at', 'selling_invoices.paymented', 'selling_invoices.priceType', 'selling_invoices.dolarPrice')
            ->where('selling_invoices.id', $id)
            ->get()->first();
        $this->invoiceProducts = SellingInvoiceProducts::
            join('selling_products', 'selling_products.id', 'selling_invoice_products.selling_products_id')
            ->where('selling_invoice_products.selling_invoices_id', $id)
            ->select('selling_invoice_products.sallingPrice', 'selling_invoice_products.qty', 'selling_products.name', 'selling_products.code', 'selling_products.unit', 'selling_products.colorCofe')
            ->get();
        $this->customerDebt = SellingInvoice::join('customers', 'customers.id', '=', 'selling_invoices.customers_id')
            ->where('selling_invoices.id', '!=', $id)
            ->selectRaw('SUM(selling_invoices.amount - selling_invoices.paymented) as total_debt')
            ->value('total_debt');
        if (!$this->data) {
            abort(404);
        }

    }
    protected static string $view = 'filament.resources.selling-invoice-resource.pages.invoice';
}