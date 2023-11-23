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
    protected static ?string $modelLabel = 'المواد';
    protected static ?string $title = 'المواد';

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('selling_products_id')
                    ->placeholder('المواد')
                    ->label('المواد')
                    ->required()
                    ->relationship('SellingProducts', 'id')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->placeholder('اسم المواد')
                            ->label('اسم المواد')
                            ->suffixIcon('fas-box-archive')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->placeholder('كود المواد')
                            ->label('كود المواد')
                            ->required()
                            ->suffixIcon('fas-barcode')
                            ->maxLength(255),
                        TextInput::make('unit')
                            ->placeholder('متر')
                            ->label('متر')
                            ->suffixIcon('fas-notes-medical')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('salePrice')
                            ->placeholder('سعر البيع')
                            ->label('سعر البيع')
                            ->required()
                            ->suffix('$')
                            ->maxLength(255),
                    ])
                    ->options(SellingProducts::select('id', DB::raw('CONCAT("نوع المواد: ", name , " - نوع القالب: ", code, " - سعر: ",salePrice, "$") as productName'))
                        ->pluck('productName', 'id'))
                    ->live()
                    ->afterStateUpdated(fn($state, Set $set) => SellingProducts::find($state) ? $set('sallingPrice', SellingProducts::find($state)->salePrice) : $set('sallingPrice', 0))
                    ->searchable()->columnSpanFull(),
                Select::make('colorCode')
                ->label('اللون')
                ->options(function(){
                    $dd=[];
                    for($i=1;$i<=100;$i++){
                        array_push($dd,$i);
                    }
                    return $dd;
                })->searchable(),
                TextInput::make('qty')
                    ->required()
                    ->placeholder(fn(Get $get) => SellingProducts::find($get('selling_invoices_id')) ? SellingProducts::find($get('selling_invoices_id'))->unit : 'عدد')
                    ->label(fn(Get $get) => SellingProducts::find($get('selling_invoices_id')) ? SellingProducts::find($get('selling_invoices_id'))->unit : 'عدد')
                    ->suffix(fn(Get $get) => SellingProducts::find($get('selling_invoices_id')) ? SellingProducts::find($get('selling_invoices_id'))->unit : 'عدد')
                    ->numeric(2),
                TextInput::make('sallingPrice')
                    ->placeholder('سعر')
                    ->label('سعر')
                    ->required()
                    ->suffix('$')
                    ->numeric(2)

            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('SellingProducts.name')
                    ->label('اسم المواد')
                    ->searchable(),
                TextColumn::make('SellingProducts.code')
                    ->label('كود المواد'),
                TextColumn::make('colorCode')
                    ->searchable()
                    ->label('اللون'),
                TextColumn::make('sallingPrice')
                    ->searchable()
                    ->suffix(' $ ')
                    ->label('سعر'),
                TextColumn::make('qty')
                    ->searchable()
                    ->label('کمیة'),
                TextColumn::make('total')
                    ->label('مجموع')
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
