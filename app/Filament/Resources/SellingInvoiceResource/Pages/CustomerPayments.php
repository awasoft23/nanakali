<?php

namespace App\Filament\Resources\SellingInvoiceResource\Pages;

use App\Filament\Resources\SellingInvoiceResource;
use App\Models\Currencies;
use App\Models\CustomerPayments as ModelsCustomerPayments;
use App\Models\Customers;
use App\Models\SellingInvoice;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class CustomerPayments extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;
    protected static ?string $title = 'الوصلات';

    protected static string $resource = SellingInvoiceResource::class;
    protected function getHeaderActions(): array
    {
        return [
            ActionsAction::make('payment')
                ->hidden(auth()->user()->role == 1)
                ->modalIcon('fas-hand-holding-dollar')
                ->modalIconColor(Color::Red)
                ->color(Color::Red)
                ->icon('fas-hand-holding-dollar')

                ->modalDescription('"عند القيام بذلك لا يمكن أن يتجاوز المبلغ المالي المستلم القروض، وبعد استلام المبلغ المالي يتم تحويل المبلغ المالي من أقدم الوصلات إلى الأحدث حسب الوصلات."')
                ->modalButton(' وەرگرتن')
                ->label('تلقي الأموال')->form(function (Form $form): Form {
                    return $form->schema([
                        Select::make('customers_id')->label('بائع')->options(
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
                            ->label('نوع العملة'),
                        TextInput::make('notPayment')->label('مبلغ القرض')
                            ->disabled(),
                        TextInput::make('discount')->label('تخفيض')
                            ->numeric(2)
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
                        TextInput::make('afterDiscount')->label('بعد الخصم')
                            ->disabled()
                            ->suffix(fn(Get $get) => $get('priceType')),
                        TextInput::make('amount')->label('مبلغ التسليم')
                            ->numeric(2)
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

                        TextInput::make('total')->label('دین')
                            ->disabled()
                            ->required()
                            ->suffix(fn(Get $get) => $get('priceType')),
                        TextInput::make('note')->label('الملاحظة')->required()->columnSpanFull()
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
                    ModelsCustomerPayments::create([
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

    public function table(Table $table): Table
    {
        return $table
            ->modelLabel('الوصلات')
            ->pluralModelLabel('الوصلات')
            ->query(ModelsCustomerPayments::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('Customers.name')->label('عميل')->searchable(),
                TextColumn::make('amount')
                    ->suffix(' $ ')
                    ->description(function ($record) {
                        $amount = $record->amount * $record->dolarPrice;
                        $amount1 = round($amount / 250);
                        $amount = $amount1 * 250;
                        return $record->priceType != '$' ? number_format($amount, 0) . ' د.ع ' : '';
                    })
                    ->numeric(2)
                    ->label('مبلغ من المال')
                    ->searchable()
                    ->summarize([
                        Summarizer::make()->label('الدولار الأمريكي')->using(function (Builder $query) {
                            return $query->where('priceType', '$')->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('الدينار العراقي')->using(function (Builder $query) {
                            return round($query->where('priceType', '!=', '$')->sum(DB::raw('amount * dolarPrice')) / 250) * 250;
                        })->numeric(0),
                        Summarizer::make()->label('الإجمالي بالدولار')->using(function (Builder $query) {
                            return $query->sum(DB::raw('amount'));
                        })->numeric(0),
                    ]),
                TextColumn::make('discount')
                    ->suffix(' $ ')
                    ->description(function ($record) {
                        $amount = $record->discount * $record->dolarPrice;
                        $amount1 = round($amount / 250);
                        $amount = $amount1 * 250;
                        return $record->priceType != '$' ? number_format($amount, 0) . ' د.ع ' : '';
                    })
                    ->numeric(2)
                    ->label('تخفيض')
                    ->searchable()
                    ->summarize([
                        Summarizer::make()->label('الدولار الأمريكي')->using(function (Builder $query) {
                            return $query->where('priceType', '$')->sum('discount');
                        })->numeric(2),
                        Summarizer::make()->label('الدينار العراقي')->using(function (Builder $query) {
                            return round($query->where('priceType', '!=', '$')->sum(DB::raw('discount * dolarPrice')) / 250) * 250;
                        })->numeric(0),
                        Summarizer::make()->label('الإجمالي بالدولار')->using(function (Builder $query) {
                            return $query->sum(DB::raw('discount'));
                        })->numeric(0),
                    ]),
                TextColumn::make('dolarPrice')->label('سعر الدولار')->numeric(2)->searchable(),
                TextColumn::make('note')->label('الملاحظة')->searchable(),
                TextColumn::make('created_at')->label('الوقت و التاريخ')->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([
                SelectFilter::make('customers_id')->label('عميل')->options(
                    Customers::all()->pluck('name', 'id')
                )->searchable(),
                DateRangeFilter::make('created_at')->label('تاریخ')
            ])
            ->actions([
                Action::make('print')->label('الطباعة')->hidden(auth()->user()->role == 1)->url(fn($record) => '/selling-invoices/printPayment/' . $record->id)->icon('fas-print')->openUrlInNewTab()
            ]);
    }


    protected static string $view = 'filament.resources.selling-invoice-resource.pages.customer-payments';
}
