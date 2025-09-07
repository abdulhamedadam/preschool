<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Guardian;
use App\Models\Level;
use App\Models\Student;
use App\Models\Students;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;
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

                        Select::make('level_id')
                            ->label(__('common.level'))
                            ->options(Level::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state) {
                                    $level = \App\Models\Level::find($state);
                                    $set('term_expenses', $level?->term_expenses ?? 0);
                                    // تحديث التوتال مباشرة
                                    $set('total_expenses', ($level?->term_expenses ?? 0) + ($get('has_transport') ? 50 : 0));
                                } else {
                                    $set('term_expenses', 0);
                                    $set('total_expenses', ($get('has_transport') ? 50 : 0));
                                }
                            }),

                        TextInput::make('term_expenses')
                            ->label(__('common.term_expenses'))
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->dehydrated(true),

                        Toggle::make('has_transport')
                            ->label(__('common.has_transport'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $set('total_expenses', ($get('term_expenses') ?? 0) + ($state ? 50 : 0));
                            }),

                        TextInput::make('total_expenses')
                            ->label(__('common.total_expenses'))
                            ->numeric()
                            ->disabled()
                            ->reactive()
                            ->afterStateHydrated(function ($state, callable $set, $get) {
                                $set('total_expenses', ($get('term_expenses') ?? 0) + ($get('has_transport') ? 50 : 0));
                            })
                            ->dehydrated(true),



                        DatePicker::make('date_of_birth')
                            ->label(__('common.date_of_birth'))
                            ->displayFormat('d M Y')
                            ->maxDate(now()),

                        TextInput::make('address')
                            ->label(__('common.address'))
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Select::make('guardian_id')
                            ->label(__('common.guardian'))
                            ->options(Guardian::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->suffixAction(
                                Forms\Components\Actions\Action::make(__('common.add_guardian'))
                                    ->icon('heroicon-o-plus')
                                    ->tooltip(__('common.add_guardian'))
                                    ->form([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('common.name'))
                                            ->required(),
                                        Forms\Components\TextInput::make('phone')
                                            ->label(__('common.phone'))
                                            ->tel(),
                                    ])
                                    ->action(function (array $data, Forms\Set $set) {
                                        $guardian = Guardian::create($data);
                                        $set('guardian_id', $guardian->id);
                                    })
                                    ->modalSubmitActionLabel(__('common.save'))
                            ),


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

                        DatePicker::make('enrollment_date')
                            ->label(__('common.enrollment_date'))
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

                        Select::make('teacher_id')
                            ->label(__('common.teachers'))
                            ->options(\App\Models\Teacher::pluck('name', 'id'))
                            ->preload()
                            ->searchable(),

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

                Tables\Columns\IconColumn::make('status')
                    ->label(__('common.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label(__('common.teacher'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_expenses')
                    ->label(__('common.total_expenses'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label(__('common.level'))
                    ->relationship('level', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('common.status'))
                    ->options([
                        1 => __('common.active'),
                        0 => __('common.inactive'),
                    ]),

                Tables\Filters\Filter::make('enrollment_date')
                    ->label(__('common.enrollment_date'))
                    ->form([
                        DatePicker::make('enrolled_from')
                            ->label(__('common.from_date')),
                        DatePicker::make('enrolled_until')
                            ->label(__('common.to_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['enrolled_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('enrollment_date', '>=', $date),
                            )
                            ->when(
                                $data['enrolled_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('enrollment_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Tables\Grouping\Group::make('level.name')
                    ->label(__('common.level'))
                    ->collapsible(),

                Tables\Grouping\Group::make('status')
                    ->label(__('common.status'))
                    ->collapsible(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }


    public static function getNavigationLabel(): string
    {
        return __('common.students');
    }

    public static function getModelLabel(): string
    {
        return __('common.student');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.students');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
