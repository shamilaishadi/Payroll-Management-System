<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($user) ? 'Edit User' : 'Create User' }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <div class="bg-white shadow-md rounded p-6">
            <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Password {{ isset($user) ? '(leave blank to keep current)' : '' }}</label>
                    <input type="password" name="password" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Role</label>
                    <select name="role" required class="w-full border rounded p-2">
                        @foreach ($roles as $role)
                            @if ($role->name !== 'attendance-maker')
                                <option value="{{ $role->name }}"
                                    {{ (isset($user) && $user->roles->pluck('name')->first() === $role->name) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Attendance PIN {{ isset($user) ? '(leave blank to keep current)' : '' }}</label>
                    <input type="text" name="pin" class="w-full border rounded p-2" maxlength="10" value="{{ old('pin') }}">
                    <small class="text-gray-500">Numeric, up to 10 characters</small>
                </div>


                <div class="text-right">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">
                        {{ isset($user) ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
