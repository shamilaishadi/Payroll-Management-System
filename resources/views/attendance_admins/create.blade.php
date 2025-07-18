<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Add Attendance Admin</h2>
    </x-slot>

    <div class="max-w-md mx-auto mt-6 bg-white shadow-md p-6 rounded">
        <form action="{{ route('attendance-admins.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full border px-3 py-2 rounded" required>
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="flex justify-end">
                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
