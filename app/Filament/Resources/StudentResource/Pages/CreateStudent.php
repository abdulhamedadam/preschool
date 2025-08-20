<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\StudentsHistory;
use App\Models\TeacherStudents;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
    protected function afterCreate(): void
    {
        // Save to teacher_students table
        if ($this->record->teacher_id) {
            TeacherStudents::create([
                'teacher_id' => $this->record->teacher_id,
                'student_id' => $this->record->id,
                'assignment_date' => now(),
                'notes' => 'Initial assignment',
            ]);
        }

        // Save to students_histories table
        StudentsHistory::create([
            'student_id' => $this->record->id,
            'action' => 'enrolled',
            'date' => now(),
            'details' => 'Student enrolled in the system',
            'created_by' => auth()->id(),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
