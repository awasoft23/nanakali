<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LossedProductsResource\Pages;
use App\Filament\Resources\LossedProductsResource\RelationManagers;
use App\Models\LossedProducts;
use App\Models\SellingProducts;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class LossedProductsResource extends Resource
{
    protected static ?string $model = LossedProducts::class;


    protected static ?string $label = 'خسارة';
    protected static ?string $navigationGroup = 'يبيع';
    protected static ?string $activeNavigationIcon = 'fas-dollar-sign';
    protected static ?string $navigationLabel = 'خسارة';
    protected static ?string $pluralLabel = 'خسارة';
    protected static ?string $pluralModelLabel = 'خسارة';
    protected static ?string $recordTitleAttribute = 'خسارة';
    protected static ?int $navigationSort = 45;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('selling_products_id')
                    ->required()
                    ->options(SellingProducts::select('id', DB::raw('CONCAT("نوع المواد: ", name , " - نوع القالب: ", code, " - سعر: ",salePrice, "$") as productName'))
                        ->pluck('productName', 'id'))
                    ->searchable()
                    ->label('مواد')
                ,
                Select::make('colorCode')
                ->label('اللون')
                ->options(function(){
                    $dd=[];
                    for($i=1;$i<=100;$i++){
                        array_push($dd,$i);
                    }
                    return $dd;
                })->searchable(),
                Forms\Components\TextInput::make('sallingPrice')
                    ->label('سعر')
                    ->required()
                    ->numeric(2),
                Forms\Components\TextInput::make('qty')
                    ->label('کمیة')
                    ->required()
                    ->numeric(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at','desc')
            ->columns([
                Tables\Columns\TextColumn::make('SellingProducts.name')
                ->label('مواد')

                    ->numeric(2)
                    ->sortable(),
                    TextColumn::make('colorCode')
                    ->searchable()
                    ->label('اللون'),
                Tables\Columns\TextColumn::make('sallingPrice')
                    ->label('سعر')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('کمیة')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                ->label('مجموع')
                ->formatStateUsing(fn($record) => number_format($record->qty * $record->sallingPrice,2))
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/y')
                    ->sortable()
                    ->label('تاریخ')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLossedProducts::route('/'),
        ];
    }
}
