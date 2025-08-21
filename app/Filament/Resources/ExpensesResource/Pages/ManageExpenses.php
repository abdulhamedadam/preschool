<?php

namespace App\Filament\Resources\ExpensesResource\Pages;

use App\Filament\Resources\ExpensesResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\DB;

class ManageExpenses extends ManageRecords
{
    protected static string $resource = ExpensesResource::class;

   protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->after(function ($record) {
                    Transaction::create([
                        'type' => Transaction::EXPENSE,
                        'value' => $record->value,
                        'date' => $record->date,
                        'student_id' => null,
                        'created_by' => $record->created_by,
                        'notes' => $record->notes . ' | Expense Item: ' . $record->band->name,
                        'table_id' => $record->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }),
        ];
    }
}
