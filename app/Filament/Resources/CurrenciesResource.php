<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrenciesResource\Pages;
use App\Models\Currencies;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CurrenciesResource extends Resource
{
    protected static ?string $model = Currencies::class;
    protected static ?string $navigationIcon = 'fas-coins';
    protected static ?string $label = 'نرخی دۆلار';
    protected static ?string $navigationGroup = 'ڕێکخستنەکان';
    protected static ?string $activeNavigationIcon = 'fas-dollar-sign';
    protected static ?string $navigationLabel = 'نرخی دۆلار';
    protected static ?string $pluralLabel = 'نرخی دۆلار';
    protected static ?string $pluralModelLabel = 'نرخی دۆلار';
    protected static ?string $recordTitleAttribute = 'نرخی دۆلار';
    protected static ?int $navigationSort = 45;

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextInputColumn::make('dinarPrice')
                    ->label('نرخی دۆلار'),

            ])
        ;
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencies::route('/'),

        ];
    }
}