<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Work Schedules</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('work-schedules.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Create New Schedule
            </a>
        </div>

        <div class="bg-white shadow-md rounded p-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">Schedule Name</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $schedule->name }}</td>
                            <td class="px-4 py-2 border flex space-x-2">
                                <a href="{{ route('work-schedules.edit', $schedule) }}" class="text-blue-600 hover:underline">Edit</a>
                                {{-- <a href="{{ route('work-schedules.assign', $schedule) }}" class="text-green-600 hover:underline">Assign to Users</a>
                                <form method="POST" action="{{ route('work-schedules.destroy', $schedule) }}" onsubmit="return confirm('Delete this schedule?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">No schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
