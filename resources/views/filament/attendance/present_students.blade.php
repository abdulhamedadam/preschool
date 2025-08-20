<div class="p-4">
    <h3 class="text-lg font-medium mb-4">{{ __('common.present_students') }}</h3>

    <div class="space-y-2">
        @forelse($students as $detail)
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ @$detail->student->name }}</p>
                    <p class="text-sm text-gray-500">{{ @$detail->teacher->name }}</p>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">{{ __('common.present') }}</span>
            </div>
        @empty
            <p class="text-gray-500">{{ __('common.No students found') }}</p>
        @endforelse
    </div>
</div>