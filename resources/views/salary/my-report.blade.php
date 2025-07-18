<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Salary Report</h2>
    </x-slot>

    <div class="p-4 max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="month" value="{{ $previousMonth }}">
                <button type="submit" class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">← Prev</button>
            </form>

            <div class="text-lg font-semibold">{{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</div>

            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="month" value="{{ $nextMonth }}">
                <button type="submit" class="px-3 py-1 rounded transition {{ $disableNext ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700' }}" {{ $disableNext ? 'disabled' : '' }}>Next →</button>
            </form>
        </div>

        @if(empty($calendarData))
            <div class="bg-white p-6 rounded shadow text-center text-red-600">
                <p>Your salary info is not set up yet. Please ask an HR admin to set up your salary info.</p>
            </div>
        @else
            <div class="grid grid-cols-7 gap-4 text-center border rounded p-4 bg-white mb-6">
                @foreach($calendarData as $date => $data)
                    @php
                        $isToday = \Carbon\Carbon::parse($date)->isToday();
                        $isFuture = \Carbon\Carbon::parse($date)->isFuture();
                        // Priority: Leave > Future > Absent > Present
                        $status = $data['status'];
                        if ($isFuture) {
                            $status = 'Future';
                        }
                        if ($data['status'] === 'Leave') {
                            $status = 'Leave';
                        }
                        $bgClass = match($status) {
                            'Present' => 'bg-green-50',
                            'Leave' => 'bg-yellow-100',
                            'Absent' => 'bg-red-100',
                            'Future' => 'bg-gray-100',
                            default => $isToday ? 'bg-blue-100' : 'bg-white',
                        };
                        $statusTextClass = match($status) {
                            'Present' => 'text-green-600',
                            'Leave' => 'text-yellow-600',
                            'Absent' => 'text-red-600',
                            'Future' => 'text-gray-600',
                            default => $isToday ? 'text-blue-600' : 'text-gray-600',
                        };
                    @endphp
                    <div class="p-2 border rounded shadow {{ $bgClass }}">
                        <strong>{{ \Carbon\Carbon::parse($date)->format('d M D') }}</strong><br>
                        @if($status === 'Present')
                            Worked: {{ $data['worked_hours'] }}h<br>
                            @if($data['ot_hours'] > 0)
                                OT: {{ $data['ot_hours'] }}h<br>
                            @endif
                        @endif
                        <span class="text-sm text-gray-600">Status:
                            <span class="{{ $statusTextClass }}">
                                {{ $status }}
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
                            <li><strong class="inline-block w-40">Total Worked Hours:</strong> <span class="text-blue-700">{{ $summary['worked_hours'] }} hrs</span></li>
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
                        <strong>Final Salary:</strong>
                        <span class="text-green-700 font-bold text-lg">Rs. {{ number_format($summary['total_pay'], 2) }}</span>
                        <hr class="border-t-2 border-gray-300 my-2">
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
