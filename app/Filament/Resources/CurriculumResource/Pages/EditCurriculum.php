<?php

namespace App\Filament\Resources\CurriculumResource\Pages;

use App\Filament\Resources\CurriculumResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Facades\Auth;
use App\Models\Curriculum;
use App\Models\CurriculumDetails;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;

class EditCurriculum extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string $resource = CurriculumResource::class;
    protected static string $view = 'filament.curriculum.curriculum_form';
    public $record;
    public $month_id = null;
    public $subject_id = null;
    public $content = '';
    public $files = [];

    public function mount($record)
    {
        $this->record = $record;
        $this->month_id = $record;
    }

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
                            ->default($this->month_id)
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

    protected function getTableQuery(): Builder
    {
        return CurriculumDetails::where('curriculum_id', $this->record)
            ->with(['subject', 'creator']);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('subject.name')
                ->label(__('common.subject'))
                ->sortable(),
            TextColumn::make('content')
                ->label(__('common.content'))
                ->limit(50)
                ->html()
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) > 50) {
                        return strip_tags($state);
                    }
                    return null;
                }),
        TextColumn::make('files_list')
            ->label(__('common.files'))
            ->formatStateUsing(function ($record) {
                $files = $record->getMedia('curriculum_files');
                if ($files->count() > 0) {
                    $html = '<div class="space-y-1">';
                    foreach ($files as $file) {
                        $html .= '<div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <a href="' . $file->getUrl() . '" download class="text-primary-600 hover:text-primary-800 text-sm" wire:click.stop>
                                ' . $file->name . '
                            </a>
                            <span class="text-xs text-gray-500">(' . $this->formatFileSize($file->size) . ')</span>
                        </div>';
                    }
                    $html .= '</div>';
                    return $html;
                }
                return __('common.no_files');
            })
            ->html(),
            TextColumn::make('creator.name')
                ->label(__('common.created_by'))
                ->sortable(),
            TextColumn::make('created_at')
                ->label(__('common.created_at'))
                ->dateTime()
                ->sortable(),
        ];
    }


    private function formatFileSize($bytes)
    {
        if ($bytes == 0) return '0 B';
        $k = 1024;
        $sizes = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
    protected function getTableActions(): array
    {
        return [
            // Action::make('view')
            //     ->label(__('common.view'))
            //     ->icon('heroicon-o-eye')
            //     ->url(fn (CurriculumDetails $record): string => '#') // You can update this later
            //     ->openUrlInNewTab(),
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading(__('common.delete_content'))
                ->modalDescription(__('common.are_you_sure_delete_content'))
                ->modalSubmitActionLabel(__('common.yes_delete')),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label(__('common.add_content'))
                ->url(route('filament.admin.resources.curricula.edit', $this->record))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->actions($this->getTableActions())
            // ->headerActions($this->getTableHeaderActions())
            ->emptyStateHeading(__('common.no_content_yet'))
            ->emptyStateDescription(__('common.start_adding_content'))
            ->emptyStateIcon('heroicon-o-document');
    }

    public function save()
    {
        $this->validate([
            'month_id' => 'required',
            'subject_id' => 'required',
            'content' => 'required',
        ]);

        // Find or create curriculum
        $curriculum = Curriculum::firstOrCreate(
            ['id' => $this->record],
            [
                'month_id' => $this->month_id,
                'name' => \App\Models\StudyMonthes::find($this->month_id)?->name,
                'date' => now()->toDateString(),
            ]
        );

        // Create curriculum detail
        $detail = CurriculumDetails::create([
            'curriculum_id' => $curriculum->id,
            'subject_id' => $this->subject_id,
            'content' => $this->content,
            'created_by' => Auth::id(),
        ]);

        // Handle file uploads if any
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $detail->addMedia($file)->toMediaCollection('curriculum_files');
            }
        }

        // Clear form fields
        $this->subject_id = null;
        $this->content = '';
        $this->files = [];

        // Show success message
        return redirect()->route('filament.admin.resources.curricula.edit', $this->record);
        $this->notify('success', __('common.content_added_successfully'));
    }
}
