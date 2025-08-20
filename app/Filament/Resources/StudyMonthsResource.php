<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\StudyMonthsResource\Pages;
use App\Filament\Resources\StudyMonthsResource\RelationManagers;
use App\Models\StudyMonthes;
use App\Models\StudyMonths;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyMonthsResource extends Resource
{
    protected static ?string $model = StudyMonthes::class;
     protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('common.Name'))
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.Name'))
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
            'index' => Pages\ManageStudyMonths::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.Study Months');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Study Months');
    }

    public static function getLabel(): string
    {
        return __('common.Study Month');
    }

    public static function getModelLabel(): string
    {
        return __('common.Study Month');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Study Months');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Study Months');
    }

      public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
