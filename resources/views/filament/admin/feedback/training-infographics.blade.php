<x-filament-panels::page>
    <div class="space-y-8">
        {{-- Summary Metric Tiles --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 rounded-3xl shadow-xl text-white">
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest opacity-80">Training Category</p>
                <h3 class="text-2xl font-black mt-2 leading-tight">{{ $record->trainingType?->name }}</h3>
                <div class="mt-4 flex items-center gap-2 text-blue-100 text-sm font-bold">
                    <x-heroicon-o-calendar class="w-4 h-4"/>
                    {{ \Carbon\Carbon::parse($record->start_date)->format('M Y') }}
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-md">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Feedback Received</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $record->applications()->whereHas('feedback')->count() }}</h3>
                    <span class="text-gray-400 font-bold text-sm">/ {{ $record->applications->count() }} Total</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-md">
                <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest">Satisfaction Score</p>
                <div class="flex items-center gap-3 mt-2">
                    <h3 class="text-4xl font-black text-amber-500">★ {{ number_format($record->applications()->whereHas('feedback')->with('feedback')->get()->avg('feedback.overall_rating'), 1) }}</h3>
                    <span class="px-2 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg text-xs font-bold border border-amber-200 dark:border-amber-700">Out of 5</span>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-inner">
            <h4 class="text-sm font-bold mb-5 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                <x-heroicon-o-funnel class="w-4 h-4"/>
                Dynamic Search Filters
            </h4>
            {{ $this->form }}
        </div>

        {{-- Interactive Analytics Charts --}}
        <div class="grid grid-cols-1 gap-8">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-lg">
                <h4 class="text-lg font-black text-gray-900 dark:text-white mb-6 border-b dark:border-gray-700 pb-4">Parameter-wise Performance Analysis</h4>
                @livewire(\App\Filament\Admin\Resources\FeedbackResource\Widgets\CategoryRatingChart::class, [
                    'record' => $record,
                    'filters' => $this->data
                ])
            </div>
        </div>
    </div>
</x-filament-panels::page>