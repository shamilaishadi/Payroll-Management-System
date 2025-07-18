<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Add Salary Info</h2></x-slot>

    <div class="max-w-xl mx-auto p-4">
        <form method="POST" action="{{ route('salary-info.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block">User</label>
                <select name="user_id" class="w-full border p-2 rounded" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block">Basic salary</label>
                <input type="number" name="basic_salary" step="0.01" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">OT Rate Per Hour</label>
                <input type="number" name="ot_rate_per_hour" step="0.01" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">Late Deduction Per Hour</label>
                <input type="number" name="late_deduction_per_hour" step="0.01" class="w-full border p-2 rounded" required>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</x-app-layout>
