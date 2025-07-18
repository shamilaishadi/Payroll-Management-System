<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Salary Info</h2>
    </x-slot>

    <div class="container mx-auto p-4">
        <a href="{{ route('salary-info.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Add New</a>

        @if (session('success'))
            <div class="text-green-600">{{ session('success') }}</div>
        @endif

        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">User</th>
                    <th class="border px-4 py-2">Basic</th>
                    <th class="border px-4 py-2">OT Rate/Hr</th>
                    <th class="border px-4 py-2">Late Deduction/Hr</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaryInfos as $info)
                    <tr>
                        <td class="border px-4 py-2">{{ $info->user->name }}</td>
                        <td class="border px-4 py-2">Rs. {{ $info->basic_salary }}</td>
                        <td class="border px-4 py-2">Rs. {{ $info->ot_rate_per_hour }}</td>
                        <td class="border px-4 py-2">Rs. {{ $info->late_deduction_per_hour }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('salary-info.edit', $info->id) }}" class="text-blue-500 underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
