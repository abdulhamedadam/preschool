<x-filament::page>
    <div class="space-y-6">
        <x-filament::card class="rounded-xl shadow-sm">
            <h2 class="text-lg font-medium mb-4">{{ __('common.add_new_content') }}</h2>
            {{ $this->form }}
            
            <div class="mt-4">
                <x-filament::button wire:click="save" form="saveForm">
                    {{ __('common.save') }}
                </x-filament::button>
            </div>
        </x-filament::card>

        <x-filament::card class="rounded-xl shadow-sm">
   
            {{ $this->table }}
        </x-filament::card>
    </div>
</x-filament::page>