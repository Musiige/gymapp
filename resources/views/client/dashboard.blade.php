<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Client Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Subscription status --}}
            @php
               $subscription = \App\Models\Subscription::where('user_id', Auth::id())
    ->whereIn('status', ['active', 'pending'])
    ->with(['membership', 'payment'])
    ->latest()
    ->first();

                $workouts = \App\Models\WorkoutAssignment::where('client_id', Auth::id())
                    ->with('workout.trainer')
                    ->latest()
                    ->get();
            @endphp

            {{-- Membership card --}}
            @if($subscription)
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">My Membership</h3>
                    <p class="text-gray-600">Package:
                        <span class="font-bold text-gray-900">{{ $subscription->membership->name }}</span>
                    </p>
                    <p class="text-gray-600">Expires:
                        <span class="font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}
                        </span>
                    </p>
                    <p class="text-gray-600">Status:
                        <span class="font-bold uppercase
                            {{ $subscription->status === 'active' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $subscription->status }}
                        </span>
                    </p>
                   <div class="flex gap-4 mt-4 flex-wrap">
    @if(!$subscription->payment || $subscription->payment->status !== 'paid')
        <a href="{{ route('client.payment', $subscription->id) }}"
            style="background-color:#4f46e5; color:#ffffff;"
            class="inline-block font-semibold py-2 px-6 rounded-md text-sm">
            Pay Now
        </a>
    @endif
    <a href="{{ route('client.subscription') }}"
        class="inline-block mt-0 text-indigo-600 hover:underline text-sm self-center">
        Change package
    </a>
</div>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No Active Package</h3>
                    <p class="text-gray-500 mb-4">You have not selected a membership package yet.</p>
                    <a href="{{ route('client.subscription') }}"
                        style="background-color:#4f46e5; color:#ffffff;"
                        class="inline-block font-semibold py-2 px-6 rounded-md text-sm">
                        Choose a Package
                    </a>
                </div>
            @endif

            {{-- Workouts card --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">My Workouts</h3>

                @if($workouts->isEmpty())
                    <p class="text-gray-500 text-sm">No workouts assigned yet. Check back after your trainer sets one up.</p>
                @else
                    <div class="flex flex-col gap-4">
                        @foreach($workouts as $assignment)
                            <div class="border border-gray-100 rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $assignment->workout->title }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $assignment->workout->description }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-3">
                                    Assigned by {{ $assignment->workout->trainer->name }} ·
                                    {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>