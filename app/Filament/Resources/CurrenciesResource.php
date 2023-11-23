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
    protected static ?string $label = 'سعر الدولار';
    protected static ?string $navigationGroup = 'إعدادات';
    protected static ?string $activeNavigationIcon = 'fas-dollar-sign';
    protected static ?string $navigationLabel = 'سعر الدولار';
    protected static ?string $pluralLabel = 'سعر الدولار';
    protected static ?string $pluralModelLabel = 'سعر الدولار';
    protected static ?string $recordTitleAttribute = 'سعر الدولار';
    protected static ?int $navigationSort = 45;

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextInputColumn::make('dinarPrice')
                    ->label('سعر الدولار'),

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