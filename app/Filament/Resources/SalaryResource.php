<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalaryResource\Pages;
use App\Filament\Resources\SalaryResource\RelationManagers;
use App\Models\Employees;
use App\Models\Salary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static ?string $label = 'راتب مقدما';
    protected static ?string $navigationGroup = 'إعدادات';
    protected static ?string $navigationLabel = 'راتب مقدما';
    protected static ?string $pluralLabel = 'راتب مقدما';
    protected static ?string $pluralModelLabel = 'راتب مقدما';
    protected static ?string $recordTitleAttribute = 'راتب مقدما';
    protected static ?int $navigationSort = 45;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employees_id')
                    ->options(Employees::all()->pluck('name', 'id'))
                    ->label('الموظف')
                    ->options(Employees::all()->pluck('name', 'id'))
                    ->live(),
                Forms\Components\TextInput::make('amount')
                    ->label('کمیة')
                    ->suffix(fn(Get $get) => Employees::find($get('employees_id')) ? Employees::find($get('employees_id'))->salaryType==0?'$':'د.ع' : '')
                    ->required()
                    ->numeric(2),
                Forms\Components\TextInput::make('note')
                    ->required()
                    ->label('الملاحظة')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employees.name')
                    ->label('موظف')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('کمیة')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('الملاحظة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/y')
                    ->sortable()
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSalaries::route('/'),
        ];
    }
}
