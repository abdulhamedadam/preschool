<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAttendanceResource\Pages;
use App\Models\StudentAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;

class StudentAttendanceResource extends Resource
{
    protected static ?string $model = StudentAttendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attendance_date')
                    ->label(__('common.Attendance Date'))
                    ->date('d-m-Y')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('month')
                    ->label(__('common.Month'))
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => __('common.January'),
                        2 => __('common.February'),
                        3 => __('common.March'),
                        4 => __('common.April'),
                        5 => __('common.May'),
                        6 => __('common.June'),
                        7 => __('common.July'),
                        8 => __('common.August'),
                        9 => __('common.September'),
                        10 => __('common.October'),
                        11 => __('common.November'),
                        12 => __('common.December'),
                        default => __('common.Unknown')
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('year')
                    ->label(__('common.Year'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make(('present_count'))
                    ->label(__('common.present_count'))
                    ->getStateUsing(fn($record) => $record->details()->where('status', 1)->count())
                    ->action(
                        Action::make('viewPresentStudents')
                            ->modalHeading(fn($record) => 'Present Students - ' . $record->attendance_date)
                            ->modalContent(function ($record) {
                                $presentStudents = $record->details()
                                    ->where('status', 1)
                                    ->with('student')
                                    ->get();

                                return view('filament.attendance.present_students', [
                                    'students' => $presentStudents
                                ]);
                            })
                            ->modalCancelActionLabel('Close')
                    )
                    ->color('success')
                    ->icon('heroicon-o-user-group'),

                Tables\Columns\TextColumn::make('absent_count')
                    ->label(__('common.absent_count'))
                    ->getStateUsing(fn($record) => $record->details()->where('status', 0)->count())
                    ->action(
                        Action::make('viewAbsentStudents')
                            ->modalHeading(fn($record) => 'Absent Students - ' . $record->attendance_date)
                            ->modalContent(function ($record) {
                                $absentStudents = $record->details()
                                    ->where('status', 0)
                                    ->with('student')
                                    ->get();

                                return view('filament.attendance.absent_students', [
                                    'students' => $absentStudents
                                ]);
                            })
                            ->modalCancelActionLabel('Close')
                    )
                    ->color('danger')
                    ->icon('heroicon-o-user-group'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('month')
                    ->options([
                        1 => __('common.January'),
                        2 => __('common.February'),
                        3 => __('common.March'),
                        4 => __('common.April'),
                        5 => __('common.May'),
                        6 => __('common.June'),
                        7 => __('common.July'),
                        8 => __('common.August'),
                        9 => __('common.September'),
                        10 => __('common.October'),
                        11 => __('common.November'),
                        12 => __('common.December'),
                    ]),

                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        $years = [];
                        $startYear = now()->subYears(5)->year;
                        $endYear = now()->addYear()->year;

                        for ($year = $startYear; $year <= $endYear; $year++) {
                            $years[$year] = $year;
                        }

                        return $years;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('attendance_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentAttendances::route('/'),
            'create' => Pages\CreateStudentAttendance::route('/create'),
            'edit' => Pages\EditStudentAttendance::route('/{record}/edit'),
        ];
    }

    public static function getBreadCrumb(): string
    {
        return __('common.Student Attendance');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Student Attendances');
    }

    public static function getLabel(): string
    {
        return __('common.Student Attendance');
    }

    public static function getModelLabel(): string
    {
        return __('common.Student Attendance');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Student Attendances');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Student Attendances');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
