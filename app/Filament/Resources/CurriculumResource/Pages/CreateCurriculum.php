<?php

namespace App\Filament\Resources\CurriculumResource\Pages;

use App\Filament\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Curriculum;
use App\Models\CurriculumDetail;
use App\Models\CurriculumDetails;

class CreateCurriculum extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = CurriculumResource::class;
    protected static string $view = 'filament.curriculum.curriculum_form';
    public $record;
    public $month_id = null;
    public $subject_id = null;
    public $content = '';
    public $files = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('month_id')
                            ->label(__('common.study_month'))
                            ->options(\App\Models\StudyMonthes::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->columnSpan(1),
                        Select::make('subject_id')
                            ->label(__('common.subject'))
                            ->options(\App\Models\Subjects::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->columnSpan(1),
                        RichEditor::make('content')
                            ->label(__('common.content'))
                            ->required()
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('files')
                            ->label(__('common.files'))
                            ->collection('curriculum_files')
                            ->multiple()
                            ->downloadable()
                            ->openable()
                            ->preserveFilenames()
                            ->model(CurriculumDetails::class)
                            ->columnSpanFull(),
                    ]),
            ]);
    }




    public function save()
    {

        $curriculum = Curriculum::create([
            'month_id' => $this->month_id,
            'name'     => \App\Models\StudyMonthes::find($this->month_id)?->name, // اسم الشهر
            'date'     => now()->toDateString(),
        ]);


        $detail = CurriculumDetails::create([
            'curriculum_id' => $curriculum->id,
            'subject_id'    => $this->subject_id,
            'content'       => $this->content,
            'created_by'    => Auth::id(),
        ]);





        return redirect()->route('filament.admin.resources.curricula.create',$this->record);
    }
}
