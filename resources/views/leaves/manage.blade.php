<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Manage Leave Requests</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-4">
        <form method="GET" class="flex flex-wrap gap-4 mb-6">
            <select name="user_id" class="border rounded p-2 min-w-[180px]">
                <option value="">-- All Users --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>

            <select name="status" class="border rounded p-2 min-w-[150px]">
                <option value="">-- All Statuses --</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <input type="date" name="date" value="{{ request('date') }}" class="border rounded p-2 min-w-[150px]">

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-4">Leave Requests</h3>
            <table class="w-full table-auto text-left">
                <thead>
                    <tr class="border-b">
                        <th>User</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                        <tr class="border-b hover:bg-gray-50">
                            <td>{{ $leave->user->name }}</td>
                            <td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td class="capitalize {{
                                $leave->status == 'approved' ? 'text-green-600' :
                                ($leave->status == 'rejected' ? 'text-red-600' : 'text-yellow-600')
                            }}">{{ $leave->status }}</td>
                            <td>
                                @if($leave->status == 'pending')
                                    <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="text-green-600">Approve</button>
                                    </form>
                                    <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="inline ml-2">
                                        @csrf @method('PATCH')
                                        <button class="text-red-600">Reject</button>
                                    </form>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>