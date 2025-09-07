<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SalariesRelationManager extends RelationManager
{
    protected static string $relationship = 'salaries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->label(__('common.amount'))
                    ->numeric()
                    ->required()
                    ->minValue(0),
                    
                Forms\Components\DatePicker::make('created_at')
                    ->label(__('common.effective_date'))
                    ->required(),
                    
                Forms\Components\Textarea::make('notes')
                    ->label(__('common.notes'))
                    ->maxLength(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('common.amount'))
                    ->money('USD')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.effective_date'))
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('common.notes'))
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}