<div class="p-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800">
    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-8 border-b border-gray-100 dark:border-gray-800 pb-4">
        ISRO Centre-wise Distribution
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($training->applications()->whereHas('feedback')->with(['user.centre', 'feedback'])->get()->groupBy('user.centreRel.name') as $centreName => $apps)
            <div
                class="p-5 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:border-blue-500 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1">
                            {{ $centreName }}</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $apps->count() }}</p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase">Total Responses</p>
                    </div>
                    <div class="bg-amber-500/10 p-2 rounded-xl border border-amber-500/20">
                        <span class="text-amber-600 dark:text-amber-400 font-black text-lg">★
                            {{ number_format($apps->avg('feedback.overall_rating'), 1) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-full p-12 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 font-medium italic">
                No centre data available for this training.
            </div>
        @endforelse
    </div>
</div>
