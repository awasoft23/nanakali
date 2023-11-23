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

    protected static ?string $label = 'مستخدم';

    protected static ?string $navigationGroup = 'إعدادات';

    protected static ?string $navigationIcon = 'far-user';

    protected static ?string $activeNavigationIcon = 'fas-user';

    protected static ?string $navigationLabel = "المستخدمون";
    protected static ?string $pluralLabel = "المستخدمون";

    protected static ?string $pluralModelLabel = "المستخدمون";
    protected static ?string $recordTitleAttribute = "المستخدمون";



    protected static ?int $navigationSort = 45;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'اسم' => $record->name,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('اسم')
                    ->autofocus()
                    ->suffixIcon('far-user')
                    ->label('اسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->placeholder('بريد إلكتروني')
                    ->label('بريد إلكتروني')
                    ->suffixIcon('far-envelope')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->autofocus(false)
                    ->autocomplete(false)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->placeholder('كلمة المرور')
                    ->label('كلمة المرور')
                    ->suffixIcon('fas-unlock')
                    ->password()
                    ->autofocus(false)
                    ->autocomplete(false)
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create'),
                Forms\Components\Select::make('role')
                    ->options([
                        0 => 'محاسب',
                        1 => "المدير"
                    ])
                    ->default(0)
                    ->placeholder("سلطة")
                    ->label("سلطة")
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
                    ->label('اسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('بريد إلكتروني')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->formatStateUsing(fn($state) => $state == 0 ? 'محاسب' : "المدير")
                    ->label("سلطة")
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        0 => 'محاسب',
                        1 => "المدير"
                    ])
                    ->default(0)
                    ->label("سلطة")
                    ->searchable()

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->label('الإجراءات')->button()
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