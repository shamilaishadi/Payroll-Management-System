<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Salary Report</h2>
    </x-slot>

    <div class="p-4 max-w-6xl mx-auto">
        <form method="GET" class="flex items-end gap-4 mb-6">
            <div>
                <label>User</label>
                <select name="user_id" class="border rounded p-2 w-48">
                    <option value="">-- Select --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Month</label>
                <input type="month" name="month" value="{{ $selectedMonth }}" class="border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">View</button>
        </form>

        @if($calendarData)
            <div class="grid grid-cols-7 gap-4 text-center border rounded p-4 bg-white mb-6">
                @foreach($calendarData as $date => $data)
                    @php
                        $isToday = \Carbon\Carbon::parse($date)->isToday();
                        $isFuture = \Carbon\Carbon::parse($date)->isFuture();
                        $bgClass = match(true) {
                            $isToday => 'bg-blue-100',
                            $data['status'] === 'Present' => 'bg-green-50',
                            $data['status'] === 'Leave' => 'bg-yellow-100',
                            $data['status'] === 'Absent' => 'bg-red-100',
                            $isFuture => 'bg-gray-100',
                            default => 'bg-white',
                        };
                        $statusTextClass = match(true) {
                            $isToday => 'text-blue-600',
                            $data['status'] === 'Present' => 'text-green-600',
                            $data['status'] === 'Leave' => 'text-yellow-600',
                            $data['status'] === 'Absent' => 'text-red-600',
                            $isFuture => 'text-gray-600',
                            default => 'text-gray-600',
                        };
                    @endphp
                    <div class="p-2 border rounded shadow {{ $bgClass }}">
                        <strong>{{ \Carbon\Carbon::parse($date)->format('d M D') }}</strong><br>
                        @if($data['status'] === 'Present')
                            Worked: {{ $data['worked_hours'] }}h<br>
                            @if($data['ot_hours'] > 0)
                                OT: {{ $data['ot_hours'] }}h<br>
                            @endif
                        @endif
                        <span class="text-sm text-gray-600">Status:
                            <span class="{{ $statusTextClass }}">
                                {{ $isFuture ? 'Future' : $data['status'] }}
                            </span>
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-xl font-semibold mb-4">Salary Summary - {{ $summary['month'] }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <ul class="space-y-2">
                            <li><strong class="inline-block w-40">Basic Salary:</strong> <span class="text-blue-700">Rs. {{ number_format($summary['basic_salary'], 2) }}</span></li>
                            <li><strong class="inline-block w-40">Worked Hours:</strong> <span class="text-blue-700">{{ $summary['worked_hours'] }} hrs</span></li>
                            <li><strong class="inline-block w-40">Overtime Hours:</strong> <span class="text-blue-700">{{ $summary['ot_hours'] }} hrs</span></li>
                            <li><strong class="inline-block w-40">Absent Days:</strong> <span class="text-red-700">{{ $summary['absent_days'] }}</span></li>
                        </ul>
                    </div>
                    <div>
                        <ul class="space-y-2">
                            <li><strong class="inline-block w-40">OT Pay:</strong> <span class="text-green-700">+ Rs. {{ number_format($summary['ot_pay'], 2) }}</span></li>
                            <li><strong class="inline-block w-40">Absent Deduction:</strong> <span class="text-red-700">- Rs. {{ number_format($summary['absent_deduction'], 2) }}</span></li>
                            <li><strong class="inline-block w-40">Late Deduction:</strong> <span class="text-red-700">- Rs. {{ number_format($summary['late_deduction'], 2) }}</span></li>
                            <li><strong class="inline-block w-40">EPF Deduction:</strong> <span class="text-red-700">- Rs. {{ number_format($summary['epf_deduction'], 2) }}</span></li>
                        </ul>
                    </div>
                    <div class="col-span-1 md:col-span-2 mt-4">
                        <hr class="border-t-2 border-gray-300 my-2">
                        <strong>Final Salary :</strong>
                        <span class="text-green-700 font-bold text-lg">Rs. {{ number_format($summary['total_pay'], 2) }}</span>
                        <hr class="border-t-2 border-gray-300 my-2">
                    </div>
                </div>

                {{-- <div class="mt-6">
                    <a href="#" class="bg-green-600 text-white px-4 py-2 rounded">Download PDF</a>
                </div> --}}

                <div class="mt-6">
                    <form method="POST" action="{{ route('salary.email.user') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Send Paysheet Email
                        </button>
                    </form>
                </div>

            </div>
        @else
            <div class="bg-white p-6 rounded shadow text-center text-red-600">
                <p>Please select a user with a valid work schedule and salary info.</p>
                <a href="{{ route('salary-info.create') }}" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Salary Info</a>
            </div>
        @endif
    </div>
</x-app-layout>
