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

    protected static ?string $label = "راس المال الأموال والاحتفاظ بها";


    protected static ?string $navigationIcon = 'far-handshake';

    protected static ?string $activeNavigationIcon = 'fas-handshake';

    protected static ?string $navigationLabel = "الشرکاء";
    protected static ?string $pluralLabel = "الشرکاء";

    protected static ?string $pluralModelLabel = "الشرکاء";

    protected static ?string $recordTitleAttribute = "الشرکاء";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('partnersName')
                    ->options([
                        'صبر محمد مولود' => 'صبر محمد مولود',
                        'ئەیاد عبدولشریف' => 'ئەیاد عبدولشریف',
                    ])
                    ->searchable()
                    ->label('شريك')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->label('الملاحظة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->required()
                    ->label('نوع الإجراء')
                    ->searchable()
                    ->default(0)
                    ->options([
                        0 => 'راس المال',
                        1 => 'أخذ المال'
                    ]),
                Forms\Components\Select::make('priceType')
                    ->label('نوع العملة')
                    ->required()
                    ->searchable()
                    ->default('$')
                    ->options([
                        '$' => '$',
                        'د.ع' => 'د.ع'
                    ]),
                Forms\Components\TextInput::make('amount')
                    ->label('مبلغ من المال')
                    ->required()
                    ->numeric(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc')->where('id', '>', 6))
            ->columns([
                Tables\Columns\TextColumn::make('partnersName')
                    ->label('شريك')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('الملاحظة')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->formatStateUsing(fn($state) => $state == 0 ? 'راس المال' : 'محسوبات شخصیة')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('مبلغ من المال')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->formatStateUsing(fn($state, $record) => $record->priceType == '$' ? number_format($state, 2) . ' ' . $record->priceType : number_format($state, 0) . ' ' . $record->priceType)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('الوقت و التاريخ')
                    ->color(fn($record) => $record->type == 0 ? Color::Green : Color::Red)
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('نوع الإجراء')
                    ->searchable()
                    ->options([
                        0 => 'راس المال',
                        1 => 'أخذ المال'
                    ]),
                SelectFilter::make('partnersName')
                    ->options([
                        'صبر محمد مولود' => 'صبر محمد مولود',
                        'ئەیاد عبدولشریف' => 'ئەیاد عبدولشریف',
                    ])
                    ->searchable()
                    ->label('شريك'),
                SelectFilter::make('priceType')
                    ->label('نوع العملة')
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
