<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Trainer Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-gray-600 mb-6">Welcome, {{ Auth::user()->name }}.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('trainer.clients') }}"
                    class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-indigo-400 transition text-center">
                    <p class="text-3xl mb-2">👥</p>
                    <p class="font-semibold text-gray-800">My Clients</p>
                    <p class="text-sm text-gray-500 mt-1">View all registered clients</p>
                </a>
                <a href="{{ route('trainer.attendance') }}"
                    class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-indigo-400 transition text-center">
                    <p class="text-3xl mb-2">✅</p>
                    <p class="font-semibold text-gray-800">Attendance</p>
                    <p class="text-sm text-gray-500 mt-1">Mark today's attendance</p>
                </a>
                <a href="{{ route('trainer.workouts') }}"
                    class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-indigo-400 transition text-center">
                    <p class="text-3xl mb-2">💪</p>
                    <p class="font-semibold text-gray-800">Workouts</p>
                    <p class="text-sm text-gray-500 mt-1">Create and assign workouts</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>