<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpensesResource\Pages;
use App\Filament\Resources\ExpensesResource\Widgets\ExpensesOverview;
use App\Models\Expenses;
use App\Models\ExpensesTypes;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ExpensesResource extends Resource
{
    protected static ?string $model = Expenses::class;
    protected static ?string $label = 'خەرجی';


    protected static ?string $navigationIcon = 'fas-layer-group';

    protected static ?string $activeNavigationIcon = 'fas-layer-group';

    protected static ?string $navigationLabel = 'خەرجییەکان';
    protected static ?string $pluralLabel = 'خەرجییەکان';

    protected static ?string $pluralModelLabel = 'خەرجییەکان';
    protected static ?string $recordTitleAttribute = 'خەرجییەکان';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('ناوی وەرگری پارە')
                    ->placeholder('ناوی وەرگری پارە')
                    ->hint('تێبینی ئەم ناوە تەنها بۆ سەر پسولەیە.'),
                Forms\Components\Select::make('expenses_type_id')
                    ->label('جۆری خەرجی')
                    ->placeholder('جۆری خەرجی')
                    ->suffixIcon('far-clipboard')
                    ->relationship('ExpensesTypes', 'ExpenseType')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('ExpenseType')
                            ->label('جۆری خەرجی')
                            ->placeholder('جۆری خەرجی')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('ExpenseType')
                            ->label('جۆری خەرجی')
                            ->placeholder('جۆری خەرجی')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->options(ExpensesTypes::all()->pluck('ExpenseType', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->label('تێبینی')
                    ->suffixIcon('far-file')
                    ->maxLength(255),
                Select::make('priceType')
                    ->label('جۆری دراو')
                    ->searchable()
                    ->suffixIcon('fas-coins')
                    ->default(0)
                    ->required()
                    ->live()
                    ->options([
                        0 => '$',
                        1 => 'د.ع'
                    ]),
                Forms\Components\TextInput::make('amount')
                    ->label('بڕی پارە')
                    ->suffix(fn(Get $get) => $get('priceType') == 0 ? '$' : 'د.ع')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc')->where('id', '>', 1))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('وەرگر')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ExpensesTypes.ExpenseType')
                    ->label('جۆری خەرجی')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('تێبینی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable()
                    ->label('بڕ')
                    ->formatStateUsing(fn($state, Expenses $record) => $record->priceType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('کۆی گشتی دۆلاری ئەمریکی')->using(function (Builder $query) {
                            return $query->where('priceType', 0)->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('کۆی گشتی دیناری عێراقی')->using(function (Builder $query) {
                            return $query->where('priceType', 1)->sum('amount');
                        })->numeric(0)
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('کات و بەروار')
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
                ,

            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('بەروار')
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('print')->hidden(auth()->user()->role == 1)->label('چاپکردن')->icon('fas-print')->url(fn($record) => '/expenses/print/' . $record->id)->openUrlInNewTab()
            ]);
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
            'index' => Pages\ListExpenses::route('/'),
            'print' => Pages\Invoice::route('/print/{record}'),

        ];
    }
}