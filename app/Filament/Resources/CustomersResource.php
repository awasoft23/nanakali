<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomersResource\Pages;
use App\Models\Customers;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomersResource extends Resource
{
    protected static ?string $model = Customers::class;


    protected static ?string $label = 'عميل';

    protected static ?string $navigationGroup = 'إعدادات';

    protected static ?string $navigationIcon = 'far-handshake';

    protected static ?string $activeNavigationIcon = 'fas-handshake';

    protected static ?string $navigationLabel = 'العملاء';
    protected static ?string $pluralLabel = 'العملاء';

    protected static ?string $pluralModelLabel = 'العملاء';

    protected static ?string $recordTitleAttribute = 'العملاء';

    protected static ?int $navigationSort = 42;
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'phone', 'address'];
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'اسم' => $record->name,
            'رقم الهاتف' => $record->phone,
            'عنوان' => $record->address,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->placeholder('اسم')
                    ->suffixIcon('far-user')
                    ->label('اسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->placeholder('رقم الهاتف')
                    ->label('رقم الهاتف')
                    ->suffixIcon('fas-phone-volume')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('عنوان')
                    ->placeholder('عنوان')
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
                    ->label('اسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('عنوان')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make(),
                    Action::make('کشف حساب')
                    ->form([
                        DatePicker::make('from')->label('من'),
                        DatePicker::make('to')->label('الی'),

                    ])
                    ->requiresConfirmation()
                    ->action(fn($record,array $data)=> redirect('/customers/report/'.$record->id.'/'.$data['from'].'/'.$data['to']))->label('کشف حساب')
                ])->label('الإجراءات')->button(),
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
            'report' => Pages\PrintA::route('report/{id}/{from}/{to}'),
        ];
    }
}
