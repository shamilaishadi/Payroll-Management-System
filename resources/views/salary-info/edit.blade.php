<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Edit Salary Info</h2></x-slot>

    <div class="max-w-xl mx-auto p-4">
        <form method="POST" action="{{ route('salary-info.update', $salaryInfo->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block">User</label>
                <input type="text" class="w-full border p-2 rounded bg-gray-100" value="{{ $salaryInfo->user->name }} ({{ $salaryInfo->user->email }})" disabled>
            </div>

            <div class="mb-4">
                <label class="block">Basic salary</label>
                <input type="number" name="basic_salary" step="0.01" value="{{ $salaryInfo->basic_salary }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">OT Rate Per Hour</label>
                <input type="number" name="ot_rate_per_hour" step="0.01" value="{{ $salaryInfo->ot_rate_per_hour }}" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block">Late Deduction Per Hour</label>
                <input type="number" name="late_deduction_per_hour" step="0.01" value="{{ $salaryInfo->late_deduction_per_hour }}" class="w-full border p-2 rounded" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>
