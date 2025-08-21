<?php

namespace App\Filament\Resources\ExpensesItemsResource\Pages;

use App\Filament\Resources\ExpensesItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExpensesItems extends ManageRecords
{
    protected static string $resource = ExpensesItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
