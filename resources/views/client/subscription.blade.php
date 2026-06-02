<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Choose Your Package
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($activeSubscription)
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-5 mb-8">
                    <h3 class="text-base font-semibold text-indigo-900 mb-2">Current Package</h3>
                    <p class="text-indigo-800">{{ $activeSubscription->membership->name }}</p>
                    <p class="text-indigo-700 text-sm">Expires: {{ \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M Y') }}</p>
                    <p class="text-indigo-700 text-sm uppercase font-bold mt-1">{{ $activeSubscription->status }}</p>
                </div>
            @endif

            <p class="text-gray-500 text-sm mb-6 text-center">Tap a package below to select it</p>

            <div class="flex flex-col gap-4">
                @foreach($memberships as $membership)
                    <form method="POST" action="{{ route('client.subscription.store') }}">
                        @csrf
                        <input type="hidden" name="membership_id" value="{{ $membership->id }}">
                        <button type="submit"
                            class="w-full text-left bg-white border border-gray-200 hover:border-indigo-500 hover:bg-indigo-50 rounded-xl p-5 shadow-sm transition active:scale-95">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-base font-bold text-gray-900">{{ $membership->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $membership->description }}</p>
                                </div>
                                <div class="text-right ml-4 shrink-0">
                                    <p class="text-lg font-bold text-indigo-600">UGX {{ number_format($membership->price) }}</p>
                                    <p class="text-xs text-gray-400">{{ $membership->duration_days }} day(s)</p>
                                </div>
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>