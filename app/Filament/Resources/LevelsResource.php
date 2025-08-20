<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\LevelsResource\Pages;
use App\Filament\Resources\LevelsResource\RelationManagers;
use App\Models\Level;
use App\Models\Levels;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LevelsResource extends Resource
{
    protected static ?string $model = Level::class;
    protected static ?string $cluster = Settings::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('common.Name'))
                        ->required(),

                    Textarea::make('description')
                        ->label(__('common.Description'))
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
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
            'index' => Pages\ManageLevels::route('/'),
        ];
    }

    public static function getBreadCrumb(): string
    {
        return __('common.Levels');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Levels');
    }

    public static function getLabel(): string
    {
        return __('common.Levels');
    }

    public static function getModelLabel(): string
    {
        return __('common.Level');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Levels');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Levels');
    }

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
