<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseProductsResource\Pages;
use App\Models\PurchaseProducts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseProductsResource extends Resource
{
    protected static ?string $model = PurchaseProducts::class;

    protected static ?string $label = 'المواد';

    protected static ?string $navigationGroup = 'شراء';

    protected static ?string $navigationIcon = 'fas-box-archive';

    protected static ?string $activeNavigationIcon = 'fas-box-open';

    protected static ?string $navigationLabel = 'المواد';
    protected static ?string $pluralLabel = 'المواد';

    protected static ?string $pluralModelLabel = 'المواد';

    protected static ?string $recordTitleAttribute = 'المواد';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('کۆد')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('متر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchasePricw')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->label('سعر الشراء')
                    ->searchable(),
                TextColumn::make('purchasing_invoice_products_sum_qty')->sum('PurchasingInvoiceProducts', 'qty')->label('شراء')->numeric(0),
                TextColumn::make('used_products_sum_qty')->sum('UsedProducts', 'qty')->label('مستخدم')->numeric(0),
                TextColumn::make('status')->formatStateUsing(fn($record) => number_format($record->purchasing_invoice_products_sum_qty - $record->used_products_sum_qty, 0) . ' - ' . $record->unit)


            ])

            ->actions([
                Tables\Actions\EditAction::make(),
            ])
        ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseProducts::route('/'),
            'create' => Pages\CreatePurchaseProducts::route('/create'),
            'edit' => Pages\EditPurchaseProducts::route('/{record}/edit'),
        ];
    }
}
