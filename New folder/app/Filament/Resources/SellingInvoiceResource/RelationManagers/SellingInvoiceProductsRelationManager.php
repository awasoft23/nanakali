<?php

namespace App\Filament\Resources\SellingInvoiceResource\RelationManagers;

use App\Models\SellingInvoice;
use App\Models\SellingProducts;
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

class SellingInvoiceProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'SellingInvoiceProducts';
    protected static ?string $modelLabel = 'کاڵا';
    protected static ?string $title = 'کاڵاکان';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('selling_products_id')
                    ->placeholder('کاڵا')
                    ->label('کاڵا')
                    ->required()
                    ->relationship('SellingProducts', 'id')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->placeholder('ناوی کاڵا')
                            ->label('ناوی کاڵا')
                            ->suffixIcon('fas-box-archive')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->placeholder('کۆدی کاڵا')
                            ->label('کۆدی کاڵا')
                            ->required()
                            ->suffixIcon('fas-barcode')
                            ->maxLength(255),
                        TextInput::make('unit')
                            ->placeholder('یەکە')
                            ->label('یەکە')
                            ->suffixIcon('fas-notes-medical')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('salePrice')
                            ->placeholder('نرخی فرۆشتن')
                            ->label('نرخی فرۆشتن')
                            ->required()
                            ->suffix('$')
                            ->maxLength(255),
                    ])
                    ->options(SellingProducts::select('id', DB::raw('CONCAT("جۆری مەڕمەڕ: ", name , " - جۆری قالب: ", code," - کۆدی ڕەنگ: ", colorCofe, " - نرخ: ",salePrice, "$") as productName'))
                        ->pluck('productName', 'id'))
                    ->live()
                    ->afterStateUpdated(fn($state, Set $set) => SellingProducts::find($state) ? $set('sallingPrice', SellingProducts::find($state)->salePrice) : $set('sallingPrice', 0))
                    ->searchable()->columnSpanFull(),
                TextInput::make('qty')
                    ->required()
                    ->placeholder(fn(Get $get) => SellingProducts::find($get('selling_invoices_id')) ? SellingProducts::find($get('selling_invoices_id'))->unit : 'دانە')
                    ->label(fn(Get $get) => SellingProducts::find($get('selling_invoices_id')) ? SellingProducts::find($get('selling_invoices_id'))->unit : 'دانە')
                    ->suffix(fn(Get $get) => SellingProducts::find($get('selling_invoices_id')) ? SellingProducts::find($get('selling_invoices_id'))->unit : 'دانە')
                    ->numeric(),
                TextInput::make('sallingPrice')
                    ->placeholder('نرخ')
                    ->label('نرخ')
                    ->required()
                    ->suffix('$')
                    ->numeric()

            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('SellingProducts.name')
                    ->label('ناوی کاڵا')
                    ->searchable(),
                TextColumn::make('SellingProducts.code')
                    ->label('کۆدی کاڵا'),
                TextColumn::make('sallingPrice')
                    ->searchable()
                    ->suffix(' $ ')
                    ->label('نرخ'),
                TextColumn::make('qty')
                    ->searchable()
                    ->label('بڕ'),
                TextColumn::make('total')
                    ->label('کۆی گشتی')
                    ->formatStateUsing(fn($record) => '$ ' . number_format($record->qty * $record->sallingPrice, 2))
                    ->summarize(
                        [
                            Summarizer::make()->label(' کۆی گشتی دۆلاری ئەمریکی')->using(
                                function (Builder $query) {
                                    return $query->sum(DB::raw('(qty * sallingPrice)'));
                                }
                            )->numeric(2),
                            Summarizer::make()->label('کۆی گشتی بە دیناری عێراقی')->using(
                                function (Builder $query) {
                                    return $query->sum(DB::raw('(qty * sallingPrice)')) * $this->ownerRecord['dolarPrice'];
                                }
                            )->numeric(0)
                        ]
                    )
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