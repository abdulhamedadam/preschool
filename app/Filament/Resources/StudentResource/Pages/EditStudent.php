<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\StudentsHistory;
use App\Models\TeacherStudents;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;
    protected function afterSave(): void
    {
        // Update teacher_students if teacher changed
        if ($this->record->wasChanged('teacher_id')) {
            TeacherStudents::updateOrCreate(
                ['student_id' => $this->record->id],
                [
                    'teacher_id' => $this->record->teacher_id,
                    'assignment_date' => now(),
                    'notes' => 'Teacher changed',
                ]
            );
        }

        // Add history record for important changes
        if ($this->record->wasChanged('status')) {
            $action = $this->record->status ? 'reactivated' : 'deactivated';
            StudentsHistory::create([
                'student_id' => $this->record->id,
                'action' => $action,
                'date' => now(),
                'details' => 'Student status changed',
                'created_by' => auth()->id(),
            ]);
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
