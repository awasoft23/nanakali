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
    protected static ?string $label = 'مستخدم';

    protected static ?string $navigationGroup = 'شراء';

    protected static ?string $navigationIcon = 'far-circle-check';

    protected static ?string $activeNavigationIcon = 'fas-circle-check';

    protected static ?string $navigationLabel = "المواد المستهلكة";
    protected static ?string $pluralLabel = "المواد المستهلكة";

    protected static ?string $pluralModelLabel = "المواد المستهلكة";

    protected static ?string $recordTitleAttribute = "المواد المستهلكة";

    protected static ?int $navigationSort = 30;


    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Select::make('purchase_products_id')
                    ->relationship('PurchaseProducts', 'code')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم')
                            ->placeholder('اسم')
                            ->suffixIcon('fas-box-archive')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('كود المواد')
                            ->placeholder('كود المواد')
                            ->suffixIcon('fas-barcode')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unit')
                            ->label('متر')
                            ->placeholder('متر')
                            ->suffixIcon('fas-notes-medical')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('purchasePricw')
                            ->label('سعر الشراء')
                            ->placeholder('سعر الشراء')
                            ->suffix('$')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->label('المواد')
                    ->searchable()
                    ->live()
                    ->required()
                    ->options(
                        PurchaseProducts::select('id', DB::raw('CONCAT("اسم: ", name , " - کود: ", code, " - سعر: ",purchasePricw, "$") as productName'))->pluck('productName', 'id')
                    )
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('purchase_price', PurchaseProducts::find($state) ? PurchaseProducts::find($state)->purchasePricw : 0);
                    })
                    ->columnSpanFull(),
                TextInput::make('qty')
                    ->required()
                    ->disabled(fn(Get $get) => !PurchaseProducts::find($get('purchase_products_id')))
                    ->label(fn(Get $get) => PurchaseProducts::find($get('purchase_products_id')) ? PurchaseProducts::find($get('purchase_products_id'))->unit : 'متر')
                    ->suffix(fn(Get $get) => PurchaseProducts::find($get('purchase_products_id')) ? PurchaseProducts::find($get('purchase_products_id'))->unit : null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))

            ->columns([
                TextColumn::make('PurchaseProducts.code')->label('كود المواد')->searchable(),
                TextColumn::make('PurchaseProducts.name')->label('اسم المواد')->searchable(),
                TextColumn::make('qty')->label('رقم')
                    ->formatStateUsing(fn($state, UsedProducts $record) => number_format($state, 0) . ' - ' . PurchaseProducts::find($record->purchase_products_id)->unit),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('الوقت و التاريخ')
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('تاریخ')
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
