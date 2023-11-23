<?php

namespace App\Filament\Resources\SellingInvoiceResource\Pages;

use App\Filament\Resources\SellingInvoiceResource;
use App\Models\Currencies;
use App\Models\CustomerPayments;
use App\Models\Customers;
use App\Models\SellingInvoice;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListSellingInvoices extends ListRecords
{
    protected static string $resource = SellingInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('listPayments')->label(' پارە وەرگرتنەکان')->url('/selling-invoices/CustomerPayments')->color(Color::Blue)->icon('fas-file-invoice-dollar'),
            Action::make('payment')->hidden(auth()->user()->role == 1)
                ->modalIcon('fas-hand-holding-dollar')
                ->modalIconColor(Color::Red)
                ->color(Color::Red)
                ->icon('fas-hand-holding-dollar')

                ->modalDescription('لەکاتی ئەنجامدانی ئەم کردارە، بڕی پارەی واصل کراو ناتوانرێت لە قەرزەکان زیاتر بێت، لەدوای واصل کردنی بڕی پارەکە، بەپێی پسولەکان بڕی پارەکە لە کۆنترین پسولەکان واصل دەکرێت بۆ نوێترین.')
                ->modalButton(' وەرگرتن')
                ->label('پارە وەرگرتن')->form(function (Form $form): Form {
                    return $form->schema([
                        Select::make('customers_id')->label('فرۆشیار')->options(
                            Customers::all()->pluck('name', 'id')
                        )->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($get('priceType') == '$') {
                                    $set('notPayment', number_format(SellingInvoice::where('customers_id', $state)
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall'), 2));
                                    $set('afterDiscount', number_format(SellingInvoice::where('customers_id', $state)
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall'), 2));
                                } else {
                                    $set('notPayment', number_format(SellingInvoice::where('customers_id', $state)
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall'), 0));
                                    $set('afterDiscount', number_format(SellingInvoice::where('customers_id', $state)
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall'), 0));
                                }
                                $set('amount', 0);
                                $set('discount', 0);
                            }),
                        Select::make('priceType')
                            ->options([
                                '$' => '$',
                                'د.ع' => 'د.ع'
                            ])->default('$')
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state == '$') {
                                    $set('notPayment', number_format(SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall'), 2));
                                    $set('afterDiscount', number_format(SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall'), 2));
                                } else {
                                    $set('notPayment', number_format(SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall'), 0));
                                    $set('afterDiscount', number_format(SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall'), 0));
                                }
                                $set('discount', 0);
                                $set('amount', 0);

                            })
                            ->live()
                            ->label('جۆری دراو'),
                        TextInput::make('notPayment')->label('بڕی قەرز')
                            ->disabled(),
                        TextInput::make('discount')->label('داشکان')
                            ->numeric()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                if ($get('priceType') == '$') {
                                    $set(
                                        'afterDiscount',
                                        number_format(
                                            SellingInvoice::where('customers_id', $get('customers_id'))
                                                ->selectRaw('SUM(amount - paymented) as totall')
                                                ->value('totall') - $state,
                                            2
                                        )
                                    );
                                } else {
                                    $set('afterDiscount', number_format(SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall') - $state, 0));
                                }
                                $set('amount', 0);

                            })
                            ->default(0)
                            ->hint(fn($state) => number_format($state))
                            ->live(onBlur: true)
                            ->required()
                            ->suffix(fn(Get $get) => $get('priceType')),
                        TextInput::make('afterDiscount')->label('دوای داشکان')
                            ->disabled()
                            ->suffix(fn(Get $get) => $get('priceType')),
                        TextInput::make('amount')->label('بڕی واصل کردن')
                            ->numeric()
                            ->live(onBlur: true)
                            ->hint(fn($state) => number_format($state))
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                if ($get('priceType') == '$') {
                                    $set(
                                        'total',
                                        number_format(
                                            SellingInvoice::where('customers_id', $get('customers_id'))
                                                ->selectRaw('SUM(amount - paymented) as totall')
                                                ->value('totall') - $state - $get('discount'),
                                            2
                                        )
                                    );
                                } else {
                                    $set('total', number_format(SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall') - $state - $get('discount'), 0));
                                }
                            })
                            ->required()
                            ->maxValue(function (Get $get) {
                                if ($get('priceType') == '$') {
                                    return SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM(amount - paymented) as totall')
                                        ->value('totall') - $get('discount');
                                } else {
                                    return SellingInvoice::where('customers_id', $get('customers_id'))
                                        ->selectRaw('SUM((amount - paymented) * dolarPrice) as totall')
                                        ->value('totall') - $get('discount');
                                }
                            })->suffix(fn(Get $get) => $get('priceType')),

                        TextInput::make('total')->label('ماوە')
                            ->disabled()
                            ->required()
                            ->suffix(fn(Get $get) => $get('priceType')),
                        TextInput::make('note')->label('تێبینی')->required()->columnSpanFull()
                    ])->columns(2);
                })
                ->action(function (array $data) {
                    $payments = SellingInvoice::orderBy('id', 'asc')->where('customers_id', $data['customers_id'])->whereRaw('(amount - paymented) > 0')->get();
                    if ($data['priceType'] == '$') {
                        $amount = $data['amount'] + $data['discount'];

                    } else {
                        $amount = $data['amount'] / Currencies::find(1)->dinarPrice;
                        $data['amount'] = $amount;
                        $amount = $data['amount'] + $data['discount'];
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
                    CustomerPayments::create([
                        'customers_id' => $data['customers_id'],
                        'note' => $data['note'],
                        'amount' => $data['amount'],
                        'dolarPrice' => Currencies::find(1)->dinarPrice,
                        'priceType' => $data['priceType'],
                        'user_name' => auth()->user()->name,
                        'discount' => $data['discount']
                    ]);
                })
        ];
    }
}