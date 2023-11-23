<?php

namespace App\Filament\Resources\PurchasingInvoiceResource\Pages;

use App\Filament\Resources\PurchasingInvoiceResource;
use App\Models\Currencies;
use App\Models\PurchasingInvoice;
use App\Models\VendorPayments as ModelsVendorPayments;
use App\Models\Vendors;
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
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class VendorPayments extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;
    protected static string $resource = PurchasingInvoiceResource::class;
    protected static ?string $title = 'قائمة المدفوعات';
    protected ?string $heading = 'قائمة المدفوعات';
    protected static ?string $navigationLabel = 'قائمة المدفوعات';

    protected function getHeaderActions(): array
    {
        return [
            ActionsAction::make('payment')

                ->hidden(auth()->user()->role == 1)
                ->modalIcon('fas-hand-holding-dollar')
                ->modalIconColor(Color::Red)
                ->color(Color::Red)
                ->icon('fas-hand-holding-dollar')
                ->requiresConfirmation()
                ->modalDescription('"عند القيام بذلك لا يمكن أن يتجاوز المبلغ المالي المستلم القروض، وبعد استلام المبلغ المالي يتم تحويل المبلغ المالي من أقدم الوصلات إلى الأحدث حسب الوصلات."')
                ->modalButton('دفع')
                ->label('دفع')->form(function (Form $form): Form {
                    return $form->schema([
                        Select::make('vendor_id')->label('بائع')->options(
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
                            ->label('نوع العملة'),
                        TextInput::make('notPayment')->label('مبلغ القرض')
                            ->required()->disabled(),
                        TextInput::make('amount')->label('مبلغ من المال')
                            ->numeric(2)
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
                        TextInput::make('note')->label('الملاحظة')->required()
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
    public function table(Table $table): Table
    {
        return $table
            ->modelLabel('الوصلات')
            ->pluralModelLabel('الوصلات')
            ->query(ModelsVendorPayments::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('Vendors.name')->label('بائع')->searchable(),
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
                TextColumn::make('dolarPrice')->label('سعر الدولار')->numeric(2)->searchable(),
                TextColumn::make('note')->label('الملاحظة')->searchable(),
                TextColumn::make('created_at')->label('الوقت و التاريخ')->dateTime('d/m/Y H:i:s'),
            ])
            ->actions([
                Action::make('print')->hidden(auth()->user()->role == 1)->label('الطباعة')->icon('fas-print')->url(fn($record) => '/purchasing-invoices/printPayment/' . $record->id)->openUrlInNewTab()
            ])
            ->filters([
                SelectFilter::make('vendors_id')->label('بائع')->options(
                    Vendors::all()->pluck('name', 'id')
                )->searchable(),
                DateRangeFilter::make('created_at')->label('تاریخ')
            ]);
    }

    protected static string $view = 'filament.resources.purchasing-invoice-resource.pages.vendor-payments';
}
