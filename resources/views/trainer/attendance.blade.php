<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mark Attendance</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Record Attendance</h3>
                <form method="POST" action="{{ route('trainer.attendance.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <select name="client_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Session</label>
                        <select name="session_slot" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select session</option>
                            <option value="morning">Morning (5:30am – 8:00am)</option>
                            <option value="midday">Midday (8:00am – 3:30pm)</option>
                            <option value="evening">Evening (3:30pm – 9:00pm)</option>
                        </select>
                        @error('session_slot') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

         <button type="submit"
    style="background-color:#4f46e5; color:#ffffff;"
    class="w-full font-semibold py-2 px-4 rounded-md transition hover:opacity-90">
    Mark Attendance
</button>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Today's Attendance</h3>
                @if($todayAttendance->isEmpty())
                    <p class="text-gray-500 text-sm">No attendance marked today yet.</p>
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

        </div>
    </div>
</x-app-layout>