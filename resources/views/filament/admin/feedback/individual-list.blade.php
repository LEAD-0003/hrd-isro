<div class="p-2">
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-200 uppercase font-bold border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-4 py-3">S.No.</th>
                    <th class="px-4 py-3">Emp Code</th>
                    <th class="px-4 py-3">Employee Name</th>
                    <th class="px-4 py-3">Centre</th>
                    <th class="px-4 py-3 text-center">Rating</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-gray-900">
                @forelse($applications as $index => $app)
                <tr class="transition-colors">
                    <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-gray-600 dark:text-gray-400">
                        {{ $app->user?->employee_code ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white uppercase tracking-tight">
                        {{ $app->user?->name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                        {{ $app->user?->centre?->name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-filament::badge color="warning" icon="heroicon-m-star">
                            {{ $app->feedback?->overall_rating ?? '0' }}
                        </x-filament::badge>
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{-- FIX: Modal open aaga Correct trigger --}}
                        <x-filament::button 
                            wire:click="mountTableAction('view_feedback_form', '{{ $app->id }}')"
                            size="sm"
                            color="info"
                            outlined
                        >
                            View Form
                        </x-filament::button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800/20 font-medium">
                        No feedback submissions found for this training.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>