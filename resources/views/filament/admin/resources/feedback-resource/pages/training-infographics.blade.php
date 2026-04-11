<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-5 rounded-xl shadow-lg text-white">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Target Group</p>
                <h3 class="text-xl font-black mt-1">{{ $record->trainingType?->name }}</h3>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase">Response Rate</p>
                <div class="flex items-end gap-2 mt-1">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">
                        {{ $record->applications()->whereHas('feedback')->count() }}
                    </h3>
                    <span class="text-gray-400 text-sm mb-1">/ {{ $record->applications->count() }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase">Avg. Rating</p>
                <h3 class="text-2xl font-black text-warning-500 mt-1">
                    ★ {{ number_format($record->applications()->whereHas('feedback')->with('feedback')->get()->avg('feedback.overall_rating'), 1) }}
                </h3>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-900/40 p-6 rounded-xl border border-gray-200 dark:border-gray-800">
            <h4 class="text-sm font-bold mb-4 text-gray-700 dark:text-gray-300">Refine Analytics</h4>
            {{ $this->form }}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @livewire(\App\Filament\Admin\Resources\FeedbackResource\Widgets\CategoryRatingChart::class, [
                'record' => $record,
                'filters' => $this->data
            ])
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="font-bold text-gray-900 dark:text-white mb-6">Program Expectations</h4>
                </div>
        </div>
    </div>
</x-filament-panels::page>