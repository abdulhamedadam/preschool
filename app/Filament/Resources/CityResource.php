<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->relationship(
                                name: 'Country',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->whereNull('parent_id')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('common.Country'))
                            ->native(false),
                        Forms\Components\TextInput::make('name')
                            ->label(__('common.Name'))
                            ->required()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->whereNotNull('parent_id'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.Country'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('Country.name')
                    ->label(__('common.City'))
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ManageCities::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.Cities');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Cities');
    }

    public static function getLabel(): string
    {
        return __('common.Cities');
    }

    public static function getModelLabel(): string
    {
        return __('common.City');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Cities');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Cities');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
