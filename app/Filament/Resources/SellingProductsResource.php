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




    protected static ?string $label = 'المواد';
    protected static ?string $navigationGroup = 'يبيع';
    protected static ?string $navigationIcon = 'fab-codepen';
    protected static ?string $activeNavigationIcon = 'fab-codepen';
    protected static ?string $navigationLabel = 'المواد';
    protected static ?string $pluralLabel = 'المواد';
    protected static ?string $pluralModelLabel = 'المواد';
    protected static ?string $recordTitleAttribute = 'المواد';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder("نوع المواد")
                    ->label("نوع المواد")
                    ->suffixIcon('fas-box-archive')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->placeholder("نوع القالب")
                    ->label("نوع القالب")
                    ->required()
                    ->suffixIcon('fas-barcode')
                    ->maxLength(255),
                Forms\Components\TextInput::make('colorCofe')
                    ->placeholder('رمز اللون')
                    ->label('رمز اللون')
                    ->required()
                    ->suffixIcon('fas-barcode')
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit')
                    ->placeholder('متر')
                    ->label('متر')
                    ->default('متر')
                    ->suffixIcon('fas-notes-medical')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('salePrice')
                    ->placeholder('سعر البيع')
                    ->label('سعر البيع')
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
                    ->label("نوع المواد")
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label("نوع القالب")
                    ->searchable(),
                Tables\Columns\TextColumn::make('colorCofe')
                    ->label('رمز اللون')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('متر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salePrice')
                    ->suffix(' $ ')
                    ->numeric(2)
                    ->label('سعر الشراء')
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
