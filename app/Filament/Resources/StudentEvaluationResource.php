<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentEvaluationResource\Pages;
use App\Filament\Resources\StudentEvaluationResource\RelationManagers;
use App\Models\StudentEvaluation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
class StudentEvaluationResource extends Resource
{
    protected static ?string $model = StudentEvaluation::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 12;

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
                 TextColumn::make('month')
                ->label(__('common.month'))
                ->formatStateUsing(function ($state) {
                    $months = [
                        1 => __('common.january'),
                        2 => __('common.february'),
                        3 => __('common.march'),
                        4 => __('common.april'),
                        5 => __('common.may'),
                        6 => __('common.june'),
                        7 => __('common.july'),
                        8 => __('common.august'),
                        9 => __('common.september'),
                        10 => __('common.october'),
                        11 => __('common.november'),
                        12 => __('common.december'),
                    ];
                    return $months[$state] ?? $state;
                })
                ->sortable()
                ->searchable(),
                
            TextColumn::make('year')
                ->label(__('common.year'))
                ->sortable()
                ->searchable(),
                
           
                
            TextColumn::make('evaluation_details_count')
                ->label(__('common.students_count'))
                ->counts('evaluationDetails')
                ->sortable()
                ->badge()
                ->color('primary'),
                

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                 Action::make('view_details')
                ->label(__('common.view_details'))
                ->icon('heroicon-o-eye')
                ->modalHeading(__('common.evaluation_details'))
                ->modalContent(function (StudentEvaluation $record) {
                    return view('filament.evaluations.evaluation_details', [
                        'evaluation' => $record
                    ]);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel(__('common.close')),
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
            'index' => Pages\ListStudentEvaluations::route('/'),
            'create' => Pages\CreateStudentEvaluation::route('/create'),
            'edit' => Pages\EditStudentEvaluation::route('/{record}/edit'),
        ];
    }


       public static function getNavigationLabel(): string
    {
        return __('common.student_evaluations');
    }


    public static function getModelLabel(): string
    {
        return __('common.student_evaluations');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.student_evaluations');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
