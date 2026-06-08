<x-becky-layout>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('trainer.workouts') }}" style="color:#555;font-size:20px;text-decoration:none">←</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800">Edit <span style="color:#FF6B00">Workout</span></h2>
    </div>

    <div class="bfh-card">
        <form method="POST" action="{{ route('trainer.workouts.update', $workout->id) }}">
            @csrf
            @method('PUT')
            <div class="bfh-form-group">
                <label class="bfh-form-label">Title</label>
                <input type="text" name="title" value="{{ old('title', $workout->title) }}"
                    class="bfh-input" placeholder="e.g. Upper Body Strength">
                @error('title')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Description</label>
                <textarea name="description" rows="6"
                    class="bfh-input" style="resize:none"
                    placeholder="Describe the exercises, sets, reps...">{{ old('description', $workout->description) }}</textarea>
                @error('description')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Save changes</button>
        </form>
    </div>

    <form method="POST" action="{{ route('trainer.workouts.destroy', $workout->id) }}"
        onsubmit="return confirm('Delete this workout? This cannot be undone.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bfh-btn danger" style="margin-top:8px">Delete workout</button>
    </form>

</x-becky-layout>