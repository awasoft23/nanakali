<?php

namespace App\Filament\Resources\SellingInvoiceResource\Pages;

use App\Filament\Resources\SellingInvoiceResource;
use App\Models\CustomerPayments;
use App\Models\SellingInvoice;
use Filament\Resources\Pages\Page;

class PaymentInvoice extends Page
{
    protected static string $resource = SellingInvoiceResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'وصل';
    public $data, $invoiceProducts, $customerDebt;
    public function mount(): void
    {
        $id = request('record');
        $this->data = CustomerPayments::join('customers', 'customers.id', 'customer_payments.customers_id')
            ->Select('customers.name', 'customers.phone', 'customer_payments.id', 'customer_payments.created_at', 'customer_payments.amount', 'customer_payments.priceType', 'customer_payments.note', 'customer_payments.discount', 'customer_payments.user_name', 'customer_payments.dolarPrice', 'customers.id as customer_id')
            ->where('customer_payments.id', $id)
            ->get()->first();
        if (!$this->data) {
            abort(404);
        }
        $this->customerDebt = SellingInvoice::join('customers', 'customers.id', '=', 'selling_invoices.customers_id')
            ->selectRaw('SUM(selling_invoices.amount - selling_invoices.paymented) as total_debt')
            ->where('selling_invoices.created_at', '<=', $this->data->created_at)
            ->value('total_debt');
    }
    protected static string $view = 'filament.resources.selling-invoice-resource.pages.payment-invoice';
}
