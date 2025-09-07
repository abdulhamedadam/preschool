<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers\SalariesRelationManager;
use App\Models\Teacher;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label(__('common.phone'))
                            ->tel()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),

                        TextInput::make('age')
                            ->label(__('common.age'))
                            ->numeric()
                            ->minValue(18)
                            ->maxValue(100),

                        TextInput::make('address')
                            ->label(__('common.address'))
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Select::make('country_id')
                            ->label(__('common.country'))
                            ->options(\App\Models\City::query()
                                ->where('parent_id', null)
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('city_id', null)),

                        Select::make('city_id')
                            ->label(__('common.city'))
                            ->options(fn($get) => \App\Models\City::query()
                                ->where('parent_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),

                        DatePicker::make('hire_date')
                            ->label(__('common.hire_date'))
                            ->displayFormat('d M Y')
                            ->maxDate(now())
                            ->default(now()),

                        Select::make('status')
                            ->label(__('common.status'))
                            ->options([
                                1 => __('common.active'),
                                0 => __('common.inactive'),
                            ])
                            ->default(1)
                            ->required(),

                        Textarea::make('notes')
                            ->label(__('common.notes'))
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label(__('common.phone'))
                    ->searchable(),

                TextColumn::make('current_salary')
                    ->label(__('common.current_salary'))

                    ->sortable()
                    ->getStateUsing(fn(Teacher $record) => $record->current_salary),

                TextColumn::make('country.name')
                    ->label(__('common.country'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('city.name')
                    ->label(__('common.city'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('hire_date')
                    ->label(__('common.hire_date'))
                    ->date('d M Y')
                    ->sortable(),

                ToggleColumn::make('status')
                    ->label(__('common.status'))
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('common.status'))
                    ->options([
                        1 => __('common.active'),
                        0 => __('common.inactive'),
                    ]),

                Tables\Filters\Filter::make('hired_after')
                    ->label(__('teacher.hired_after'))
                    ->form([
                        DatePicker::make('hired_from')
                            ->label(__('teacher.hired_from_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['hired_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('hire_date', '>=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('common.add_teacher'))
                    ->modalHeading(__('common.add_teacher'))
                    ->modalWidth('5xl')
                    ->using(function (array $data) {
                        $teacher = Teacher::create($data);

                        // Create classroom automatically
                        \App\Models\ClassRoom::create([
                            'name'       => $teacher->name,
                            'teacher_id' => $teacher->id,
                        ]);

                        return $teacher;
                    }),
            ])
            ->actions([
                // Add Salary Action
                // In TeacherResource.php - replace the add_salary action with this:

                Tables\Actions\Action::make('add_update_salary')
                    ->label(__('common.manage_salary'))
                    ->icon('heroicon-o-currency-dollar')
                    ->iconButton()
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label(__('common.salary_amount'))
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->step(0.01),

                    
                        Forms\Components\Textarea::make('notes')
                            ->label(__('common.notes'))
                            ->maxLength(500),
                    ])
                    ->fillForm(function (Teacher $record) {
                        // Get current month's salary if exists
                        $currentMonthSalary = $record->salaries()
                            ->whereYear('created_at', now()->year)
                            ->whereMonth('created_at', now()->month)
                            ->first();

                        return [
                            'amount' => $currentMonthSalary?->amount,
                            'effective_date' => $currentMonthSalary ? $currentMonthSalary->created_at : now(),
                            'notes' => $currentMonthSalary?->notes,
                        ];
                    })
                    ->action(function (Teacher $record, array $data): void {
                        $effectiveDate = Carbon::parse($data['effective_date']);

               
                        $existingSalary = $record->salaries()
                            ->whereYear('created_at', $effectiveDate->year)
                            ->whereMonth('created_at', $effectiveDate->month)
                            ->first();

                        if ($existingSalary) {
                          
                            $existingSalary->update([
                                'amount' => $data['amount'],
                                'notes' => $data['notes'] ?? null,
                            ]);

                            $message = __('common.salary_updated_success');
                        } else {
                        
                            $record->salaries()->create([
                                'amount' => $data['amount'],
                                'notes' => $data['notes'] ?? null,
                                'created_at' => $data['effective_date'],
                            ]);

                            $message = __('common.salary_added_success');
                        }

                        // Show success notification
                        Notification::make()
                            ->title($message)
                            ->success()
                            ->send();
                    }),






                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->modalWidth('5xl')
                    ->using(function (Teacher $record, array $data) {
                        $record->update($data);

                        if ($record->classRoom) {
                            $record->classRoom->update([
                                'name' => $record->name,
                            ]);
                        }

                        return $record;
                    }),

                Tables\Actions\ViewAction::make()
                    ->iconButton(),

                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('hire_date', 'desc')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SalariesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('common.teachers');
    }

    public static function getModelLabel(): string
    {
        return __('common.teacher');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.teachers');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
