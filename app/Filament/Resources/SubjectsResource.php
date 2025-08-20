<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\SubjectsResource\Pages;
use App\Filament\Resources\SubjectsResource\RelationManagers;
use App\Models\Subjects;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectsResource extends Resource
{
    protected static ?string $model = Subjects::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
     protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
       return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('common.Name'))
                        ->required(),

                    Forms\Components\Textarea::make('description')
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
            'index' => Pages\ManageSubjects::route('/'),
        ];
    }

    public static function getBreadCrumb(): string
    {
        return __('common.Subjects');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Subjects');
    }

    public static function getLabel(): string
    {
        return __('common.Subject');
    }

    public static function getModelLabel(): string
    {
        return __('common.Subject');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Subjects');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Subjects');
    }

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
