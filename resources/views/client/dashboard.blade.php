<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Client Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Subscription status --}}
            @php
                $subscription = \App\Models\Subscription::where('user_id', Auth::id())
                    ->whereIn('status', ['active', 'pending'])
                    ->with('membership')
                    ->latest()
                    ->first();
            @endphp

            @if($subscription)
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">My Membership</h3>
                    <p class="text-gray-600">Package: <span class="font-bold text-gray-900">{{ $subscription->membership->name }}</span></p>
                    <p class="text-gray-600">Expires: <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</span></p>
                    <p class="text-gray-600">Status:
                        <span class="font-bold uppercase
                            {{ $subscription->status === 'active' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $subscription->status }}
                        </span>
                    </p>
                    <a href="{{ route('client.subscription') }}"
                        class="inline-block mt-4 text-indigo-600 hover:underline text-sm">
                        Change package
                    </a>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No Active Package</h3>
                    <p class="text-gray-500 mb-4">You have not selected a membership package yet.</p>
                    <a href="{{ route('client.subscription') }}"
                    style="background-color:#4f46e5; color:#ffffff;"
            style="background-color:#4f46e5; color:#ffffff;"
class="inline-block font-semibold py-2 px-6 rounded-md text-sm"
                        Choose a Package
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>