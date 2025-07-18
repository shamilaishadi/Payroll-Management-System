<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Roles</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4 flex justify-end">
            <a href="{{ route('roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>
                Create New Role
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 text-green-800 bg-green-100 border border-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 border border-black-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-black-500">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-black-500">Permissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase border border-black-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($roles as $role)
                            <tr class="hover:bg-gray-50 transition cursor-pointer" onclick="window.location='{{ route('roles.edit', $role->id) }}'">
                                <td class="px-6 py-4 whitespace-nowrap border border-black-500">
                                    {{ Str::of($role->name)->replace(['-', '_'], ' ')->title() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border border-black-500">
                                    {{ $role->permissions->map(fn($perm) => Str::of($perm->name)->replace(['-', '_'], ' ')->title())->join(', ') ?: 'No Permissions' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border border-black-500" onclick="event.stopPropagation()">
                                    <x-delete-button :action="route('roles.destroy', $role->id)" :entity="'role ' . $role->name" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
