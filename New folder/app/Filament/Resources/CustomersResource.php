<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomersResource\Pages;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;


    protected static ?string $label = 'کڕیار';

    protected static ?string $navigationGroup = 'ڕێکخستنەکان';

    protected static ?string $navigationIcon = 'far-handshake';

    protected static ?string $activeNavigationIcon = 'fas-handshake';

    protected static ?string $navigationLabel = 'کڕیارەکان';
    protected static ?string $pluralLabel = 'کڕیارەکان';

    protected static ?string $pluralModelLabel = 'کڕیارەکان';

    protected static ?string $recordTitleAttribute = 'کڕیارەکان';

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
                ActionGroup::make([
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomers::route('/create'),
            'view' => Pages\ViewCustomers::route('/{record}'),
            'edit' => Pages\EditCustomers::route('/{record}/edit'),
        ];
    }
}