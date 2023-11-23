<?php

namespace App\Filament\Resources\PurchasingInvoiceResource\Pages;

use App\Filament\Resources\PurchasingInvoiceResource;
use App\Models\Currencies;
use App\Models\PurchasingInvoice;
use App\Models\PurchasingInvoiceProducts;
use App\Models\VendorPayments;
use Carbon\Carbon;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditPurchasingInvoice extends EditRecord
{
    protected static string $resource = PurchasingInvoiceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterSave(): void
    {
        $total = PurchasingInvoiceProducts::where('purchasing_invoices_id', $this->data['id'])->select(DB::raw('qty * purchase_price as total_price'))->get()->sum('total_price');
        PurchasingInvoice::where('id', $this->data['id'])->update([
            'amount' => $total
        ]);
        VendorPayments::where('purchasing_invoices_id', $this->data['id'])->delete();
        if ($this->data['paymented'] > 0) {
            VendorPayments::create([
                'vendors_id' => $this->data['vendors_id'],
                'amount' => $this->data['paymented'],
                'purchasing_invoices_id' => $this->data['id'],
                'note' => 'إجمالي المبلغ المستلم($ ' . number_format($this->data['amount'], 2) . ' )' . ' عدد وصل (' . $this->data['invoice_id'] . ')',
                'created_at' => Carbon::parse($this->data['created_at'])->addHours(3),
                'dolarPrice' => Currencies::find(1)->dinarPrice,
                'priceType' => $this->data['priceType'],
                'user_name' => auth()->user()->name
            ]);
        }

    }
}
