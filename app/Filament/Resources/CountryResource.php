<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('common.Name'))
                        ->required()
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('parent_id'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('common.Name'))
                    ->sortable()
                    ->searchable()


            ])
            ->filters([

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
            'index' => Pages\ManageCountries::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.Countries');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Countries');
    }

    public static function getLabel(): string
    {
        return __('common.Countries');
    }

    public static function getModelLabel(): string
    {
        return __('common.Country');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Countries');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Countries');
    }

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
