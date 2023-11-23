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
    protected static ?string $label = 'المصاریف';


    protected static ?string $navigationIcon = 'fas-layer-group';

    protected static ?string $activeNavigationIcon = 'fas-layer-group';

    protected static ?string $navigationLabel = 'المصاریف';
    protected static ?string $pluralLabel = 'المصاریف';

    protected static ?string $pluralModelLabel = 'المصاریف';
    protected static ?string $recordTitleAttribute = 'المصاریف';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('اسم المستفيد')
                    ->placeholder('اسم المستفيد')
                    ->hint('لاحظ أن هذا الاسم مخصص للوصلات فقط.'),
                Forms\Components\Select::make('expenses_type_id')
                    ->label('نوع الالمصاریف')
                    ->placeholder('نوع الالمصاریف')
                    ->suffixIcon('far-clipboard')
                    ->relationship('ExpensesTypes', 'ExpenseType')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('ExpenseType')
                            ->label('نوع الالمصاریف')
                            ->placeholder('نوع الالمصاریف')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->editOptionForm([
                        Forms\Components\TextInput::make('ExpenseType')
                            ->label('نوع الالمصاریف')
                            ->placeholder('نوع الالمصاریف')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->options(ExpensesTypes::all()->pluck('ExpenseType', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->label('الملاحظة')
                    ->suffixIcon('far-file')
                    ->maxLength(255),
                Select::make('priceType')
                    ->label('نوع العملة')
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
                    ->label('مبلغ من المال')
                    ->suffix(fn(Get $get) => $get('priceType') == 0 ? '$' : 'د.ع')
                    ->required()
                    ->numeric(2),
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
                    ->label('نوع الالمصاریف')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('الملاحظة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable()
                    ->label('کمیة')
                    ->formatStateUsing(fn($state, Expenses $record) => $record->priceType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('مجموع دولار الامریکی')->using(function (Builder $query) {
                            return $query->where('priceType', 0)->sum('amount');
                        })->numeric(2),
                        Summarizer::make()->label('إجمالي الدينار العراقي')->using(function (Builder $query) {
                            return $query->where('priceType', 1)->sum('amount');
                        })->numeric(0)
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('الوقت و التاريخ')
                    ->dateTime('d/m/y H:i:s')
                    ->sortable()
                ,

            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('تاریخ')
            ])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('print')->hidden(auth()->user()->role == 1)->label('الطباعة')->icon('fas-print')->url(fn($record) => '/expenses/print/' . $record->id)->openUrlInNewTab()
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
