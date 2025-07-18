<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">My Leave Requests</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-4">
        @if(session('success'))
            <div class="bg-green-200 p-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('leaves.store') }}" class="bg-white p-4 rounded shadow mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label>End Date</label>
                    <input type="date" name="end_date" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label>Reason</label>
                    <input type="text" name="reason" class="w-full border p-2 rounded">
                </div>
            </div>
            <div class="mt-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit Request</button>
            </div>
        </form>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Leave History</h3>
            <table class="w-full table-auto text-left">
                <thead>
                    <tr class="border-b">
                        <th>Date Range</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                        <tr class="border-b hover:bg-gray-100">
                            <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td class="capitalize text-sm {{
                                $leave->status == 'approved' ? 'text-green-600' :
                                ($leave->status == 'rejected' ? 'text-red-600' : 'text-yellow-600')
                            }}">{{ $leave->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>