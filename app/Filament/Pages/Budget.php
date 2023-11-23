<?php

namespace App\Filament\Pages;

use App\Models\Avaliables;
use App\Models\Currencies;
use App\Models\CustomerPayments;
use App\Models\Expenses;
use App\Models\PurchaseProducts;
use App\Models\PurchasingInvoice;
use App\Models\Reciepts;
use App\Models\SellingInvoice;
use App\Models\VendorPayments;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Budget extends Page
{
    protected static ?string $navigationIcon = 'fas-scale-balanced';
    protected static ?string $title = 'میزانییة';
    protected static ?string $navigationGroup = 'تقاریر';
    protected static ?int $navigationSort = 50;

    public $data = [];
    public function mount()
    {
        $this->data['sendedMoney'] = VendorPayments::sum('amount');
        $this->data['recivedMoney'] = CustomerPayments::sum('amount');
        $this->data['VendorDebts'] = PurchasingInvoice::selectRaw(DB::raw('sum(amount - paymented) as vendorDebts'))->value('vendorDebts');
        $this->data['CustomerDebts'] = SellingInvoice::selectRaw(DB::raw('sum(amount - paymented) as vendorDebts'))->value('vendorDebts');
        $this->data['expenses'] = Expenses::groupBy('priceType')->select('priceType', DB::raw('sum(amount) as total'))->get();
        $this->data['dollarPrice'] = Currencies::find(1)->dinarPrice;
        $this->data['partnersBalance'] = Reciepts::groupBy('priceType', 'partnersName')
            ->select(
                'partnersName',
                'priceType',
                DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as balance')
            )->select('partnersName', 'priceType', DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as balance'))->get();
        $products = PurchaseProducts::join('purchasing_invoice_products', 'purchasing_invoice_products.purchase_products_id', 'purchase_products.id')
            ->selectRaw(DB::raw('sum((purchasing_invoice_products.qty) * purchasing_invoice_products.purchase_price) as totalProducts'))
            ->value('totalProducts');
        $usedProducts = PurchaseProducts::join('used_products', 'used_products.purchase_products_id', 'purchase_products.id')
            ->selectRaw(DB::raw('sum((used_products.qty) * purchase_products.purchasePricw) as totalProducts'))
            ->value('totalProducts');
        $this->data['productsBalance'] = $products - $usedProducts;
        $this->data['VendorDebts'] = $this->data['VendorDebts'] ? $this->data['VendorDebts'] : 0;
        $this->data['CustomerDebts'] = $this->data['CustomerDebts'] ? $this->data['CustomerDebts'] : 0;
        $this->data['sendedMoney'] = $this->data['sendedMoney'] ? $this->data['sendedMoney'] : 0;
        $this->data['recivedMoney'] = $this->data['recivedMoney'] ? $this->data['recivedMoney'] : 0;
        $this->data['avaliable'] = ((Avaliables::where('type',1)->sum('dollaramount') + (Avaliables::where('type',1)->sum('dinnaramount') /  Currencies::find(1)->dinarPrice)) - (Avaliables::where('type',2)->sum('dollaramount') + (Avaliables::where('type',2)->sum('dinnaramount') /  Currencies::find(1)->dinarPrice)));

    }
    protected static string $view = 'filament.pages.budget';
}
