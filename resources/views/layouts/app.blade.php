<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Attendance System') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <x-navigation>
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    @if (!request()->routeIs('dashboard'))
                        <a href="{{ url()->previous() }}" class="flex items-center text-gray-500 hover:text-indigo-600 transition font-semibold">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </a>
                    @else
                        <div class="w-20"></div>
                    @endif
                    <div class="flex-1 text-center">
                        <span class="text-2xl font-semibold text-gray-800">{{ $header }}</span>
                    </div>
                    <div class="w-20"></div> <!-- Spacer for symmetry -->
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        <div x-data="{ show: true }" x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            x-transition
            class="fixed top-4 right-4 z-50">
            @if (session('success'))
                <div class="flex items-center justify-between bg-green-100 border border-green-400 text-green-800 px-5 py-5 rounded shadow-lg mb-4"
                    role="alert">
                    <span class="mr-4 my-3">{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-800 hover:text-green-600 font-bold text-lg">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center justify-between bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded shadow-lg mb-4"
                    role="alert">
                    <span class="mr-4">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-800 hover:text-red-600 font-bold text-lg">&times;</button>
                </div>
            @endif
        </div>

        <div class="min-h-[70vh] px-4 sm:px-6 lg:px-8 py-6 bg-gray-50">
            {{ $slot }}
        </div>
        <footer class="w-full text-center py-4 text-gray-500 text-sm bg-white border-t border-gray-200 mt-8">
            &copy; {{ date('Y') }} AttendancePro. All rights reserved.
        </footer>
    </x-navigation>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>