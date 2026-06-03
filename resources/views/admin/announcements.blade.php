<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Send Announcement</h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
    <a href="{{ route('admin.announcements') }}"
        style="background-color:#4f46e5; color:#ffffff;"
        class="inline-block font-semibold py-2 px-6 rounded-md text-sm">
        📢 Send Announcement
    </a>
</div>

        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Push to all clients</h3>
                <form method="POST" action="{{ route('admin.announcements.send') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            placeholder="e.g. Weekend Special Offer"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" rows="4"
                            placeholder="Type your announcement here..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        style="background-color:#4f46e5; color:#ffffff;"
                        class="w-full font-semibold py-3 px-4 rounded-md transition hover:opacity-90">
                        Send to All Clients
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>