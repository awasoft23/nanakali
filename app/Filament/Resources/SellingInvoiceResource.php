<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellingInvoiceResource\Pages;
use App\Filament\Resources\SellingInvoiceResource\RelationManagers\SellingInvoiceProductsRelationManager;
use App\Models\Customers;
use App\Models\SellingInvoice;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class SellingInvoiceResource extends Resource
{
    protected static ?string $model = SellingInvoice::class;

    protected static ?string $label = 'فرۆشتن';
    protected static ?string $navigationGroup = 'فرۆشتن';
    protected static ?string $navigationIcon = 'fas-cart-shopping';
    protected static ?string $activeNavigationIcon = 'fas-cart-shopping';
    protected static ?string $navigationLabel = 'فرۆشتنی کاڵا';
    protected static ?string $pluralLabel = 'فرۆشتنی کاڵا';
    protected static ?string $pluralModelLabel = 'فرۆشتنی کاڵا';
    protected static ?string $recordTitleAttribute = 'فرۆشتنی کاڵا';
    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Select::make('customers_id')
                    ->relationship('customers', 'name')
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
                    ->options(Customers::all()->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('کڕیار')
                    ->label('کڕیار'),
                Forms\Components\TextInput::make('note')
                    ->placeholder('تێبینی')
                    ->label('تێبینی')
                    ->maxLength(255),
                Select::make('priceType')->options([
                    '$' => '$',
                    'د.ع' => 'د.ع'
                ])
                    ->live()
                    ->disabled(fn($operation) => $operation === 'create')
                    ->hidden(fn($operation) => $operation === 'create')
                    ->label('جۆری دراو'),
                Forms\Components\TextInput::make('paymented')
                    ->placeholder('بڕی واصلکراو')
                    ->label('بڕی واصلکراو')
                    ->disabled(fn($operation) => $operation === 'create')
                    ->hidden(fn($operation) => $operation === 'create')
                    ->afterStateHydrated(fn($state) => $state + 250)
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dolarPrice')
                    ->placeholder('نرخی دۆلار')
                    ->label('نرخی دۆلار')
                    ->disabled()
                    ->hidden(fn($operation, Get $get) => $operation === 'create' || $get('priceType') != 'د.ع')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))
            ->columns([
                TextColumn::make('id')->label('#')->searchable()->badge(true),
                Tables\Columns\TextColumn::make('customers.name')
                    ->label('کڕیار')
                    ->numeric()
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('customers.phone')
                    ->label('ژمارەی مۆبایل')
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('بڕی پارە')
                    ->numeric()
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymented')
                    ->label('بڕی واصلکراو')
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->color(fn($state, $record) => $record->amount - $record->paymented > 0 ? Color::Red : Color::Green)
                    ->label('کات و بەروار')
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                ActionsAction::make('print')->hidden(auth()->user()->role == 1)->label('چاپکردن')->url(fn($record) => '/selling-invoices/print/' . $record->id)->icon('fas-print')->openUrlInNewTab()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SellingInvoiceProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellingInvoices::route('/'),
            'CustomerPayments' => Pages\CustomerPayments::route('/CustomerPayments'),
            'create' => Pages\CreateSellingInvoice::route('/create'),
            'edit' => Pages\EditSellingInvoice::route('/{record}/edit'),
            'view' => Pages\ViewSellingInvoice::route('/{record}'),
            'print' => Pages\Invoice::route('/print/{record}'),
            'printPayment' => Pages\PaymentInvoice::route('/printPayment/{record}'),

        ];
    }
}