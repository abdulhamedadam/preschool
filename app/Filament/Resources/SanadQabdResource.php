<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SanadQabdResource\Pages;
use App\Filament\Resources\SanadQabdResource\RelationManagers;
use App\Models\SanadQabd;
use App\Models\SandQabd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SanadQabdResource extends Resource
{
    protected static ?string $model = SandQabd::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 12;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label(__('common.student'))
                    ->relationship('student', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('value')
                    ->label(__('common.value'))
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->label(__('common.Date'))
                    ->required()
                    ->default(now()),
                Forms\Components\Textarea::make('notes')
                    ->label(__('common.Notes'))
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id())

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label(__('common.student'))
                    ->searchable(),


                Tables\Columns\TextColumn::make('value')
                    ->label(__('common.value'))
                     ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label(__('common.Date'))
                     ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('common.Notes'))
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
            'index' => Pages\ManageSanadQabds::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.sanad_qabds');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.sanad_qabds');
    }

    public static function getLabel(): string
    {
        return __('common.sanad_qabds');
    }

    public static function getModelLabel(): string
    {
        return __('common.sanad_qabd');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.sanad_qabds');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.sanad_qabds');
    }
}
