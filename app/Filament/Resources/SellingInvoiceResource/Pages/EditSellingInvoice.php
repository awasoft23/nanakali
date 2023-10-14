<?php

namespace App\Filament\Resources\SellingInvoiceResource\Pages;

use App\Filament\Resources\SellingInvoiceResource;
use App\Models\CustomerPayments;
use App\Models\SellingInvoice;
use App\Models\SellingInvoiceProducts;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditSellingInvoice extends EditRecord
{
    protected static string $resource = SellingInvoiceResource::class;
    protected function afterFill(): void
    {
        if ($this->data['priceType'] !== '$') {
            $this->data['paymented'] = $this->data['paymented'] * $this->data['dolarPrice'];
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterSave(): void
    {
        $total = SellingInvoiceProducts::where('selling_invoices_id', $this->data['id'])->select(DB::raw('qty * sallingPrice as total_price'))->get()->sum('total_price');

        SellingInvoice::where('id', $this->data['id'])->update([
            'amount' => $total
        ]);
        CustomerPayments::where('selling_invoices_id', $this->data['id'])->delete();
        if ($this->data['paymented'] > 0) {
            if ($this->data['priceType'] != '$') {
                $this->data['paymented'] = $this->data['paymented'] / $this->data['dolarPrice'];
                SellingInvoice::where('id', $this->data['id'])->update([
                    'paymented' => $this->data['paymented']
                ]);
            }
            CustomerPayments::create([
                'dolarPrice' => $this->record['dolarPrice'],
                'customers_id' => $this->data['customers_id'],
                'amount' => $this->data['paymented'],
                'selling_invoices_id' => $this->data['id'],
                'note' => 'بڕی پارەی وەرگیراو  لە کۆی گشتی ($ ' . number_format($total, 2) . ' )' . ' ژمارەی پسولەی (' . $this->data['id'] . ')',
                'created_at' => Carbon::parse($this->data['created_at'])->addHours(3),
                'priceType' => $this->data['priceType']
            ]);
        }

    }
}