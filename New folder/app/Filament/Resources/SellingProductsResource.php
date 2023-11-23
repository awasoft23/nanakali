<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellingProductsResource\Pages;
use App\Models\SellingProducts;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SellingProductsResource extends Resource
{
    protected static ?string $model = SellingProducts::class;




    protected static ?string $label = 'کاڵا';
    protected static ?string $navigationGroup = 'فرۆشتن';
    protected static ?string $navigationIcon = 'fab-codepen';
    protected static ?string $activeNavigationIcon = 'fab-codepen';
    protected static ?string $navigationLabel = 'کاڵاکان';
    protected static ?string $pluralLabel = 'کاڵاکان';
    protected static ?string $pluralModelLabel = 'کاڵاکان';
    protected static ?string $recordTitleAttribute = 'کاڵاکان';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('جۆری مەڕمەڕ')
                    ->label('جۆری مەڕمەڕ')
                    ->suffixIcon('fas-box-archive')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->placeholder('جۆری قالب')
                    ->label('جۆری قالب')
                    ->required()
                    ->suffixIcon('fas-barcode')
                    ->maxLength(255),
                Forms\Components\TextInput::make('colorCofe')
                    ->placeholder('کۆدی ڕەنگ')
                    ->label('کۆدی ڕەنگ')
                    ->required()
                    ->suffixIcon('fas-barcode')
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit')
                    ->placeholder('یەکە')
                    ->label('یەکە')
                    ->default('مەتر')
                    ->suffixIcon('fas-notes-medical')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('salePrice')
                    ->placeholder('نرخی فرۆشتن')
                    ->label('نرخی فرۆشتن')
                    ->required()
                    ->suffix('$')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('جۆری مەڕمەڕ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('جۆری قالب')
                    ->searchable(),
                Tables\Columns\TextColumn::make('colorCofe')
                    ->label('کۆدی ڕەنگ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('یەکە')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salePrice')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->label('نرخی کڕین')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSellingProducts::route('/'),
            'create' => Pages\CreateSellingProducts::route('/create'),
            'edit' => Pages\EditSellingProducts::route('/{record}/edit'),
        ];
    }
}