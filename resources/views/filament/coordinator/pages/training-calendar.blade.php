<x-filament-panels::page>
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg border-2 border-gray-400 dark:border-gray-700 overflow-hidden">
        
        <div class="p-4 flex justify-between items-center bg-gray-100 dark:bg-gray-800 border-b-2 border-gray-400 dark:border-gray-700">
            <h2 class="text-2xl font-black text-sky-900 dark:text-sky-400 uppercase tracking-tighter">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
            </h2>
            <form method="GET" class="flex gap-2">
                <select name="month" onchange="this.form.submit()" class="rounded-lg border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-bold text-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="rounded-lg border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-white font-bold text-sm">
                    @foreach(range(now()->year - 1, now()->year + 2) as $y)
                        <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-7 bg-[#4fa3f7] dark:bg-sky-700 border-b-2 border-gray-400 dark:border-gray-700">
            @foreach(['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY'] as $day)
                <div class="py-3 text-center text-xs font-black text-blue-900 dark:text-sky-100 tracking-tighter">{{ $day }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 bg-gray-400 dark:bg-gray-700 gap-[2px]">
            @foreach($this->getCalendarDays() as $day)
                <div class="relative min-h-[140px] {{ $day['isCurrentMonth'] ? 'bg-[#e5e7eb] dark:bg-gray-800' : 'bg-[#d1d5db] dark:bg-gray-900' }} border border-gray-300 dark:border-gray-700" style="aspect-ratio: 1 / 1;">
                    
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full text-sm font-black shadow-inner 
                            {{ $day['date']->isToday() ? 'bg-sky-600 text-white' : ($day['isCurrentMonth'] ? 'bg-[#93c5fd] dark:bg-sky-900 text-white' : 'bg-gray-400 dark:bg-gray-700 text-white opacity-40') }}">
                            {{ $day['date']->format('j') }}
                        </span>
                    </div>

                    <div class="mt-15 px-2 space-y-1 overflow-y-auto max-h-[calc(100%-50px)]">
                        @foreach($day['trainings'] as $training)
                           <br> 
                           <button 
                                wire:click="mountAction('viewTraining', { record: {{ $training->id }} })"
                                class="w-full text-left px-2 py-1.5 text-[11px] font-black leading-tight rounded bg-white dark:bg-gray-700 border-l-4 border-sky-600 shadow-md text-sky-900 dark:text-sky-300 hover:bg-sky-100 dark:hover:bg-gray-600 transition truncate uppercase"
                            >
                                {{ $training->title }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-filament-actions::modals />
</x-filament-panels::page>