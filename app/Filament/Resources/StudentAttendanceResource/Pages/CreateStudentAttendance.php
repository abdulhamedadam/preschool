<?php

namespace App\Filament\Resources\StudentAttendanceResource\Pages;

use App\Filament\Resources\StudentAttendanceResource;
use App\Models\StudentAttendance;
use App\Models\StudentAttendanceDetails;
use App\Models\Students;
use App\Models\Teacher;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Grid;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CreateStudentAttendance extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentAttendanceResource::class;
    protected static string $view = 'filament.attendance.create_student_attendance';

    public ?array $data = [];
    public array $attendance = [];
    public $students = [];

    public function mount(): void
    {
        $this->form->fill([
            'date' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('teacher_id')
                            ->label(__('common.teacher'))
                            ->options(Teacher::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn () => $this->loadStudents())
                            ->columnSpan(1),

                        DatePicker::make('date')
                            ->label(__('common.attendance_date'))
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->columnSpan(1),
                    ]),
            ])
            ->statePath('data');
    }

    public function loadStudents(): void
    {
        $teacherId = $this->form->getState()['teacher_id'] ?? null;
        
        if ($teacherId) {
            $this->students = Students::whereHas('teachers', function($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->with('classRoom', 'guardian')
            ->get();

            // Initialize all students as present by default
            $this->attendance = $this->students->mapWithKeys(fn ($student) => [
                $student->id => 'present'
            ])->toArray();
        } else {
            $this->students = [];
            $this->attendance = [];
        }
    }

    public function markAllPresent(): void
    {
        foreach ($this->filteredStudents as $student) {
            $this->attendance[$student->id] = 'present';
        }
    }

    public function markAllAbsent(): void
    {
        foreach ($this->filteredStudents as $student) {
            $this->attendance[$student->id] = 'absent';
        }
    }

    #[Computed]
    public function filteredStudents()
    {
        return $this->students;
    }

    public function getTitle(): string|Htmlable
    {
        return __('common.create_attendance');
    }

public function submit()
{
    $data = $this->form->getState();
    $attendanceDate = Carbon::parse($data['date']);
    $teacherId = $data['teacher_id'];
    
    try {

        $existingAttendance = StudentAttendance::where('attendance_date', $attendanceDate)->first();

        if ($existingAttendance) {
    
            $attendance = $existingAttendance;
            StudentAttendanceDetails::where('student_attendance_id', $attendance->id)
                ->where('teacher_id', $teacherId)
                ->delete();
        } else {

            $attendance = StudentAttendance::create([
                'attendance_date' => $attendanceDate,
                'month' => $attendanceDate->month,
                'year' => $attendanceDate->year,
                'created_by' => Auth::id(),
                'notes' => 'Attendance taken for teacher ' . $teacherId,
            ]);
        }
        
        // Create attendance details
        foreach ($this->attendance as $studentId => $status) {
            StudentAttendanceDetails::create([
                'student_attendance_id' => $attendance->id,
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'status' => $status === 'present' ? 1 : 0,
                'notes' => $status === 'present' ? 'Present' : 'Absent',
            ]);
        }

      
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Attendance saved successfully'),
        ]);

        // Redirect after save
        return redirect()->to(StudentAttendanceResource::getUrl());

    } catch (\Exception $e) {
        $this->dispatch('notify', [
            'type' => 'danger',
            'message' => __('Error saving attendance: ') . $e->getMessage(),
        ]);
    }
}

    public function cancel()
    {
        return redirect()->to(StudentAttendanceResource::getUrl());
    }
}