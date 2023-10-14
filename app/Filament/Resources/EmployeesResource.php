<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Models\Employees;
use App\Models\Expenses;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class EmployeesResource extends Resource
{
    protected static ?string $model = Employees::class;

    protected static ?string $label = 'فەرمانبەر';

    protected static ?string $navigationGroup = 'ڕێکخستنەکان';

    protected static ?string $navigationIcon = 'far-circle-user';

    protected static ?string $activeNavigationIcon = 'fas-circle-user';

    protected static ?string $navigationLabel = 'فەرمانبەرەکان';
    protected static ?string $pluralLabel = 'فەرمانبەرەکان';

    protected static ?string $pluralModelLabel = 'فەرمانبەرەکان';
    protected static ?string $recordTitleAttribute = 'فەرمانبەرەکان';

    protected static ?int $navigationSort = 45;
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phoneNumber', 'address'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ناو' => $record->name,
            'ژمارەی مۆبایل' => $record->phoneNumber,
            'ناونیشان' => $record->address,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('ناوی سیانی')
                    ->suffixIcon('far-user')
                    ->label('ناوی سیانی')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phoneNumber')
                    ->placeholder('ژمارەی مۆبایل')
                    ->label('ژمارەی مۆبایل')
                    ->suffixIcon('fas-phone-volume')
                    ->required()
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('ناونیشان')
                    ->placeholder('ناونیشان')
                    ->suffixIcon('fas-location-crosshairs')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('IDCardType')
                    ->placeholder('جۆری ناسنامە')
                    ->label('جۆری ناسنامە')
                    ->suffixIcon('fas-address-card')
                    ->maxLength(255),
                Forms\Components\TextInput::make('IDCardNumber')
                    ->suffixIcon('fas-hashtag')
                    ->placeholder('ژمارەی ناسنامە')
                    ->label('ژمارەی ناسنامە')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('DOB')
                    ->placeholder('بەرواری لەدایکبوون')
                    ->label('بەرواری لەدایکبوون')
                    ->suffixIcon('fas-calendar-days')
                    ->required(),
                Forms\Components\DatePicker::make('DateOfWork')
                    ->placeholder('بەرواری دەستبەکاربوون')
                    ->suffixIcon('fas-calendar-days')
                    ->label('بەرواری دەستبەکاربوون')
                    ->required(),
                Select::make('salaryType')
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
                Forms\Components\TextInput::make('salary')
                    ->placeholder('مووچە')
                    ->label('مووچە')
                    ->suffix(fn(Get $get) => $get('salaryType') == 0 ? '$' : 'د.ع')
                    ->required()
                    ->numeric(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('lasSalary', 'asc'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phoneNumber')
                    ->label('ژمارەی مۆبایل')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('IDCardNumber')
                    ->label('ژمارەی ناسنامە')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DateOfWork')
                    ->label('بەرواری دەستبەکاربوون')
                    ->date('d/m/y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monthAbsense')
                    ->label('غیاباتی مانگانە')

                    ->sortable(),
                Tables\Columns\TextColumn::make('totalAbsense')
                    ->label('کۆی غیابات')

                    ->sortable(),
                Tables\Columns\TextColumn::make('salary')
                    ->label('مووچە')
                    ->formatStateUsing(fn($state, Employees $record) => $record->salaryType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('کۆی گشتی دۆلاری ئەمریکی')->using(function (Builder $query) {
                            return $query->where('salaryType', 0)->sum('salary');
                        })->numeric(2),
                        Summarizer::make()->label('کۆی گشتی دیناری عێراقی')->using(function (Builder $query) {
                            return $query->where('salaryType', 1)->sum('salary');
                        })->numeric(0)
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('lasSalary')
                    ->label('مووچەی داهاتوو')
                    ->color(fn($state) => Carbon::parse($state) < Carbon::now() ? Color::Red : Color::Green)
                    ->dateTime('d/m/y')
                    ->sortable(),
            ])
            ->filters([
                DateRangeFilter::make('lasSalary')->label('مووچەی داهاتوو'),
                DateRangeFilter::make('DateOfWork')->label('بەرواری دەستبەکاربوون'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('salary')
                        ->hidden(auth()->user()->role == 1)
                        ->label('مووچەدان')
                        ->icon('fas-hand-holding-dollar')
                        ->modalIcon('fas-hand-holding-dollar')

                        ->color('success')
                        ->form(function ($record, Form $form) {
                            return $form->schema([
                                DatePicker::make('lasSalary')->label('کۆتا مووچەدان')->default($record->lasSalary)->required(),
                                DatePicker::make('nextSalary')->label('مووچەی داهاتوو')->default(Carbon::parse($record->lasSalary)->addMonths(1))->required(),
                                TextInput::make('slarys')->label('بڕی مووچە')->default($record->salary)->suffix($record->salaryType == 0 ? '$' : 'د.ع')->disabled(),
                                TextInput::make('absense')->label('غیابات')->default($record->monthAbsense)->disabled(),
                                TextInput::make('absenses')->label('لێبڕین')->default(number_format(($record->monthAbsense) * ($record->salary / 30), 2))->disabled(),
                                TextInput::make('salary')->label('بڕی مووچە')->default(number_format($record->salary - (($record->monthAbsense) * ($record->salary / 30)), 2, '.', ''))->suffix($record->salaryType == 0 ? '$' : 'د.ع')->required()
                            ])->columns(2);
                        })->action(
                            function (array $data, $record) {
                                $record->update([
                                    'lasSalary' => $data['nextSalary'],
                                    'monthAbsense' => 0

                                ]);
                                Expenses::create([
                                    'expenses_type_id' => 1,
                                    'note' => 'مووچەی  فەرمانبەر : ' . $record->name,
                                    'priceType' => $record->salaryType,
                                    'amount' => $data['salary'],
                                    'user_name' => auth()->user()->name,
                                    'name' => $record->name
                                ]);
                            }
                        )
                        ->requiresConfirmation()
                        ->modalButton('مووچەدان')
                        ->modalDescription('لەکاتی داگرتنی دوگمەی مووچەدان، مووچەی مانگی پێشووی ئەم کارمەندە، لە هەژماری ژمێریار کەمدەبێتەوە و وەک خەرجی هەژماردەکرێت، هەروەها غیاباتی ئەم مانگەی فەرمانبەر سفر دەبێتەوە')

                ])->label('کردارەکان')->button()
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployees::route('/'),
            'absenses' => Pages\EmployeesAbsensesList::route('/absenses'),
        ];
    }
}