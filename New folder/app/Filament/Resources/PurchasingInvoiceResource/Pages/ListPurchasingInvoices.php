<?php

namespace App\Filament\Resources\PurchasingInvoiceResource\Pages;

use App\Filament\Resources\PurchasingInvoiceResource;
use App\Models\Currencies;
use App\Models\PurchasingInvoice;
use App\Models\VendorPayments;
use App\Models\Vendors;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\DB;

class ListPurchasingInvoices extends ListRecords
{
    protected static string $resource = PurchasingInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('listPayments')->label('پارەدانەکان')->url('/purchasing-invoices/vendorPayments')->color(Color::Blue)->icon('fas-file-invoice-dollar'),
            Action::make('payment')
                ->hidden(auth()->user()->role == 1)
                ->modalIcon('fas-hand-holding-dollar')
                ->modalIconColor(Color::Red)
                ->color(Color::Red)
                ->icon('fas-hand-holding-dollar')
                ->requiresConfirmation()
                ->modalDescription('لەکاتی ئەنجامدانی ئەم کردارە، بڕی پارەی واصل کراو ناتوانرێت لە قەرزەکان زیاتر بێت، لەدوای واصل کردنی بڕی پارەکە، بەپێی پسولەکان بڕی پارەکە لە کۆنترین پسولەکان واصل دەکرێت بۆ نوێترین.')
                ->modalButton('پارەدان')
                ->label('پارەدان')->form(function (Form $form): Form {
                    return $form->schema([
                        Select::make('vendor_id')->label('فرۆشیار')->options(
                            Vendors::all()->pluck('name', 'id')
                        )->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $set('notPayment', PurchasingInvoice::where('vendors_id', $state)
                                    ->selectRaw('SUM(amount - paymented) as totall')
                                    ->value('totall'));
                            }),
                        Select::make('priceType')
                            ->options([
                                '$' => '$',
                                'د.ع' => 'د.ع'
                            ])->default('$')
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state == '$') {
                                    $set('notPayment', number_format(PurchasingInvoice::where('vendors_id', $get('vendor_id'))
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall'), 2));
                                } else {
                                    $set('notPayment', number_format(PurchasingInvoice::where('vendors_id', $get('vendor_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall'), 0));
                                }
                            })
                            ->live()
                            ->label('جۆری دراو'),
                        TextInput::make('notPayment')->label('بڕی قەرز')
                            ->required()->disabled(),
                        TextInput::make('amount')->label('بڕی پارە')
                            ->numeric()
                            ->required()
                            ->maxValue(function (Get $get) {
                                if ($get('priceType') == '$') {
                                    return PurchasingInvoice::where('vendors_id', $get('vendor_id'))
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall');
                                } else {
                                    return PurchasingInvoice::where('vendors_id', $get('vendor_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall');
                                }
                            })->suffix(fn(Get $get) => $get('priceType')),
                        TextInput::make('note')->label('تێبینی')->required()
                    ])->columns(1);
                })
                ->action(function (array $data) {
                    $payments = PurchasingInvoice::orderBy('id', 'asc')->where('vendors_id', $data['vendor_id'])->whereRaw('(amount - paymented) > 0')->get();
                    if ($data['priceType'] == '$') {
                        $amount = $data['amount'];

                    } else {
                        $amount = $data['amount'] / Currencies::find(1)->dinarPrice;
                        $data['amount'] = $amount;
                    }
                    foreach ($payments as $payment) {
                        $total = $payment->amount - $payment->paymented;
                        $total = min($total, $amount);
                        $payment->paymented = $payment->paymented + $total;
                        $amount -= $total;
                        $payment->save();

                        if ($amount <= 0) {
                            break;
                        }
                    }
                    VendorPayments::create([
                        'vendors_id' => $data['vendor_id'],
                        'note' => $data['note'],
                        'amount' => $data['amount'],
                        'dolarPrice' => Currencies::find(1)->dinarPrice,
                        'priceType' => $data['priceType'],
                        'user_name' => auth()->user()->name
                    ]);
                })
        ];
    }
}