<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Work Schedule: {{ $schedule->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('work-schedules.update', $schedule->id) }}">
                @csrf
                @method('PUT')

                {{-- Schedule Name --}}
                <div class="mb-6">
                    <label for="name" class="block font-medium mb-1">Schedule Name</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $schedule->name) }}"
                           class="w-full border rounded p-2 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Work Days Form --}}
                @php
                    $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                @endphp

                @foreach ($dayNames as $day)
                    @php
                        $start = old("days.$day.start_time", isset($days[$day]['start_time']) ? \Carbon\Carbon::createFromFormat('H:i:s', $days[$day]['start_time'])->format('H:i') : '');
                        $end = old("days.$day.end_time", isset($days[$day]['end_time']) ? \Carbon\Carbon::createFromFormat('H:i:s', $days[$day]['end_time'])->format('H:i') : '');
                    @endphp


                    <div class="mb-5 border-b pb-4">
                        <label class="block font-semibold mb-2 text-lg">{{ $day }}</label>

                        <div class="flex flex-col sm:flex-row gap-4">
                            {{-- Start Time --}}
                            <div class="w-full">
                                <label class="block text-sm mb-1">Start Time</label>
                                <input type="time" name="days[{{ $day }}][start_time]"
                                       value="{{ $start }}"
                                       class="w-full border rounded p-2 @error("days.$day.start_time") border-red-500 @enderror">
                                @error("days.$day.start_time")
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- End Time --}}
                            <div class="w-full">
                                <label class="block text-sm mb-1">End Time</label>
                                <input type="time" name="days[{{ $day }}][end_time]"
                                       value="{{ $end }}"
                                       class="w-full border rounded p-2 @error("days.$day.end_time") border-red-500 @enderror">
                                @error("days.$day.end_time")
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Submit --}}
                <div class="text-right mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                        Update Schedule
                    </button>
                    <a href="{{ route('work-schedules.index') }}" class="ml-4 text-gray-600 hover:underline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
