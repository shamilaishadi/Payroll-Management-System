<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Records') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label for="from_date" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}"
                        class="border rounded-md p-2">
                </div>

                <div>
                    <label for="to_date" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                        class="border rounded-md p-2">
                </div>

                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Name or Email" class="border rounded-md p-2">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filter
                </button>
            </form>

            @if ($attendances->isEmpty())
                <p class="text-gray-600">No attendance records found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">User</th>
                                <th class="px-4 py-2 border">Email</th>
                                <th class="px-4 py-2 border">Sign In Time</th>
                                <th class="px-4 py-2 border">Sign In Status</th>
                                <th class="px-4 py-2 border">Sign Out Time</th>
                                <th class="px-4 py-2 border">Sign Out Status</th>
                                <th class="px-4 py-2 border">Worked</th>
                                <th class="px-4 py-2 border">Overtime</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $attendance->user->name }}</td>
                                    <td class="px-4 py-2 border">{{ $attendance->user->email }}</td>
                                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($attendance->sign_in_time)->format('d/m/Y h:i A') }}</td>
                                    <td class="px-4 py-2 border capitalize">{{ $attendance->sign_in_status ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $attendance->sign_out_time ? \Carbon\Carbon::parse($attendance->sign_out_time)->format('d/m/Y h:i A') : '—' }}
                                    </td>
                                    <td class="px-4 py-2 border capitalize">{{ $attendance->sign_out_status ?? '—' }}</td>
                                    <td class="px-4 py-2 border">
                                        @if ($attendance->worked_minutes)
                                            {{ floor($attendance->worked_minutes / 60) }}h {{ $attendance->worked_minutes % 60 }}m
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if ($attendance->overtime_minutes)
                                            {{ floor($attendance->overtime_minutes / 60) }}h {{ $attendance->overtime_minutes % 60 }}m
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
