<?php

namespace App\Filament\Resources\StudentsBirthDaysResource\Pages;

use App\Filament\Resources\StudentsBirthDaysResource;
use Carbon\Carbon;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStudentsBirthDays extends ListRecords
{
    protected static string $resource = StudentsBirthDaysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        // Get the active tab filter
        $activeTab = $this->activeTab;

        if ($activeTab === 'today') {
            $today = Carbon::today();
            $query->whereDay('date_of_birth', $today->day)
                ->whereMonth('date_of_birth', $today->month);
        } elseif ($activeTab === 'this_week') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $query->where(function ($q) use ($startOfWeek, $endOfWeek) {
                $q->where(function ($q) use ($startOfWeek, $endOfWeek) {
                    // For birthdays within the current week
                    $q->whereMonth('date_of_birth', '>=', $startOfWeek->month)
                        ->whereDay('date_of_birth', '>=', $startOfWeek->day)
                        ->whereMonth('date_of_birth', '<=', $endOfWeek->month)
                        ->whereDay('date_of_birth', '<=', $endOfWeek->day);
                });
            });
        } elseif ($activeTab === 'this_month') {
            $query->whereMonth('date_of_birth', Carbon::now()->month);
        }

        return $query;
    }

    public function getTabs(): array
    {
        $tabs = [
            'today' => Tab::make(__('common.today'))
                ->modifyQueryUsing(function (Builder $query) {
                    $today = Carbon::today();
                    return $query->whereRaw('DAY(date_of_birth) = ?', [$today->day])
                        ->whereRaw('MONTH(date_of_birth) = ?', [$today->month]);
                }),
            'this_week' => Tab::make(__('common.this_week'))
                ->modifyQueryUsing(function (Builder $query) {
                    $startOfWeek = Carbon::now()->startOfWeek();
                    $endOfWeek = Carbon::now()->endOfWeek();

                    return $query->where(function ($q) use ($startOfWeek, $endOfWeek) {
                        $q->where(function ($q) use ($startOfWeek, $endOfWeek) {
                            $q->whereMonth('date_of_birth', '>=', $startOfWeek->month)
                                ->whereDay('date_of_birth', '>=', $startOfWeek->day)
                                ->whereMonth('date_of_birth', '<=', $endOfWeek->month)
                                ->whereDay('date_of_birth', '<=', $endOfWeek->day);
                        });
                    });
                }),
            'this_month' => Tab::make(__('common.this_month'))
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->whereMonth('date_of_birth', Carbon::now()->month);
                }),
        ];

        $tabs['today']->badge(
            StudentsBirthDaysResource::getModel()::whereRaw('DAY(date_of_birth) = ?', [Carbon::today()->day])
                ->whereRaw('MONTH(date_of_birth) = ?', [Carbon::today()->month])
                ->count()
        );

        $tabs['this_week']->badge(StudentsBirthDaysResource::getModel()::where(function ($q) {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $q->where(function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereMonth('date_of_birth', '>=', $startOfWeek->month)
                    ->whereDay('date_of_birth', '>=', $startOfWeek->day)
                    ->whereMonth('date_of_birth', '<=', $endOfWeek->month)
                    ->whereDay('date_of_birth', '<=', $endOfWeek->day);
            });
        })->count());

        $tabs['this_month']->badge(StudentsBirthDaysResource::getModel()::whereMonth('date_of_birth', Carbon::now()->month)->count());

        return $tabs;
    }
}
