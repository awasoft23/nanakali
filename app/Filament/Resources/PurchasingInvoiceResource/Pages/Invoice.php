<?php

namespace App\Filament\Resources\PurchasingInvoiceResource\Pages;

use App\Filament\Resources\PurchasingInvoiceResource;
use App\Models\PurchasingInvoice;
use App\Models\VendorPayments;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = PurchasingInvoiceResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'پسولە';
    public $data, $invoiceProducts, $customerDebt;
    public function mount(): void
    {
        $id = request('record');
        $this->data = VendorPayments::join('vendors', 'vendors.id', 'vendor_payments.vendors_id')
            ->Select(
                'vendors.name',
                'vendors.phone',
                'vendor_payments.id',
                'vendor_payments.created_at',
                'vendor_payments.amount',
                'vendor_payments.priceType',
                'vendor_payments.note',
                'vendor_payments.user_name',
                'vendor_payments.dolarPrice',
                'vendors.id as customer_id'
            )
            ->where('vendor_payments.id', $id)
            ->get()->first();
        if (!$this->data) {
            abort(404);
        }
        $this->customerDebt = PurchasingInvoice::join('vendors', 'vendors.id', '=', 'purchasing_invoices.vendors_id')
            ->selectRaw('SUM(purchasing_invoices.amount - purchasing_invoices.paymented) as total_debt')
            ->where('purchasing_invoices.created_at', '<=', $this->data->created_at)
            ->value('total_debt');
    }
    protected static string $view = 'filament.resources.purchasing-invoice-resource.pages.invoice';
}