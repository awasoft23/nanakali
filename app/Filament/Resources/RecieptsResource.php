<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecieptsResource\Pages;
use App\Models\Reciepts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RecieptsResource extends Resource
{
    protected static ?string $model = Reciepts::class;

    protected static ?string $label = 'دانان و هەڵگرتنی پارە';


    protected static ?string $navigationIcon = 'far-handshake';

    protected static ?string $activeNavigationIcon = 'fas-handshake';

    protected static ?string $navigationLabel = 'خاوەن پشکەکان';
    protected static ?string $pluralLabel = 'خاوەن پشکەکان';

    protected static ?string $pluralModelLabel = 'خاوەن پشکەکان';

    protected static ?string $recordTitleAttribute = 'خاوەن پشکەکان';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('partnersName')
                    ->options([
                        'صبر محمد مولود' => 'صبر محمد مولود',
                        'ئەیاد عبدولشریف' => 'ئەیاد عبدولشریف',
                        'ئەشقی ئەحمەد ئیبراهیم' => 'ئەشقی ئەحمەد ئیبراهیم'
                    ])
                    ->searchable()
                    ->label('خاوەن پشک')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->label('تێبینی')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->label('جۆری کردار')
                    ->searchable()
                    ->default(0)
                    ->options([
                        0 => 'پارەدانان',
                        1 => 'پارە بردن'
                    ]),
                Forms\Components\Select::make('priceType')
                    ->label('جۆری دراو')
                    ->required()
                    ->searchable()
                    ->default('$')
                    ->options([
                        '$' => '$',
                        'د.ع' => 'د.ع'
                    ]),
                Forms\Components\TextInput::make('amount')
                    ->label('بڕی پارە')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc')->where('id', '>', 6))
            ->columns([
                Tables\Columns\TextColumn::make('partnersName')
                    ->label('خاوەن پشک')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('تێبینی')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('جۆر')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->formatStateUsing(fn($state) => $state == 0 ? 'پارەدانان' : 'پارەبردن')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('بڕی پارە')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->formatStateUsing(fn($state, $record) => $record->priceType == '$' ? number_format($state, 2) . ' ' . $record->priceType : number_format($state, 0) . ' ' . $record->priceType)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('کات و بەروار')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('جۆری کردار')
                    ->searchable()
                    ->options([
                        0 => 'پارەدانان',
                        1 => 'پارە بردن'
                    ]),
                SelectFilter::make('partnersName')
                    ->options([
                        'صبر محمد مولود' => 'صبر محمد مولود',
                        'ئەیاد عبدولشریف' => 'ئەیاد عبدولشریف',
                        'ئەشقی ئەحمەد ئیبراهیم' => 'ئەشقی ئەحمەد ئیبراهیم'
                    ])
                    ->searchable()
                    ->label('خاوەن پشک'),
                SelectFilter::make('priceType')
                    ->label('جۆری دراو')
                    ->searchable()

                    ->options([
                        '$' => '$',
                        'د.ع' => 'د.ع'
                    ])
            ])
        ;
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReciepts::route('/'),

        ];
    }
}