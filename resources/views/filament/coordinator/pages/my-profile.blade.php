<x-filament-panels::page>
    @if (! $isEditing)
        {{ $this->profileInfolist }}
    @else
        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament-panels::form.actions 
                    :actions="$this->getFormActions()" 
                />
            </div>
        </form>
    @endif
</x-filament-panels::page>