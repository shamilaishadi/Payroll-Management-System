<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Receipt') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-2xl font-bold text-center mb-4">Attendance Details</h3>

            <div class="mb-4">
                <p><strong>User:</strong> {{ $attendance->user->name }}</p>
                <p><strong>Email:</strong> {{ $attendance->user->email }}</p>

                <p><strong>Shift Start Time:</strong> {{ $attendance->shift_start ? \Carbon\Carbon::parse($attendance->shift_start)->format('h:i A') : 'Not Available' }}</p>

                <p><strong>Sign In Time:</strong> 
                    <span id="sign-in-time" data-time="{{ \Carbon\Carbon::parse($attendance->sign_in_time)->toIso8601String() }}"></span>
                </p>

                <p><strong>Sign In Status:</strong> {{ ucfirst($attendance->sign_in_status ?? 'N/A') }}</p>

                @if ($attendance->sign_out_time)
                    <p><strong>Scheduled Sign Out Time:</strong> {{ $attendance->shift_end ? \Carbon\Carbon::parse($attendance->shift_end)->format('h:i A') : 'Not Available' }}</p>
                    <p><strong>Actual Sign Out Time:</strong> 
                        <span id="sign-out-time" data-time="{{ \Carbon\Carbon::parse($attendance->sign_out_time)->toIso8601String() }}"></span>
                    </p>
                    <p><strong>Sign Out Status:</strong> {{ ucfirst($attendance->sign_out_status ?? 'N/A') }}</p>

                    @if ($attendance->sign_out_status === 'over_time')
                        <p><strong>Overtime:</strong> Yes</p>
                    @endif
                @endif
            </div>

            <div class="flex justify-center">
                <a href="{{ route('dashboard') }}" class="btn btn-primary px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const signInTimeElement = document.getElementById('sign-in-time');
            const signOutTimeElement = document.getElementById('sign-out-time');

            if (signInTimeElement?.dataset.time) {
                const signInTime = new Date(signInTimeElement.dataset.time);
                signInTimeElement.textContent = signInTime.toLocaleString('en-GB', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });
            }

            if (signOutTimeElement?.dataset.time) {
                const signOutTime = new Date(signOutTimeElement.dataset.time);
                signOutTimeElement.textContent = signOutTime.toLocaleString('en-GB', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });
            }
        });
    </script>
</x-app-layout>
