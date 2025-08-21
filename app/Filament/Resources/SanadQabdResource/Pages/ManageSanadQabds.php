<?php

namespace App\Filament\Resources\SanadQabdResource\Pages;

use App\Filament\Resources\SanadQabdResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSanadQabds extends ManageRecords
{
    protected static string $resource = SanadQabdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function ($record) {
                    Transaction::create([
                        'type' => Transaction::REVENUE,
                        'value' => $record->value,
                        'date' => $record->date,
                        'student_id' => null,
                        'created_by' => $record->created_by,
                        'notes' => $record->notes . ' | Expense Item: ' . $record->student->name,
                        'table_id' => $record->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }),
        ];
    }
}
