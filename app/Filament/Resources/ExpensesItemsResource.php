<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\ExpensesItemsResource\Pages;
use App\Filament\Resources\ExpensesItemsResource\RelationManagers;
use App\Models\BnodSarf;
use App\Models\ExpensesItems;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpensesItemsResource extends Resource
{
    protected static ?string $model = BnodSarf::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 6;
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
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('common.Name'))
                    ->sortable()
                    ->searchable()


            ])
            ->filters([])
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
            'index' => Pages\ManageExpensesItems::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.expenses_items');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.expenses_items');
    }

    public static function getLabel(): string
    {
        return __('common.expenses_items');
    }

    public static function getModelLabel(): string
    {
        return __('common.expenses_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.expenses_items');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.expenses_items');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
