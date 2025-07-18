<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-lg font-semibold">
                    👋 Hello {{ auth()->user()->name }}, you're logged in!
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @can('manage_users')
                    <a href="{{ route('users.index') }}"
                       class="bg-blue-600 hover:bg-blue-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8zm0 0v1m0 4h.01"></path>
                        </svg>
                        <span class="font-semibold">Users</span>
                    </a>
                @endcan

                {{-- @can('mark_attendance')
                    <a href="{{ route('attendance.create') }}"
                       class="bg-green-600 hover:bg-green-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 16h4v4m0 0h4m-4 0v-4m-4 0h-4m0 0H8m0 0V8m0 8V8m0 8h4m4 0h4"></path>
                        </svg>
                        <span class="font-semibold">Mark Attendance</span>
                    </a>
                @endcan --}}

                @canany(['view_attendance', 'mark_attendance'])
                    <a href="{{ route('attendance.view') }}"
                       class="bg-yellow-600 hover:bg-yellow-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold">View Attendance</span>
                    </a>
                @endcan

                @can('manage_roles')
                    <a href="{{ route('roles.index') }}"
                       class="bg-purple-600 hover:bg-purple-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        <span class="font-semibold">Manage Roles</span>
                    </a>
                @endcan

                @can('manage_work_schedule')
                    <a href="{{ route('work-schedules.index') }}"
                       class="bg-teal-600 hover:bg-teal-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 4v16m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        <span class="font-semibold">Manage Work Schedule</span>
                    </a>
                @endcan

                @can('manage_salary')
                    <a href="{{ route('salary-info.index') }}"
                       class="bg-pink-600 hover:bg-pink-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 12v4"></path>
                        </svg>
                        <span class="font-semibold">Manage Salary Info</span>
                    </a>
                    <a href="{{ route('salary-report.index') }}"
                       class="bg-red-600 hover:bg-red-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center mt-4">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3zm3 6h12M9 9v6m6-6v6"></path>
                        </svg>
                        <span class="font-semibold">Salary Report</span>
                    </a>
                @endcan

                <a href="{{ route('salary.my-report') }}"
                   class="bg-orange-600 hover:bg-orange-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 12v4"></path>
                    </svg>
                    <span class="font-semibold">My Salary Report</span>
                </a>

                <a href="{{ route('leaves.index') }}"
                   class="bg-lime-600 hover:bg-lime-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-semibold">My Leave Requests</span>
                </a>
                @can('manage_leaves')
                <a href="{{ route('leaves.manage') }}"
                   class="bg-cyan-600 hover:bg-cyan-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-semibold">Manage Leave Requests</span>
                </a>
                @endcan
                <a href="{{ route('users.edit-pin') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 transition text-white p-6 rounded-lg shadow flex flex-col items-center justify-center">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m0-6v2m-6 4V7a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H8a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-semibold">Edit Attendence Pin</span>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
