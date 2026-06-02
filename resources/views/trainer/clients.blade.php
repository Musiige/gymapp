<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Clients</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($clients->isEmpty())
                <div class="bg-white rounded-xl p-6 text-center text-gray-500 shadow-sm">
                    No clients registered yet.
                </div>
            @else
                <div class="flex flex-col gap-4">
                    @foreach($clients as $client)
                        @php
                            $sub = $client->subscriptions->last();
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $client->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $client->phone }}</p>
                                </div>
                                <div class="text-right">
                                    @if($sub)
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full
                                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">{{ $sub->membership->name ?? '' }}</p>
                                    @else
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                            No package
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>