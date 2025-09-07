<div class="space-y-6">
    <!-- معلومات التقييم الأساسية -->
 <div class="flex flex-wrap items-center justify-between gap-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
    <div class="flex items-center gap-8">

        <!-- الشهر -->
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('common.month') }}</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                @php
                    $months = [
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
                    ];
                    echo $months[$evaluation->month] ?? $evaluation->month;
                @endphp
            </p>
        </div>
        
        <!-- السنة -->
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('common.year') }}</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $evaluation->year }}</p>
        </div>
        
        <!-- عدد الطلاب -->
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('common.total_students') }}</h3>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $evaluation->evaluationDetails->groupBy('student_id')->count() }}</p>
        </div>
    </div>
</div>
    <!-- جدول تفاصيل التقييم -->
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 border-collapse border border-gray-300 dark:border-gray-600">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border border-gray-300 dark:border-gray-600">
                        {{ __('common.student') }}
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border border-gray-300 dark:border-gray-600">
                        {{ __('common.subject') }}
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border border-gray-300 dark:border-gray-600">
                        {{ __('common.grade') }}
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border border-gray-300 dark:border-gray-600">
                        {{ __('common.evaluation') }}
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase border border-gray-300 dark:border-gray-600">
                        {{ __('common.notes') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($evaluation->evaluationDetails->groupBy('student_id') as $studentId => $studentEvaluations)
                    @php
                        $student = $studentEvaluations->first()->student;
                        $rowspan = count($studentEvaluations);
                    @endphp
                    
                    @foreach($studentEvaluations as $index => $detail)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            @if($index === 0)
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 align-top" rowspan="{{ $rowspan }}">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                           <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->name).'&color=7F9CF5&background=EBF4FF' }}" alt="">
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ @$student->name }}</div>

                                        </div>
                                    </div>
                                </td>
                            @endif
                            
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ @$detail->subject->name ?? 'N/A' }}
                            </td>
                            
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ @$detail->grade }}
                                </span>
                            </td>
                            
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                @php
                                    $evaluationLabels = [
                                        'excellent' => __('common.excellent'),
                                        'very_good' => __('common.very_good'),
                                        'good' => __('common.good'),
                                        'acceptable' => __('common.acceptable'),
                                        'weak' => __('common.weak'),
                                    ];
                                    $colorClasses = [
                                        'excellent' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'very_good' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'good' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'acceptable' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                                        'weak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClasses[$detail->evaluation] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                                    {{ $evaluationLabels[$detail->evaluation] ?? $detail->evaluation }}
                                </span>
                            </td>
                            
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ @$detail->notes }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>