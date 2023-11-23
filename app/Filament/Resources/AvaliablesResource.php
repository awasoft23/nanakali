<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvaliablesResource\Pages;
use App\Filament\Resources\AvaliablesResource\RelationManagers;
use App\Models\Avaliables;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvaliablesResource extends Resource
{
    protected static ?string $model = Avaliables::class;


    protected static ?string $label ='الموجودات ثابتة';
    protected static ?string $navigationGroup = 'إعدادات';
    protected static ?string $activeNavigationIcon = 'fas-dollar-sign';
    protected static ?string $navigationLabel ='الموجودات ثابتة';
    protected static ?string $pluralLabel ='الموجودات ثابتة';
    protected static ?string $pluralModelLabel ='الموجودات ثابتة';
    protected static ?string $recordTitleAttribute ='الموجودات ثابتة';
    protected static ?int $navigationSort = 45;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('اسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('note')
                ->label('الملاحظة')
                    ->required()
                    ->maxLength(255),
                Select::make('type')->label('نوع')->options([
                    1=>'شراء',
                    2=>'بیع'
                ]),
                Forms\Components\TextInput::make('dollaramount')
                ->label('المبلغ بالدولار')
                    ->required()
                    ->numeric(2),
                Forms\Components\TextInput::make('dinnaramount')
                ->label('المبلغ بالدينار')
                    ->required()
                    ->numeric(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('اسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                ->label('الملاحظة')
                    ->searchable(),
                TextColumn::make('type')->label('نوع')->formatStateUsing(fn($state)=>$state == 1?'شراء':'بیع'),
                Tables\Columns\TextColumn::make('dollaramount')
                ->label('المبلغ بالدولار')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('dinnaramount')
                ->label('المبلغ بالدينار')
                    ->numeric(2)
                    ->summarize(Sum::make()->numeric(2))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([

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
            'index' => Pages\ListAvaliables::route('/'),

        ];
    }
}
