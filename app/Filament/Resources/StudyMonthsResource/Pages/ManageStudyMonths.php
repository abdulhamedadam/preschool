<?php

namespace App\Filament\Resources\StudyMonthsResource\Pages;

use App\Filament\Resources\StudyMonthsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStudyMonths extends ManageRecords
{
    protected static string $resource = StudyMonthsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
