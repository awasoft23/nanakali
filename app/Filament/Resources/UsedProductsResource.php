<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsedProductsResource\Pages;
use App\Models\PurchaseProducts;
use App\Models\PurchasingInvoiceProducts;
use App\Models\UsedProducts;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class UsedProductsResource extends Resource
{
    protected static ?string $model = UsedProducts::class;
    protected static ?string $label = 'بەکارهاتوو';

    protected static ?string $navigationGroup = 'کڕین';

    protected static ?string $navigationIcon = 'far-circle-check';

    protected static ?string $activeNavigationIcon = 'fas-circle-check';

    protected static ?string $navigationLabel = 'کاڵا بەکارهاتووەکان';
    protected static ?string $pluralLabel = 'کاڵا بەکارهاتووەکان';

    protected static ?string $pluralModelLabel = 'کاڵا بەکارهاتووەکان';

    protected static ?string $recordTitleAttribute = 'کاڵا بەکارهاتووەکان';

    protected static ?int $navigationSort = 30;


    public static function form(Form $form): Form
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))

            ->columns([
                TextColumn::make('PurchaseProducts.code')->label('کۆدی کاڵا')->searchable(),
                TextColumn::make('PurchaseProducts.name')->label('ناوی کاڵا')->searchable(),
                TextColumn::make('qty')->label('ژمارە')
                    ->formatStateUsing(fn($state, UsedProducts $record) => number_format($state, 0) . ' - ' . PurchaseProducts::find($record->purchase_products_id)->unit),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('کات و بەروار')
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار')
            ])
            ->actions([
                DeleteAction::make()
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsedProducts::route('/'),
        ];
    }
}