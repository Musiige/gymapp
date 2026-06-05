<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">My <span style="color:#FF6B00">Workouts</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Create and assign workout plans</p>
    </div>

    <div class="bfh-section-title">Create new workout</div>
    <div class="bfh-card" style="margin-bottom:24px">
        <form method="POST" action="{{ route('trainer.workouts.store') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="bfh-input" placeholder="e.g. Upper Body Strength">
                @error('title')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Description</label>
                <textarea name="description" rows="3" class="bfh-input" style="resize:none" placeholder="Describe the exercises, sets, reps...">{{ old('description') }}</textarea>
                @error('description')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Create Workout</button>
        </form>
    </div>

    @if($workouts->isNotEmpty())
        <div class="bfh-section-title">My workouts ({{ $workouts->count() }})</div>

        @foreach($workouts as $workout)
            <div class="bfh-card" style="margin-bottom:12px">
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px">
                    <div class="bfh-icon-box">{{ strtoupper(substr($workout->title, 0, 1)) }}</div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:14px;font-weight:600">{{ $workout->title }}</p>
                        <p style="color:#555;font-size:12px;margin-top:4px;line-height:1.5">{{ $workout->description }}</p>
                    </div>
                </div>

                @if($workout->assignments->isNotEmpty())
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px">
                        @foreach($workout->assignments as $assignment)
                            <span style="background:#2a2a2a;color:#888;font-size:11px;padding:3px 10px;border-radius:20px">
                                {{ $assignment->client->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="bfh-divider" style="margin:10px 0"></div>

                <form method="POST" action="{{ route('trainer.workouts.assign') }}" style="display:flex;gap:8px">
                    @csrf
                    <input type="hidden" name="workout_id" value="{{ $workout->id }}">
                    <select name="client_id" class="bfh-select" style="flex:1;padding:10px 12px">
                        <option value="">Assign to client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap">Assign</button>
                </form>
            </div>
        @endforeach
    @endif

</x-becky-layout>