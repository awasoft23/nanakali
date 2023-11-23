<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $label = 'بەکارهێنەر';

    protected static ?string $navigationGroup = 'ڕێکخستنەکان';

    protected static ?string $navigationIcon = 'far-user';

    protected static ?string $activeNavigationIcon = 'fas-user';

    protected static ?string $navigationLabel = 'بەکارهێنەران';
    protected static ?string $pluralLabel = 'بەکارهێنەران';

    protected static ?string $pluralModelLabel = 'بەکارهێنەران';
    protected static ?string $recordTitleAttribute = 'بەکارهێنەران';



    protected static ?int $navigationSort = 45;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'ناو' => $record->name,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('ناو')
                    ->autofocus()
                    ->suffixIcon('far-user')
                    ->label('ناو')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->placeholder('پۆستەی ئەلیکترۆنی')
                    ->label('پۆستەی ئەلیکترۆنی')
                    ->suffixIcon('far-envelope')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->autofocus(false)
                    ->autocomplete(false)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->placeholder('وشەی نهێنی')
                    ->label('وشەی نهێنی')
                    ->suffixIcon('fas-unlock')
                    ->password()
                    ->autofocus(false)
                    ->autocomplete(false)
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),
                Forms\Components\Select::make('role')
                    ->options([
                        0 => 'ژمێریار',
                        1 => 'بەڕێوەبەر'
                    ])
                    ->default(0)
                    ->placeholder('پلە')
                    ->label('پلە')
                    ->suffixIcon('fas-layer-group')
                    ->searchable()
                    ->required(),
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
                Tables\Columns\TextColumn::make('email')
                    ->label('پۆستەی ئەلیکترۆنی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->formatStateUsing(fn($state) => $state == 0 ? 'ژمێریار' : 'بەڕێوەبەر')
                    ->label('پلە')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        0 => 'ژمێریار',
                        1 => 'بەڕێوەبەر'
                    ])
                    ->default(0)
                    ->label('پلە')
                    ->searchable()

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->label('کردارەکان')->button()
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}