<x-filament::page>
    <div class="space-y-6">
        {{-- Form Section --}}
        <x-filament::card class="rounded-xl shadow-sm">
            {{ $this->form }}
        </x-filament::card>

        @if(count($this->filteredTeachers) > 0)
            {{-- Bulk Actions --}}
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <x-filament::button 
                        size="sm"
                        color="success"
                        wire:click="markAllPresent"
                        icon="heroicon-o-check-circle"
                        class="shadow-sm"
                    >
                        {{ __('common.Mark All Present') }}
                    </x-filament::button>
                    
                    <x-filament::button 
                        size="sm"
                        color="danger"
                        wire:click="markAllAbsent"
                        icon="heroicon-o-x-mark"
                        class="shadow-sm"
                    >
                        {{ __('common.Mark All Absent') }}
                    </x-filament::button>
                </div>
                
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($this->filteredTeachers) }} {{ __('common.students found') }}
                </div>
            </div>

            {{-- Students Table --}}
            <x-filament::card class="rounded-xl shadow-sm overflow-hidden">
                <div class="relative overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('common.teacher') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('common.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($this->filteredTeachers as $teacher)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $teacher->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($teacher->name).'&color=7F9CF5&background=EBF4FF' }}" alt="">
                                            </div>
                                            <div class="ml-4 text-left">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ @$teacher->name }}</div>
                                              
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        <div class="flex items-center justify-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input 
                                                    type="radio" 
                                                    name="attendance[{{ $teacher->id }}]" 
                                                    value="present" 
                                                    wire:model="attendance.{{ $teacher->id }}"
                                                    class="h-4 w-4 text-success-600 dark:text-success-500 focus:ring-success-500 border-gray-300 dark:border-gray-600 rounded"
                                                >
                                                <span class="ml-2 dark:text-gray-300">{{ __('common.present') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input 
                                                    type="radio" 
                                                    name="attendance[{{ $teacher->id }}]" 
                                                    value="absent" 
                                                    wire:model="attendance.{{ $teacher->id }}"
                                                    class="h-4 w-4 text-danger-600 dark:text-danger-500 focus:ring-danger-500 border-gray-300 dark:border-gray-600 rounded"
                                                >
                                                <span class="ml-2 dark:text-gray-300">{{ __('common.absent') }}</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::card>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between gap-x-3">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    @php
                        $presentCount = count(array_filter($this->attendance ?? [], fn($status) => $status === 'present'));
                        $absentCount = count($this->filteredTeachers) - $presentCount;
                    @endphp
                    {{ __('common.Attendance Summary:') }}
                    <span class="text-success-600 dark:text-success-400 font-medium">{{ $presentCount }} {{ __('common.Present') }}</span>,
                    <span class="text-danger-600 dark:text-danger-400 font-medium">{{ $absentCount }} {{ __('common.Absent') }}</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <x-filament::button 
                        color="gray" 
                        wire:click="cancel"
                        icon="heroicon-o-arrow-left"
                    >
                        {{ __('common.cancel') }}
                    </x-filament::button>
                    
                    <x-filament::button 
                        wire:click="submit" 
                        type="button" 
                        icon="heroicon-o-check"
                        class="shadow-sm"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>{{ __('common.save_attendance') }}</span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('common.Saving...') }}
                        </span>
                    </x-filament::button>
                </div>
            </div>
        @else
            <x-filament::card class="rounded-xl shadow-sm">
                <div class="p-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('common.No students found') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('common.Please select a teacher to view their students') }}
                    </p>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament::page>