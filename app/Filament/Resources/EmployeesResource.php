<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeesResource\Pages;
use App\Models\Employees;
use App\Models\Expenses;
use App\Models\Salary;
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

    protected static ?string $label = 'موظف';

    protected static ?string $navigationGroup = 'إعدادات';

    protected static ?string $navigationIcon = 'far-circle-user';

    protected static ?string $activeNavigationIcon = 'fas-circle-user';

    protected static ?string $navigationLabel = 'الموظفين';
    protected static ?string $pluralLabel = 'الموظفين';

    protected static ?string $pluralModelLabel = 'الموظفين';
    protected static ?string $recordTitleAttribute = 'الموظفين';

    protected static ?int $navigationSort = 45;
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phoneNumber', 'address'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'اسم' => $record->name,
            'رقم الهاتف' => $record->phoneNumber,
            'عنوان' => $record->address,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('اسم الثلاثي')
                    ->suffixIcon('far-user')
                    ->label('اسم الثلاثي')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phoneNumber')
                    ->placeholder('رقم الهاتف')
                    ->label('رقم الهاتف')
                    ->suffixIcon('fas-phone-volume')
                    ->required()
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('عنوان')
                    ->placeholder('عنوان')
                    ->suffixIcon('fas-location-crosshairs')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('IDCardType')
                    ->placeholder('نوع الهوية')
                    ->label('نوع الهوية')
                    ->suffixIcon('fas-address-card')
                    ->maxLength(255),
                Forms\Components\TextInput::make('IDCardNumber')
                    ->suffixIcon('fas-hashtag')
                    ->placeholder('رقم الهویة')
                    ->label('رقم الهویة')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('DOB')
                    ->placeholder('تاريخ الميلاد')
                    ->label('تاريخ الميلاد')
                    ->suffixIcon('fas-calendar-days')
                    ->required(),
                Forms\Components\DatePicker::make('DateOfWork')
                    ->placeholder('تاريخ البدء')
                    ->suffixIcon('fas-calendar-days')
                    ->label('تاريخ البدء')
                    ->required(),
                Select::make('salaryType')
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
                Forms\Components\TextInput::make('salary')
                    ->placeholder('راتب')
                    ->label('راتب')
                    ->suffix(fn(Get $get) => $get('salaryType') == 0 ? '$' : 'د.ع')
                    ->required()
                    ->numeric(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('lasSalary', 'asc'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phoneNumber')
                    ->label('رقم الهاتف')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('IDCardNumber')
                    ->label('رقم الهویة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DateOfWork')
                    ->label('تاريخ البدء')
                    ->date('d/m/y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monthAbsense')
                    ->label('الغياب الشهري')

                    ->sortable(),
                Tables\Columns\TextColumn::make('totalAbsense')
                    ->label('الغياب التام')

                    ->sortable(),
                Tables\Columns\TextColumn::make('salary')
                    ->label('راتب')
                    ->formatStateUsing(fn($state, Employees $record) => $record->salaryType == 0 ? '$' . number_format($state, 2) : number_format($state, 0) . 'د.ع')
                    ->summarize([
                        Summarizer::make()->label('مجموع دولار الامریکی')->using(function (Builder $query) {
                            return $query->where('salaryType', 0)->sum('salary');
                        })->numeric(2),
                        Summarizer::make()->label('إجمالي الدينار العراقي')->using(function (Builder $query) {
                            return $query->where('salaryType', 1)->sum('salary');
                        })->numeric(0)
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('lasSalary')
                    ->label('الراتب التالي')
                    ->color(fn($state) => Carbon::parse($state) < Carbon::now() ? Color::Red : Color::Green)
                    ->dateTime('d/m/y')
                    ->sortable(),
            ])
            ->filters([
                DateRangeFilter::make('lasSalary')->label('الراتب التالي'),
                DateRangeFilter::make('DateOfWork')->label('تاريخ البدء'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('salary')
                        ->hidden(auth()->user()->role == 1)
                        ->label('راتب')
                        ->icon('fas-hand-holding-dollar')
                        ->modalIcon('fas-hand-holding-dollar')

                        ->color('success')
                        ->form(function ($record, Form $form) {
                            return $form->schema([
                                DatePicker::make('lasSalary')->label('الدفعة الأخيرة')->default($record->lasSalary)->required(),
                                DatePicker::make('nextSalary')->label('الراتب التالي')->default(Carbon::parse($record->lasSalary)->addMonths(1))->required(),
                                TextInput::make('slarys')->label('مبلغ الراتب')->default($record->salary)->suffix($record->salaryType == 0 ? '$' : 'د.ع')->disabled(),
                                TextInput::make('absense')->label('غیابات')->default($record->monthAbsense)->disabled(),
                                TextInput::make('absense11')->label('راتب مقدما')->default(Salary::where('employees_id',$record->id)->where('created_at','>=',$record->ss)->sum('amount'))->disabled(),
                                TextInput::make('absenses')->label('المستقطع')->default(number_format(($record->monthAbsense) * ($record->salary / 30), 2))->disabled(),
                                TextInput::make('salary')->label('مبلغ الراتب')->default(number_format($record->salary - ((($record->monthAbsense) * ($record->salary / 30)) + Salary::where('employees_id',$record->id)->where('created_at','>=',$record->ss)->sum('amount')), 2, '.', ''))->suffix($record->salaryType == 0 ? '$' : 'د.ع')->required()
                            ])->columns(2);
                        })->action(
                            function (array $data, $record) {
                                $record->update([
                                    'lasSalary' => $data['nextSalary'],
                                    'monthAbsense' => 0,
                                    'ss'=>Carbon::now()

                                ]);
                                Expenses::create([
                                    'expenses_type_id' => 1,
                                    'note' => 'راتب الموظف: ' . $record->name,
                                    'priceType' => $record->salaryType,
                                    'amount' => $data['salary'],
                                    'user_name' => auth()->user()->name,
                                    'name' => $record->name
                                ]);
                            }
                        )
                        ->requiresConfirmation()
                        ->modalButton('راتب')
                        ->modalDescription('عند الضغط على زر الدفع، سيتم خصم راتب الشهر السابق من حساب المحاسب واحتسابه كمصروف، وسيكون غياب الموظف هذا الشهر صفراً.')

                ])->label('الإجراءات')->button()
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
