<x-filament-panels::page>
    @php $stats = $this->getStats(); @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase">Total Training</p>
            <p class="text-4xl font-bold text-sky-600 mt-2">{{ $stats['total_training'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase">No of Employee Applied</p>
            <p class="text-4xl font-bold text-sky-600 mt-2">{{ $stats['applied_count'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase">No of Employee Approved</p>
            <p class="text-4xl font-bold text-sky-600 mt-2">{{ $stats['approved_count'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center">
            <p class="text-sm font-medium text-gray-500 uppercase">No of Employee Completed</p>
            <p class="text-4xl font-bold text-sky-600 mt-2">{{ $stats['completed_count'] }}</p>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700 uppercase">Current Training Modules</h3>
            <a href="{{ route('filament.admin.resources.trainings.create') }}" class="bg-sky-500 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-sky-600">Add Course</a>
        </div>
        
        @livewire(\App\Filament\Admin\Widgets\TrainingOverview::class)
    </div>
</x-filament-panels::page>