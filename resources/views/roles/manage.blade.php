<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($role) ? 'Edit Role' : 'Create Role' }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <div class="bg-white shadow-md rounded p-6">
            <form method="POST" action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">
                @csrf
                @if(isset($role))
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Role Name</label>
                    <input type="text" name="name" value="{{ old('name', isset($role) ? Str::of($role->name)->replace(['-', '_'], ' ')->title() : '') }}" required class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 font-medium">Permissions</label>
                    <div class="grid grid-cols-2 gap-2 max-h-64 overflow-y-auto border rounded p-2">
                        @foreach($permissions as $permission)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    {{ isset($role) && $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                <span>{{ Str::of($permission->name)->replace(['-', '_'], ' ')->title() }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('roles.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        {{ isset($role) ? 'Update Role' : 'Create Role' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
