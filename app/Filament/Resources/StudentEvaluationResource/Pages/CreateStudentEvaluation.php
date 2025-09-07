<?php

namespace App\Filament\Resources\StudentEvaluationResource\Pages;

use App\Filament\Resources\StudentEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Students;
use App\Models\Subjects;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use App\Models\StudentEvaluation;
use App\Models\StudentEvaluationDetail;
use App\Models\StudentEvaluationDetails;

class CreateStudentEvaluation extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = StudentEvaluationResource::class;
    protected static string $view = 'filament.evaluations.create_student_evaluation';
    public ?array $data = [];
    public array $subjects = [];
    public $students = [];
    public $evaluations = [];
    public $currentYear;

    public function mount(): void
    {
        $this->currentYear = now()->year;

        $this->form->fill([
            'month' => now()->month,
            'year' => $this->currentYear,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('teacher_id')
                            ->label(__('common.teacher'))
                            ->options(Teacher::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn() => $this->loadStudents())
                            ->columnSpan(1),

                        Select::make('month')
                            ->label(__('common.month'))
                            ->options([
                                1 => __('common.january'),
                                2 => __('common.february'),
                                3 => __('common.march'),
                                4 => __('common.april'),
                                5 => __('common.may'),
                                6 => __('common.june'),
                                7 => __('common.july'),
                                8 => __('common.august'),
                                9 => __('common.september'),
                                10 => __('common.october'),
                                11 => __('common.november'),
                                12 => __('common.december'),
                            ])
                            ->required()
                            ->default(now()->month)
                            ->searchable()
                            ->columnSpan(1),

                        Hidden::make('year')
                            ->default(now()->year),
                    ]),
            ])
            ->statePath('data');
    }

    public function loadStudents(): void
    {
        $teacherId = $this->form->getState()['teacher_id'] ?? null;

        if ($teacherId) {
            $this->students = Students::whereHas('teachers', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
                ->with('classRoom', 'guardian')
                ->get();

            $this->subjects = Subjects::all()->toArray();

            // تهيئة مصفوفة التقييمات
            foreach ($this->students as $student) {
                foreach ($this->subjects as $subject) {
                    $this->evaluations[$student->id][$subject['id']] = [
                        'grade' => '',
                        'evaluation' => '',
                        'notes' => ''
                    ];
                }
            }
        } else {
            $this->students = [];
            $this->subjects = [];
            $this->evaluations = [];
        }
    }

    public function submit(): void
    {
        if (empty($this->students)) {
            Notification::make()
                ->title('خطأ')
                ->body('يرجى اختيار مدرس أولاً')
                ->danger()
                ->send();
            return;
        }

        $formData = $this->form->getState();
        $teacherId = $formData['teacher_id'];
        $month = $formData['month'];
        $year = $formData['year'] ?? $this->currentYear;

       
        $existingEvaluation = StudentEvaluation::where('created_by', Auth::id())
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($existingEvaluation) {
            
            $evaluation = $existingEvaluation;

            Notification::make()
                ->title('تم التحديث')
                ->body('تم تحديث تقييم الطلاب الموجود مسبقاً')
                ->success()
                ->send();
        } else {
            
            $evaluation = StudentEvaluation::create([
                'month' => $month,
                'year' => $year,
               // 'created_by' => Auth::id(),
            ]);

            Notification::make()
                ->title('تم الحفظ بنجاح')
                ->body('تم حفظ تقييم الطلاب بنجاح')
                ->success()
                ->send();
        }

        // حفظ أو تحديث تفاصيل التقييم
        foreach ($this->evaluations as $studentId => $subjects) {
            foreach ($subjects as $subjectId => $data) {
                if (!empty($data['grade']) || !empty($data['evaluation']) || !empty($data['notes'])) {

                    // التحقق من وجود تفاصيل التقييم لهذا الطالب والمادة مسبقاً
                    $existingDetail = StudentEvaluationDetails::where('student_evaluation_id', $evaluation->id)
                        ->where('student_id', $studentId)
                        ->where('subject_id', $subjectId)
                        ->first();

                    if ($existingDetail) {
                        // إذا كانت التفاصيل موجودة، نقوم بعمل update
                        $existingDetail->update([
                            'grade' => $data['grade'] ?? null,
                            'evaluation' => $data['evaluation'] ?? null,
                            'notes' => $data['notes'] ?? null,
                            'created_by' => Auth::id(),
                            'date' => now()->format('Y-m-d'),
                        ]);
                    } else {
                        // إذا لم تكن التفاصيل موجودة، نقوم بعمل create
                        StudentEvaluationDetails::create([
                            'student_evaluation_id' => $evaluation->id,
                            'student_id' => $studentId,
                            'subject_id' => $subjectId,
                            'grade' => $data['grade'] ?? null,
                            'evaluation' => $data['evaluation'] ?? null,
                            'notes' => $data['notes'] ?? null,
                            'created_by' => Auth::id(),
                            'date' => now()->format('Y-m-d'),
                        ]);
                    }
                } else {
                    // إذا كانت الحقول فارغة ونريد حذف التقييم إن كان موجوداً
                    $existingDetail = StudentEvaluationDetails::where('student_evaluation_id', $evaluation->id)
                        ->where('student_id', $studentId)
                        ->where('subject_id', $subjectId)
                        ->first();

                    if ($existingDetail) {
                        $existingDetail->delete();
                    }
                }
            }
        }

        $this->redirect(StudentEvaluationResource::getUrl('index'));
    }

    public function cancel(): void
    {
        $this->redirect(StudentEvaluationResource::getUrl('index'));
    }
}
