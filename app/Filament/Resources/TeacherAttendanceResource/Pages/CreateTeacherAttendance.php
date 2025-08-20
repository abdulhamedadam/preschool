<?php

namespace App\Filament\Resources\TeacherAttendanceResource\Pages;

use App\Filament\Resources\TeacherAttendanceResource;
use App\Models\Supervisor;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherAttendanceDetails;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CreateTeacherAttendance extends  Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = TeacherAttendanceResource::class;
    protected static string $view = 'filament.attendance.create_teacher_attendance';

    public ?array $data = [];
    public array $attendance = [];
    public $teachers = [];

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
                        Select::make('supervisor_id')
                            ->label(__('common.teacher'))
                            ->options(Supervisor::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn() => $this->loadTeachers())
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

    public function loadTeachers(): void
    {
        $supervisorId = $this->form->getState()['supervisor_id'] ?? null;

        if ($supervisorId) {
            $this->teachers = Teacher::whereHas('supervisors', function ($query) use ($supervisorId) {
                $query->where('supervisor_id', $supervisorId);
            })

                ->get();

            // Initialize all students as present by default
            $this->attendance = $this->teachers->mapWithKeys(fn($teacher) => [
                $teacher->id => 'present'
            ])->toArray();
        } else {
            $this->teachers = [];
            $this->attendance = [];
        }
    }

    public function markAllPresent(): void
    {
        foreach ($this->filteredTeachers as $teacher) {
            $this->attendance[$teacher->id] = 'present';
        }
    }

    public function markAllAbsent(): void
    {
        foreach ($this->filteredTeachers as $teacher) {
            $this->attendance[$teacher->id] = 'absent';
        }
    }

    #[Computed]
    public function filteredTeachers()
    {
        return $this->teachers;
    }

    public function getTitle(): string|Htmlable
    {
        return __('common.create_attendance');
    }

    public function submit()
    {
        $data = $this->form->getState();
        $attendanceDate = Carbon::parse($data['date']);
        $supervisorId = $data['supervisor_id'];
      //  dd($supervisorId);
        try {

            $existingAttendance = TeacherAttendance::where('attendance_date', $attendanceDate)->first();

            if ($existingAttendance) {

                $attendance = $existingAttendance;
                TeacherAttendanceDetails::where('teacher_attendance_id', $attendance->id)
                    ->where('supervisor_id', $supervisorId)
                    ->delete();
            } else {

                $attendance = TeacherAttendance::create([
                    'attendance_date' => $attendanceDate,
                    'month' => $attendanceDate->month,
                    'year' => $attendanceDate->year,
                    'created_by' => Auth::id(),
                    'notes' => 'Attendance taken for teacher ' . $supervisorId,
                ]);
            }

         
            foreach ($this->attendance as $teacherId => $status) {

                $datadetails['teacher_attendance_id']=$attendance->id;
                $datadetails['teacher_id']=$teacherId;
                $datadetails['supervisor_id']=$supervisorId;
                $datadetails['status'] = $status == 'present' ? 1 : 0;
                $datadetails['notes'] = $status == 'present' ? 'Present' : 'Absent';
                 //dd($datadetails);
                TeacherAttendanceDetails::create($datadetails);

             
            }


            $this->dispatch('notify', [
                'type' => 'success',
                'message' => __('Attendance saved successfully'),
            ]);

            // Redirect after save
            return redirect()->to(TeacherAttendanceResource::getUrl());
        } catch (\Exception $e) {

            $this->dispatch('notify', [
                'type' => 'danger',
                'message' => __('Error saving attendance: ') . $e->getMessage(),
            ]);
        }
    }

    public function cancel()
    {
        return redirect()->to(TeacherAttendanceResource::getUrl());
    }
}
