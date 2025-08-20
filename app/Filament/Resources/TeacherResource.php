<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Unique;

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

                Tables\Columns\ToggleColumn::make('status')
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
            ])->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('common.add_teacher'))
                    ->modalHeading(__('common.add_teacher'))
                    ->modalWidth('5xl')
                    ->using(function (array $data) {
                        $teacher = \App\Models\Teacher::create($data);

                        // Create classroom automatically
                        \App\Models\ClassRoom::create([
                            'name'       => $teacher->name,
                            'teacher_id' => $teacher->id,
                        ]);

                        return $teacher;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
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



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            //  'create' => Pages\CreateTeacher::route('/create'),
            //  'edit' => Pages\EditTeacher::route('/{record}/edit'),

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
