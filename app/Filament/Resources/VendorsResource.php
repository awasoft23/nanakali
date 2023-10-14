<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorsResource\Pages;
use App\Models\Vendors;
use Filament\Actions\ActionGroup;
use Filament\Actions\ForceDeleteAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ForceDeleteAction as ActionsForceDeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class VendorsResource extends Resource
{
    protected static ?string $model = Vendors::class;

    protected static ?string $label = 'فرۆشیار';

    protected static ?string $navigationGroup = 'ڕێکخستنەکان';

    protected static ?string $navigationIcon = 'fas-users';

    protected static ?string $activeNavigationIcon = 'fas-users-between-lines';

    protected static ?string $navigationLabel = 'فرۆشیارەکان';
    protected static ?string $pluralLabel = 'فرۆشیارەکان';

    protected static ?string $pluralModelLabel = 'فرۆشیارەکان';


    protected static ?int $navigationSort = 42;
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phone', 'address'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ناو' => $record->name,
            'ژمارەی مۆبایل' => $record->phone,
            'ناونیشان' => $record->address,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('ناو')
                    ->suffixIcon('far-user')
                    ->label('ناو')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->placeholder('ژمارەی مۆبایل')
                    ->label('ژمارەی مۆبایل')
                    ->suffixIcon('fas-phone-volume')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('ناونیشان')
                    ->placeholder('ناونیشان')
                    ->suffixIcon('fas-location-crosshairs')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(\Illuminate\Database\Eloquent\Builder $query) => $query->orderBy('id', 'desc'))

            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('ژمارەی مۆبایل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('ناونیشان')
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionsActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make()
                ])->label('کردارەکان')->button(),
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendors::route('/create'),
            'view' => Pages\ViewVendors::route('/{record}'),
            'edit' => Pages\EditVendors::route('/{record}/edit'),
        ];
    }
}