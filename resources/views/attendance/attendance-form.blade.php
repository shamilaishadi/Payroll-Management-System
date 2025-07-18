<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Form') }}
        </h2>
    </x-slot>

    <div class="container mx-auto py-6">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-2xl font-bold text-center mb-4">
                Hello, {{ auth()->user()->name }}!
            </h3>

            <div class="mb-4">
                <p class="text-lg font-semibold">{{ $statusMessage }}</p>

                @if ($isLate)
                    <p class="text-red-600">You are late. Shift started at {{ \Carbon\Carbon::parse($shiftStart)->format('h:i A') }}</p>
                @elseif ($isShortLeave)
                    <p class="text-orange-600">Leaving early. Shift ends at {{ \Carbon\Carbon::parse($shiftEnd)->format('h:i A') }}</p>
                @endif
            </div>

            <form action="{{ route('attendance.' . ($nextAction === 'in' ? 'sign-in' : 'sign-out')) }}" method="POST">
                @csrf
                <input type="hidden" name="sign_type" value="{{ $nextAction }}">

                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="mt-1 p-2 w-full border rounded-md">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        {{ $nextAction === 'in' ? 'Sign In' : 'Sign Out' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
