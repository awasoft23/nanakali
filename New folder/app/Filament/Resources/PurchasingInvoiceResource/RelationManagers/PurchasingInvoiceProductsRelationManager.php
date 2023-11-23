<?php

namespace App\Filament\Resources\PurchasingInvoiceResource\RelationManagers;

use App\Models\PurchaseProducts;
use App\Models\PurchasingInvoiceProducts;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PurchasingInvoiceProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'PurchasingInvoiceProducts';
    protected static ?string $modelLabel = 'کاڵا';
    protected static ?string $title = 'کاڵاکان';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('purchase_products_id')
                    ->relationship('PurchaseProducts', 'code')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('ناو')
                            ->placeholder('ناو')
                            ->suffixIcon('fas-box-archive')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('کۆدی کاڵا')
                            ->placeholder('کۆدی کاڵا')
                            ->suffixIcon('fas-barcode')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unit')
                            ->label('یەکە')
                            ->placeholder('یەکە')
                            ->suffixIcon('fas-notes-medical')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purchasePricw')
                            ->label('نرخی کڕین')
                            ->placeholder('نرخی کڕین')
                            ->suffix('$')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->label('کاڵا')
                    ->searchable()
                    ->live()
                    ->required()
                    ->options(
                        PurchaseProducts::select('id', DB::raw('CONCAT("ناو: ", name , " - کۆد: ", code, " - نرخ: ",purchasePricw, "$") as productName'))->pluck('productName', 'id')
                    )
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('purchase_price', PurchaseProducts::find($state) ? PurchaseProducts::find($state)->purchasePricw : 0);
                    })
                    ->columnSpanFull(),
                TextInput::make('qty')
                    ->required()
                    ->disabled(fn(Get $get) => !PurchaseProducts::find($get('purchase_products_id')))
                    ->label(fn(Get $get) => PurchaseProducts::find($get('purchase_products_id')) ? PurchaseProducts::find($get('purchase_products_id'))->unit : 'یەکە')
                    ->suffix(fn(Get $get) => PurchaseProducts::find($get('purchase_products_id')) ? PurchaseProducts::find($get('purchase_products_id'))->unit : null),
                TextInput::make('purchase_price')
                    ->required()
                    ->disabled(fn(Get $get) => !PurchaseProducts::find($get('purchase_products_id')))
                    ->numeric()
                    ->label('نرخ')
                    ->suffix('$'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('PurchaseProducts.code')->label('کۆدی کاڵا')->searchable(),
                TextColumn::make('PurchaseProducts.name')->label('ناوی کاڵا')->searchable(),
                TextColumn::make('purchase_price')->label('نرخی کڕین')->prefix(' $ ')->numeric(2),
                TextColumn::make('qty')->label('ژمارە')
                    ->formatStateUsing(fn($state, PurchasingInvoiceProducts $record) => number_format($state, 0) . ' - ' . PurchaseProducts::find($record->purchase_products_id)->unit),
                TextColumn::make('total')
                    ->label('کۆی گشتی')
                    ->formatStateUsing(fn(PurchasingInvoiceProducts $record) => '$ ' . number_format($record->qty * $record->purchase_price, 2))
                    ->summarize(
                        Summarizer::make()->label('کۆی گشتی')->using(
                            function (Builder $query) {
                                return $query->sum(DB::raw('(qty * purchase_price)'));
                            }
                        )->numeric(2)
                    )
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}