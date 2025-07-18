<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Work Schedule</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded p-6">
            <form method="POST" action="{{ route('work-schedules.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium mb-1">Schedule Name</label>
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>

                <h3 class="font-semibold text-lg mb-2">Daily Times</h3>

                @php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                @endphp

                @foreach ($days as $index => $day)
                    <div class="mb-3">
                        <label class="block font-medium">{{ $day }}</label>
                        <div class="flex items-center space-x-2">
                            <label>Start:</label>
                            <input type="time" name="days[{{ $index }}][start]" class="border rounded p-1">
                            <label>End:</label>
                            <input type="time" name="days[{{ $index }}][end]" class="border rounded p-1">
                        </div>
                    </div>
                @endforeach

                <div class="mt-6 flex justify-end space-x-2">
                    <a href="{{ route('work-schedules.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
