<?php

namespace App\Filament\Resources\StudentsBirthDaysResource\Pages;

use App\Filament\Resources\StudentsBirthDaysResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentsBirthDays extends EditRecord
{
    protected static string $resource = StudentsBirthDaysResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\DeleteAction::make(),
        ];
    }
}
