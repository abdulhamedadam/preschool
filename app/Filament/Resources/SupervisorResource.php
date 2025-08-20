<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisorResource\Pages;
use App\Filament\Resources\SupervisorResource\RelationManagers;
use App\Models\Supervisor;
use App\Models\SupervisorTeachers;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class SupervisorResource extends Resource
{
    protected static ?string $model = Supervisor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 4;

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


                        Select::make('teacher_ids')
                            ->label(__('common.teachers'))
                            ->multiple()
                            ->options(\App\Models\Teacher::pluck('name', 'id'))
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                              ->rules(['array', 'max:4']),

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
                    ->label(__('common.add_supervisor'))
                    ->modalHeading(__('common.add_supervisor'))
                    ->modalWidth('5xl')
                    ->using(function (array $data) {
                        $teacherIds = $data['teacher_ids'] ?? [];
                        unset($data['teacher_ids']);

                        $supervisor = \App\Models\Supervisor::create($data);

                        $supervisor->teachers()->sync($teacherIds);

                        return $supervisor;
                    })

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->fillForm(function (Supervisor $record) {
                        return [
                            'name'        => $record->name,
                            'phone'       => $record->phone,
                            'age'         => $record->age,
                            'address'     => $record->address,
                            'country_id'  => $record->country_id,
                            'city_id'     => $record->city_id,
                            'hire_date'   => $record->hire_date,
                            'status'      => $record->status,
                            'notes'       => $record->notes,
                            'teacher_ids' => $record->teachers()->pluck('teachers.id')->toArray(),
                        ];
                    })
                    ->using(function (Supervisor $record, array $data) {
                        $teacherIds = $data['teacher_ids'] ?? [];
                        unset($data['teacher_ids']);

                        $record->update($data);
                        $record->teachers()->sync($teacherIds);

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSupervisors::route('/'),
        ];
    }

    public static function mutateFormDataBeforeFill(array $data): array
    {
        $data['teacher_ids'] = Supervisor::find($data['id'])
            ->teachers()
            ->pluck('teachers.id')
            ->toArray();

        return $data;
    }

    public static function getNavigationLabel(): string
    {
        return __('common.supervisors');
    }

    public static function getModelLabel(): string
    {
        return __('common.supervisor');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.supervisors');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


}
