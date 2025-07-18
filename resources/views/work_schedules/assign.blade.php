<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assign Users to Work Schedule</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded p-6">
            <form method="POST" action="{{ route('work-schedules.store-assignment') }}">
                @csrf

                <!-- Work Schedule Selection -->
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Select Work Schedule</label>
                    <select name="work_schedule_id" id="work_schedule_id" required class="w-full border rounded p-2">
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Users Lists -->
                <div class="flex space-x-8">
                    <!-- Not Added Users List -->
                    <div class="w-1/2">
                        <label class="block mb-2 font-medium">Not Added Users</label>
                        <select id="not_added_users" multiple size="10" class="w-full border rounded p-2">
                            @foreach ($users as $user)
                                <!-- Only show users who are not assigned to any schedule -->
                                @if (!in_array($user->id, $assignedUsers[$schedules->first()->id] ?? []))
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" id="add_user" class="bg-blue-600 text-white px-4 py-2 mt-2 rounded hover:bg-blue-700">
                            Add to Schedule
                        </button>
                    </div>

                    <!-- Added Users List -->
                    <div class="w-1/2">
                        <label class="block mb-2 font-medium">Added Users</label>
                        <select id="added_users" name="users[]" multiple size="10" class="w-full border rounded p-2">
                            @foreach ($users as $user)
                                <!-- Show users assigned to the schedule -->
                                @if (in_array($user->id, $assignedUsers[$schedules->first()->id] ?? []))
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" id="remove_user" class="bg-red-600 text-white px-4 py-2 mt-2 rounded hover:bg-red-700">
                            Remove from Schedule
                        </button>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="text-right mt-4">
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
                        Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add user to the added list
        document.getElementById('add_user').addEventListener('click', function() {
            let selectedUser = document.getElementById('not_added_users').selectedOptions;
            let addedList = document.getElementById('added_users');
            for (let option of selectedUser) {
                // Remove selected option from not added list
                option.remove();
                // Add to added list
                addedList.appendChild(option);
            }
        });

        // Remove user from the added list
        document.getElementById('remove_user').addEventListener('click', function() {
            let selectedUser = document.getElementById('added_users').selectedOptions;
            let notAddedList = document.getElementById('not_added_users');
            for (let option of selectedUser) {
                // Remove selected option from added list
                option.remove();
                // Add back to not added list
                notAddedList.appendChild(option);
            }
        });
    </script>
</x-app-layout>
