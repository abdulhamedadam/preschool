<?php

namespace App\Filament\Resources\SupervisorResource\Pages;

use App\Filament\Resources\SupervisorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSupervisors extends ManageRecords
{
    protected static string $resource = SupervisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
