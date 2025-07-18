<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update PIN for {{ $user->name }}</h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <div class="bg-white shadow-md rounded p-6">
            <form method="POST" action="{{ route('users.update-pin', $user) }}">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium">New PIN</label>
                    <input type="text" name="pin" required maxlength="10" class="w-full border rounded p-2">
                </div>
                <div class="text-right">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">
                        Update PIN
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
