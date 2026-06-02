<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Stats row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalClients }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total Clients</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalTrainers }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total Trainers</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $weeklyAttendance }}</p>
                    <p class="text-sm text-gray-500 mt-1">This Week's Attendance</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ $monthlyAttendance }}</p>
                    <p class="text-sm text-gray-500 mt-1">This Month's Attendance</p>
                </div>
            </div>

            {{-- Attendance by session slot --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Attendance by Session</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach(['morning' => '5:30am – 8:00am', 'midday' => '8:00am – 3:30pm', 'evening' => '3:30pm – 9:00pm'] as $slot => $time)
                        <div class="text-center bg-indigo-50 rounded-lg p-4">
                            <p class="text-2xl font-bold text-indigo-600">
                                {{ $attendanceBySlot[$slot]->total ?? 0 }}
                            </p>
                            <p class="text-sm font-medium text-gray-700 capitalize mt-1">{{ $slot }}</p>
                            <p class="text-xs text-gray-400">{{ $time }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Today's attendance --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">
                    Today's Attendance
                    <span class="text-sm font-normal text-gray-400 ml-2">{{ now()->format('d M Y') }}</span>
                </h3>
                @if($todayAttendance->isEmpty())
                    <p class="text-gray-500 text-sm">No attendance recorded today yet.</p>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($todayAttendance as $record)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $record->client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-indigo-100 text-indigo-700 capitalize">
                                    {{ $record->session_slot }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- All subscriptions --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">All Subscriptions</h3>
                @if($subscriptions->isEmpty())
                    <p class="text-gray-500 text-sm">No subscriptions yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="pb-3 text-gray-500 font-medium">Client</th>
                                    <th class="pb-3 text-gray-500 font-medium">Package</th>
                                    <th class="pb-3 text-gray-500 font-medium">Start</th>
                                    <th class="pb-3 text-gray-500 font-medium">Expires</th>
                                    <th class="pb-3 text-gray-500 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscriptions as $sub)
                                    <tr class="border-b border-gray-50">
                                        <td class="py-3 font-medium text-gray-900">{{ $sub->user->name }}</td>
                                        <td class="py-3 text-gray-600">{{ $sub->membership->name }}</td>
                                        <td class="py-3 text-gray-600">{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</td>
                                        <td class="py-3 text-gray-600">{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
                                        <td class="py-3">
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full capitalize
                                                {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' :
                                                   ($sub->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                                   'bg-red-100 text-red-700') }}">
                                                {{ $sub->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Clients overview --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Clients Overview</h3>
                @if($clients->isEmpty())
                    <p class="text-gray-500 text-sm">No clients registered yet.</p>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($clients as $client)
                            @php
                                $sub = $client->subscriptions->last();
                            @endphp
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $client->phone }}</p>
                                </div>
                                <div class="text-right">
                                    @if($sub)
                                        <p class="text-xs font-medium text-gray-700">{{ $sub->membership->name }}</p>
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full capitalize
                                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' :
                                               ($sub->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                               'bg-red-100 text-red-700') }}">
                                            {{ $sub->status }}
                                        </span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">No package</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>