<?php

namespace App\Filament\Resources\LevelsResource\Pages;

use App\Filament\Resources\LevelsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLevels extends ManageRecords
{
    protected static string $resource = LevelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
