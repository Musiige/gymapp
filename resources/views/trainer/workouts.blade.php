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
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <p style="color:#fff;font-size:14px;font-weight:600">{{ $workout->title }}</p>
                            <a href="{{ route('trainer.workouts.edit', $workout->id) }}"
                                style="color:#FF6B00;font-size:12px;text-decoration:none;flex-shrink:0;margin-left:8px">
                                Edit ✎
                            </a>
                        </div>
                        <p style="color:#555;font-size:12px;margin-top:4px;line-height:1.5;white-space:pre-line">{{ $workout->description }}</p>
                    </div>
                </div>

                @if($workout->assignments->isNotEmpty())
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px">
                        @foreach($workout->assignments as $assignment)
                            <span style="background:#2a2a2a;color:#888;font-size:11px;padding:3px 6px 3px 10px;border-radius:20px;display:inline-flex;align-items:center;gap:6px">
                                {{ $assignment->client->name }}
                                <form method="POST" action="{{ route('trainer.workouts.unassign', $assignment->id) }}"
                                    onsubmit="return confirm('Remove this workout from {{ $assignment->client->name }}?')" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#ff4444;font-size:13px;cursor:pointer;padding:0;line-height:1">✕</button>
                                </form>
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="bfh-divider" style="margin:10px 0"></div>

                <form method="POST" action="{{ route('trainer.workouts.assign') }}" style="display:flex;gap:8px">
                    @csrf
                    <input type="hidden" name="workout_id" value="{{ $workout->id }}">
                    <div style="flex:1;position:relative">
                        <input type="text" id="search-input-{{ $workout->id }}"
                            placeholder="Search client..."
                            class="bfh-input" style="padding:10px 12px" autocomplete="off">
                        <input type="hidden" name="client_id" id="select-{{ $workout->id }}">

                        <div id="results-{{ $workout->id }}" class="search-results-box" style="display:none;position:absolute;top:100%;left:0;right:0;border:0.5px solid #333;border-radius:10px;margin-top:4px;max-height:200px;overflow-y:auto;z-index:50">
                            @foreach($clients as $client)
                                <div class="assign-option-{{ $workout->id }} search-result-item" data-id="{{ $client->id }}"
                                    data-name="{{ $client->name }} — {{ $client->phone }}"
                                    data-search="{{ strtolower($client->name . ' ' . $client->phone) }}"
                                    style="padding:10px 14px;cursor:pointer;border-bottom:0.5px solid #2a2a2a">
                                    <p class="search-result-name" style="font-size:13px">{{ $client->name }}</p>
                                    <p class="search-result-phone" style="font-size:11px;margin-top:2px">{{ $client->phone }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap">Assign</button>
                </form>
            </div>
        @endforeach
    @endif

    <script>
        @foreach($workouts as $workout)
        (function() {
            const searchInput = document.getElementById('search-input-{{ $workout->id }}');
            const resultsBox = document.getElementById('results-{{ $workout->id }}');
            const hiddenInput = document.getElementById('select-{{ $workout->id }}');
            const options = document.querySelectorAll('.assign-option-{{ $workout->id }}');

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                hiddenInput.value = '';

                if (query.length === 0) {
                    resultsBox.style.display = 'none';
                    return;
                }

                let anyVisible = false;
                options.forEach(opt => {
                    if (opt.dataset.search.includes(query)) {
                        opt.style.display = 'block';
                        anyVisible = true;
                    } else {
                        opt.style.display = 'none';
                    }
                });

                resultsBox.style.display = anyVisible ? 'block' : 'none';
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length > 0) resultsBox.style.display = 'block';
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#results-{{ $workout->id }}') && e.target !== searchInput) {
                    resultsBox.style.display = 'none';
                }
            });

            options.forEach(opt => {
                opt.addEventListener('click', function() {
                    searchInput.value = this.dataset.name;
                    hiddenInput.value = this.dataset.id;
                    resultsBox.style.display = 'none';
                });
            });
        })();
        @endforeach
    </script>
</x-becky-layout>