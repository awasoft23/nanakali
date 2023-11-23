<?php

namespace App\Filament\Resources\CustomersResource\Pages;

use App\Filament\Resources\CustomersResource;
use App\Models\Customers;
use App\Models\ReturnProducts;
use App\Models\SellingInvoice;
use Filament\Resources\Pages\Page;

class PrintA extends Page
{
    protected static string $resource = CustomersResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'کشف حساب';
    public $data;
    public function mount(): void
    {
        $id = request('id');
        $from = request('from');
        $to = request('to');
        $oldsales = SellingInvoice::where('customers_id', $id)
        ->whereDate('created_at', '<=', $from)
        ->select('amount as amount', 'created_at as created', \DB::raw('1 as type')) // Type 1 for sales
        ->get()
        ->toArray();
    $oldpayments = SellingInvoice::where('customers_id', $id)
        ->whereDate('updated_at', '<=', $from)
        ->select(\DB::raw('(-1*paymented) as amount'), 'updated_at as created', \DB::raw('2 as type')) // Type 2 for payments
        ->get()
        ->toArray();
    $odlreturned = ReturnProducts::where('customers_id', $id)
        ->whereDate('created_at', '<=', $from)
        ->select(\DB::raw('(-1*qty*sallingPrice) as amount'), 'created_at as created', \DB::raw('3 as type')) // Type 2 for payments
        ->get()
        ->toArray();
        $merged1 = array_merge($oldsales, $oldpayments, $odlreturned);
        // Now you can use a custom sorting function to sort the merged array by the 'created' field in ascending order
        usort($merged1, function ($a, $b) {
            return strtotime($a['created']) - strtotime($b['created']);
        });
        // If you still need a collection, you can convert the sorted array back to a collection
        $sorted1 = collect($merged1);
        $odlBalance = $sorted1->sum('amount');


        $sales = SellingInvoice::where('customers_id', $id)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select('amount as amount', 'created_at as created', \DB::raw('1 as type')) // Type 1 for sales
            ->get()
            ->toArray();
        $payments = SellingInvoice::where('customers_id', $id)
            ->whereDate('updated_at', '>=', $from)
            ->whereDate('updated_at', '<=', $to)
            ->select(\DB::raw('(-1*paymented) as amount'), 'updated_at as created', \DB::raw('2 as type')) // Type 2 for payments
            ->get()
            ->toArray();
        $returned = ReturnProducts::where('customers_id', $id)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->select(\DB::raw('(-1*qty*sallingPrice) as amount'), 'created_at as created', \DB::raw('3 as type')) // Type 2 for payments
            ->get()
            ->toArray();
            $merged = array_merge($sales, $payments, $returned);
            // Now you can use a custom sorting function to sort the merged array by the 'created' field in ascending order
            usort($merged, function ($a, $b) {
                return strtotime($a['created']) - strtotime($b['created']);
            });
            $customer = Customers::findOrFail($id);
            // If you still need a collection, you can convert the sorted array back to a collection
            $sorted = collect($merged);


            $this->data['from'] = $from;
            $this->data['to']=$to;
            $this->data['old']=$odlBalance;
            $this->data['customer']=$customer;
            $this->data['sorted'] = $sorted;
        // If you still need a collection, you can convert the sorted array back to a collection

    }
    protected static string $view = 'filament.resources.customers-resource.pages.print';
}
