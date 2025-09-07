<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurriculumResource\Pages;
use App\Filament\Resources\CurriculumResource\RelationManagers;
use App\Models\Curriculum;
use App\Models\StudyMonthes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurriculumResource extends Resource
{
    protected static ?string $model = StudyMonthes::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 13;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCurricula::route('/'),
            'create' => Pages\CreateCurriculum::route('/create/{id?}'),
            'edit' => Pages\EditCurriculum::route('/{record}/edit'),
        ];
    }


     public static function getBreadCrumb(): string
    {
        return __('common.curriculums');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.curriculums');
    }

    public static function getLabel(): string
    {
        return __('common.curriculums');
    }

    public static function getModelLabel(): string
    {
        return __('common.curriculum');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.curriculums');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.curriculums');
    }
}
