<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PurchasingInvoiceResource\Pages\VendorPayments;
use App\Models\Currencies;
use App\Models\CustomerPayments;
use App\Models\Expenses;
use App\Models\PurchasingInvoice;
use App\Models\Reciepts;
use App\Models\SellingInvoice;
use App\Models\VendorPayments as ModelsVendorPayments;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class CompanyBalance extends Page
{
    protected static ?string $navigationIcon = 'fas-circle-dollar-to-slot';
    protected static ?string $title = 'قاصە';
    protected static ?int $navigationSort = 50;
    public $data = [];
    public function mount()
    {
        $this->data['expenses'] = Expenses::groupBy('priceType')->select('priceType', DB::raw('sum(amount) as total'))->get();
        $this->data['sendedMoney'] = ModelsVendorPayments::where('priceType', '$')->sum('amount');
        $this->data['recivedMoney'] = CustomerPayments::where('priceType', '$')->sum('amount');
        $this->data['sendedMoneyDinar'] = ModelsVendorPayments::where('priceType', '!=', '$')->selectRaw(DB::raw('sum(amount *  dolarPrice) as vendorDebts'))->value('vendorDebts');
        $this->data['recivedMoneyDinar'] = CustomerPayments::where('priceType', '!=', '$')->selectRaw(DB::raw('sum(amount *  dolarPrice) as vendorDebts'))->value('vendorDebts');
        $this->data['dollarPrice'] = Currencies::find(1)->dinarPrice;
        $this->data['partnersBalance'] = Reciepts::groupBy('priceType', 'partnersName')
            ->select(
                'partnersName',
                'priceType',
                DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as balance')
            )->select('partnersName', 'priceType', DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as balance'))->get();
    }
    protected static string $view = 'filament.pages.company-balance';
}