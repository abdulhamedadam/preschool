<x-filament::page>
    <div class="space-y-6">
        {{-- Form Section --}}
        <x-filament::card class="rounded-xl shadow-sm">
            {{ $this->form }}
        </x-filament::card>

        @if(count($students) > 0)
        
        <x-filament::card class="rounded-xl shadow-sm overflow-hidden p-0">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('common.student_evaluation') }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('common.evaluation_instructions') }}
                </p>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 border-collapse border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th rowspan="2" scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700">
                                {{ __('common.student') }}
                            </th>
                            @foreach($subjects as $subject)
                            <th colspan="3" scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700" style="background:lightgray;">
                                {{ $subject['name'] }}
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($subjects as $subject)
                            <th scope="col" class="px-2 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700" style="background:#ffcccc;">
                                {{ __('common.grade') }}
                            </th>
                            <th scope="col" class="px-2 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700" style="background:#ffcccc;">
                                {{ __('common.evaluation') }}
                            </th>
                            <th scope="col" class="px-2 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 w-64" style="background:#ffcccc;">
                                {{ __('common.note') }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($students as $student)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap border border-gray-300 dark:border-gray-600">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&color=7F9CF5&background=EBF4FF' }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->name }}</div>
                                        
                                    </div>
                                </div>
                            </td>

                            @foreach($subjects as $subject)
                            <td class="px-2 py-4 whitespace-nowrap border border-gray-300 dark:border-gray-600">
                                <input
                                    type="number"
                                    min="0"
                                    style="width: 150px;"
                                    max="10"
                                    wire:model="evaluations.{{ $student->id }}.{{ $subject['id'] }}.grade"
                                    class="w-full h-9 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="0-10">
                            </td>
                            <td class="px-2 py-4 whitespace-nowrap border border-gray-300 dark:border-gray-600">
                                <select
                                    wire:model="evaluations.{{ $student->id }}.{{ $subject['id'] }}.evaluation" style="width: 150px;"
                                    class="w-full h-9 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">{{ __('common.select_evaluation') }}</option>
                                    <option value="excellent">{{ __('common.excellent') }}</option>
                                    <option value="very_good">{{ __('common.very_good') }}</option>
                                    <option value="good">{{ __('common.good') }}</option>
                                    <option value="acceptable">{{ __('common.acceptable') }}</option>
                                    <option value="weak">{{ __('common.weak') }}</option>
                                </select>
                            </td>
                            <td class="px-2 py-4 border border-gray-300 dark:border-gray-600 w-64">
                                <textarea
                                    wire:model="evaluations.{{ $student->id }}.{{ $subject['id'] }}.notes"
                                    style="width: 250px;"
                                    rows="2"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"></textarea>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::card>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-between gap-x-3">
            <div class="flex items-center gap-2">
                <x-filament::button
                    color="gray"
                    wire:click="cancel"
                    icon="heroicon-o-arrow-left">
                    {{ __('common.cancel') }}
                </x-filament::button>

                <x-filament::button
                    wire:click="submit"
                    type="button"
                    icon="heroicon-o-check"
                    class="shadow-sm"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('common.save_evaluation') }}</span>
                    <span wire:loading>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('common.saving') }}
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
                    {{ __('common.no_students_found') }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('common.select_teacher_to_view_students') }}
                </p>
            </div>
        </x-filament::card>
        @endif
    </div>

    <style>
        /* تنسيقات مخصصة لتحسين المظهر */
        table {
            border-collapse: collapse;
            border: 2px solid #e5e7eb;
        }

        table th,
        table td {
            vertical-align: middle;
            border: 1px solid #e5e7eb;
        }

        .dark table th,
        .dark table td {
            border-color: #4b5563;
        }

        input,
        select,
        textarea {
            transition: all 0.2s ease-in-out;
            border: 1px solid #d1d5db;
        }

        input:focus,
        select:focus,
        textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .dark input,
        .dark select,
        .dark textarea {
            border-color: #4b5563;
        }

        /* تحسين مظهر التمرير الأفقي */
        .overflow-x-auto {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .dark .overflow-x-auto {
            scrollbar-color: #4b5563 #1f2937;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        /* تنسيق للأجهزة المحمولة */
        @media (max-width: 1024px) {
            .overflow-x-auto {
                overflow-x: auto;
            }

            table {
                min-width: 1000px;
            }

            /* جعل الحقول أسهل في اللمس على الأجهزة المحمولة */
            input,
            select,
            textarea {
                min-height: 44px;
                font-size: 16px;
                /* منع التكبير التلقائي في iOS */
            }
        }

        /* تحسين مظهر الخلايا ذات الحدود */
        .border-gray-300 {
            border-color: #e5e7eb;
        }

        .dark .border-gray-600 {
            border-color: #4b5563;
        }

        /* تظليل خفيف للرؤوس */
        .bg-gray-100 {
            background-color: #f9fafb;
        }

        .dark .bg-gray-700 {
            background-color: #374151;
        }

        /* تحسين مظهر النص في الوضع الداكن */
        .dark .text-gray-400 {
            color: #9ca3af;
        }

        .dark .text-gray-500 {
            color: #6b7280;
        }
    </style>
</x-filament::page>