<div class="p-4">
    <h3 class="text-lg font-medium mb-4">{{ __('common.absent_students') }}</h3>

    <div class="space-y-2">
        @forelse($teachers as $detail)
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ @$detail->teacher->name  }}</p>
                    <p class="text-sm text-gray-500">{{ @$detail->supervisor->name }}</p>
                </div>
                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Absent</span>
            </div>
        @empty
            <p class="text-gray-500">لا يوجد طلاب غائبون في هذا التاريخ.</p>
        @endforelse
    </div>
</div>