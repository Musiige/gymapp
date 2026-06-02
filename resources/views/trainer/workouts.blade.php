<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Workouts</h2>
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
                <h3 class="font-semibold text-gray-800 mb-4">Create Workout</h3>
                <form method="POST" action="{{ route('trainer.workouts.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="e.g. Upper Body Strength">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Describe the exercises, sets, reps...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                  <button type="submit"
    style="background-color:#4f46e5; color:#ffffff;"
    class="w-full font-semibold py-2 px-4 rounded-md transition hover:opacity-90">
    Create Workout
</button>
                </form>
            </div>

            @if($workouts->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">My Workouts</h3>
                    <div class="flex flex-col gap-4">
                        @foreach($workouts as $workout)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="font-bold text-gray-900">{{ $workout->title }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $workout->description }}</p>

                                <form method="POST" action="{{ route('trainer.workouts.assign') }}" class="mt-4 flex gap-2">
                                    @csrf
                                    <input type="hidden" name="workout_id" value="{{ $workout->id }}">
                                    <select name="client_id" class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Assign to client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit"
    style="background-color:#4f46e5; color:#ffffff;"
    class="text-sm font-semibold py-2 px-4 rounded-md transition hover:opacity-90">
    Assign
</button>
                                </form>

                                @if($workout->assignments->isNotEmpty())
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-400 mb-1">Assigned to:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($workout->assignments as $assignment)
                                                <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">
                                                    {{ $assignment->client->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>