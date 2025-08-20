<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsBirthDaysResource\Pages;
use App\Filament\Resources\StudentsBirthDaysResource\RelationManagers;
use App\Models\Students;
use App\Models\StudentsBirthDays;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsBirthDaysResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?int $navigationSort = 9;
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level.name')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label(__('common.teacher'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level.name')
                    ->label(__('common.level'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label(__('common.date_of_birth'))
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('guardian.name')
                    ->label(__('common.guardian'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label(__('common.enrollment_date'))
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label(__('common.whatsapp'))
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn($record) => "https://wa.me/+2" . $record->guardian->phone, true)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {

        return parent::getEloquentQuery()->whereMonth('date_of_birth', Carbon::now()->month);
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
            'index' => Pages\ListStudentsBirthDays::route('/'),
            'create' => Pages\CreateStudentsBirthDays::route('/create'),
            'edit' => Pages\EditStudentsBirthDays::route('/{record}/edit'),
        ];
    }


    public static function getNavigationLabel(): string
    {
        return __('common.students_birthdays');
    }

    public static function getModelLabel(): string
    {
        return __('common.students_birthday');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.students_birthdays');
    }

    public static function getNavigationBadge(): ?string
    {
        $today = now(); // أو Carbon::today()

        return static::getModel()::whereRaw('DAY(date_of_birth) = ?', [$today->day])
            ->whereRaw('MONTH(date_of_birth) = ?', [$today->month])
            ->count();
    }
}
