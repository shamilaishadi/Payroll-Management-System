<!-- resources/views/components/navigation.blade.php -->
<div x-data="{ 
    mobileMenuOpen: false, 
    sidebarOpen: window.innerWidth > 768, 
    profileDropdownOpen: false 
}" 
x-init="() => {
    // Close mobile menu if window is resized to desktop size
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            mobileMenuOpen = false;
            sidebarOpen = true;
        } else {
            sidebarOpen = false;
        }
    });
}"
class="flex min-h-screen bg-gray-100">
    <!-- Mobile menu button (only shows on mobile) -->
    <div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center h-16 px-4">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-md text-gray-600 hover:bg-gray-100" x-show="!mobileMenuOpen">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="font-bold text-xl text-indigo-700 tracking-wide">AttendancePro</a>
            <div class="w-6"></div> <!-- Spacer for symmetry -->
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"></div>

    <!-- Sidebar -->
    <aside x-show="sidebarOpen || mobileMenuOpen"
           @click.away="if(window.innerWidth <= 768) mobileMenuOpen = false"
           :class="{
               'fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-lg': window.innerWidth <= 768 && mobileMenuOpen,
               'hidden': !sidebarOpen && window.innerWidth > 768,
               'fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200': window.innerWidth > 768 && sidebarOpen
           }"
           class="transition-all duration-300 ease-in-out md:pt-0">
        <div class="overflow-y-auto h-full py-4 px-3 bg-gray relative" style="background-color:#d9dbde">
            <!-- Show arrow-left only in expanded nav bar (desktop sidebarOpen) -->
            <template x-if="sidebarOpen && window.innerWidth > 768">
                <button @click="sidebarOpen = false" class="absolute top-2 right-2 p-2 rounded-full text-gray-500 hover:bg-gray-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </template>
            <!-- Close button for mobile sidebar -->
            <button @click="mobileMenuOpen = false" class="absolute top-2 right-2 p-2 rounded-full text-gray-500 hover:bg-gray-200 md:hidden" x-show="mobileMenuOpen">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex items-center mb-6">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-700 w-full text-center tracking-wide">TUH</a>
            </div>
            <ul class="space-y-2 pt-5">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-tachometer-alt w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                @can('view own attendance')
                <li>
                    <a href="{{ route('attendance.history') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('attendance.history') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-history w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('attendance.history') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">My Attendance</span>
                    </a>
                </li>
                @endcan

                @can('view employee attendance')
                <li>
                    <a href="{{ route('attendance.employees') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('attendance.employees') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-users w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('attendance.employees') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Employee Attendance</span>
                    </a>
                </li>
                @endcan

                <!-- Add other menu items similarly -->
                <!-- User Management -->
                @can('manage_users')
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('users.index') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-user-cog w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('users.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Manage Users</span>
                    </a>
                </li>
                @endcan

                @can('manage_roles')
                <li>
                    <a href="{{ route('roles.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('roles.index') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-user-tag w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('roles.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Manage Roles</span>
                    </a>
                </li>
                @endcan

                <!-- Work Schedules -->
                @can('view_work_schedule')
                <li>
                    <a href="{{ route('work-schedules.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('work-schedules.index') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-calendar-alt w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('work-schedules.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Work Schedules</span>
                    </a>
                </li>
                @endcan

                <!-- Salary Management -->
                @can('manage_salary')
                <li>
                    <a href="{{ route('salary-info.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('salary-info.index') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-money-bill-wave w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('salary-info.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Salary Info</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('salary-report.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('salary-report.index') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-file-invoice-dollar w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('salary-report.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Salary Report</span>
                    </a>
                </li>
                @endcan

                @auth
                <li>
                    <a href="{{ route('salary.my-report') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('salary.my-report') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-wallet w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('salary.my-report') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">My Salary</span>
                    </a>
                </li>
                @endauth

                <!-- Leaves Management -->
                <li>
                    <a href="{{ route('leaves.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('leaves.index') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-umbrella-beach w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('leaves.index') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">My Leaves</span>
                    </a>
                </li>

                @can('manage_leaves')
                <li>
                    <a href="{{ route('leaves.manage') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('leaves.manage') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-tasks w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('leaves.manage') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Manage Leaves</span>
                    </a>
                </li>
                @endcan

                <!-- Profile and Logout -->
                <li class="border-t border-gray-200 mt-4 pt-4">
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 group {{ request()->routeIs('profile.edit') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700' }}">
                        <i class="fas fa-user w-6 text-center text-gray-500 group-hover:text-indigo-600 {{ request()->routeIs('profile.edit') ? 'text-indigo-600' : '' }}"></i>
                        <span class="ml-3">Profile</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 rounded-lg hover:bg-indigo-50 group text-gray-700">
                            <i class="fas fa-sign-out-alt w-6 text-center text-gray-500 group-hover:text-indigo-600"></i>
                            <span class="ml-3">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col"
         :class="{
             'md:ml-64': sidebarOpen,
             'md:ml-0': !sidebarOpen
         }">
        <!-- Desktop Sidebar Open Button (top-left, only on desktop, only when sidebar is closed) -->
        <button
            @click="sidebarOpen = true"
            class="hidden md:block fixed top-4 left-4 z-30 p-2 rounded-full bg-white border border-gray-200 shadow text-gray-600 hover:bg-gray-100 transition"
            x-show="!sidebarOpen && window.innerWidth > 768"
            style="transition: left 0.3s;"
        >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto pt-0">
            {{ $slot }}
        </main>
    </div>
</div>