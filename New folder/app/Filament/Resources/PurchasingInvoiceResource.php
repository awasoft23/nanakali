<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchasingInvoiceResource\Pages;
use App\Filament\Resources\PurchasingInvoiceResource\RelationManagers\PurchasingInvoiceProductsRelationManager;
use App\Models\PurchasingInvoice;
use App\Models\Vendors;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class PurchasingInvoiceResource extends Resource
{
    protected static ?string $model = PurchasingInvoice::class;
    protected static ?string $label = 'کڕین';
    protected static ?string $navigationGroup = 'کڕین';
    protected static ?string $navigationIcon = 'fas-truck';
    protected static ?string $activeNavigationIcon = 'fas-truck-ramp-box';
    protected static ?string $navigationLabel = 'کڕینی کاڵا';
    protected static ?string $pluralLabel = 'کڕینی کاڵا';
    protected static ?string $pluralModelLabel = 'کڕینی کاڵا';
    protected static ?string $recordTitleAttribute = 'کڕینی کاڵا';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('invoice_id')->default(0)->required()->label('ژمارەی پسولە'),
                Forms\Components\Select::make('vendors_id')
                    ->relationship('vendors', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->placeholder('ناو')
                            ->suffixIcon('far-user')
                            ->label('ناو')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->placeholder('ژمارەی مۆبایل')
                            ->label('ژمارەی مۆبایل')
                            ->suffixIcon('fas-phone-volume')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('ناونیشان')
                            ->placeholder('ناونیشان')
                            ->suffixIcon('fas-location-crosshairs')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->options(Vendors::all()->pluck('name', 'id'))
                    ->label('فرۆشیار')
                    ->searchable(),
                Select::make('priceType')->options([
                    '$' => '$',
                    'د.ع' => 'د.ع'
                ])
                    ->disabled(fn($operation) => $operation === 'create')
                    ->hidden(fn($operation) => $operation === 'create')
                    ->label('جۆری دراو'),
                Forms\Components\TextInput::make('paymented')
                    ->label('واصل')
                    ->disabled(fn($operation) => $operation === 'create')
                    ->hidden(fn($operation) => $operation === 'create')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))

            ->columns([
                Tables\Columns\TextColumn::make('vendors.name')
                    ->label('فرۆشیار')
                    ->numeric()
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('کۆی گشتی')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(2))
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymented')
                    ->label('واصل')
                    ->suffix(' $ ')
                    ->summarize(Sum::make()->numeric(2))
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state, $record) => number_format($record->amount - $record->paymented, 2))
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->label('ماوە')
                    ->suffix(' $ ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('کات و بەروار')
                    ->dateTime('d/m/y H:i:s')
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable()
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PurchasingInvoiceProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchasingInvoices::route('/'),
            'vendorPayments' => Pages\VendorPayments::route('/vendorPayments'),
            'create' => Pages\CreatePurchasingInvoice::route('/create'),
            'edit' => Pages\EditPurchasingInvoice::route('/{record}/edit'),
            'printPayment' => Pages\Invoice::route('/printPayment/{record}'),

        ];
    }
}