<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnProductsResource\Pages;
use App\Filament\Resources\ReturnProductsResource\RelationManagers;
use App\Models\Customers;
use App\Models\ReturnProducts;
use App\Models\SellingProducts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class ReturnProductsResource extends Resource
{
    protected static ?string $model = ReturnProducts::class;

    protected static ?string $label = 'عودة المواد';
    protected static ?string $navigationGroup = 'يبيع';
    protected static ?string $navigationLabel = 'عودة المواد';
    protected static ?string $pluralLabel = 'عودة المواد';
    protected static ?string $pluralModelLabel = 'عودة المواد';
    protected static ?string $recordTitleAttribute = 'عودة المواد';
    protected static ?int $navigationSort = 45;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('selling_products_id')
                    ->required()
                    ->options(SellingProducts::select('id', DB::raw('CONCAT("نوع المواد: ", name , " - نوع القالب: ", code," - رمز اللون: ", colorCofe, " - سعر: ",salePrice, "$") as productName'))
                        ->pluck('productName', 'id'))
                    ->searchable()
                    ->label('مواد'),
                Forms\Components\TextInput::make('sallingPrice')
                    ->label('سعر')
                    ->required()
                    ->numeric(2),
                Forms\Components\TextInput::make('qty')
                    ->label('کمیة')
                    ->required()
                    ->numeric(2),
                Forms\Components\Select::make('customers_id')
                    ->options(Customers::all()->pluck('name', 'id'))
                    ->searchable()
                    ->label('عميل'),
                Forms\Components\Select::make('status')
                    ->label('خسارة')
                    ->options([
                        1 => 'بەڵێ',
                        0 => 'نەخێر'
                    ])
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sellingProducts.name')
                    ->numeric(2)
                    ->label('مواد')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customers.name')
                    ->label('عميل')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('sallingPrice')
                    ->label('سعر')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('کمیة')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn($record) => number_format($record->qty * $record->sallingPrice))

                    ->label('مجموع')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('status')
                    ->label('خسارة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime('d/m/y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReturnProducts::route('/'),
        ];
    }
}
