<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="javascript:history.back()" style="color:#555;font-size:13px;text-decoration:none">← Back</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">{{ $workout->title }}</h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Created by {{ $workout->trainer->name }}</p>
    </div>

    <div class="bfh-card" style="margin-bottom:20px">
        <p style="color:#666;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Description</p>
        <p style="color:#ccc;font-size:14px;line-height:1.6;white-space:pre-line">{{ $workout->description }}</p>
    </div>

    <div class="bfh-section-title">Assigned clients ({{ $workout->assignments->count() }})</div>
    @if($workout->assignments->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">Not assigned to any client yet.</p>
        </div>
    @else
        @foreach($workout->assignments as $assignment)
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                <div style="width:36px;height:36px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($assignment->client->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:13px;font-weight:500">{{ $assignment->client->name }}</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">{{ $assignment->client->phone }}</p>
                </div>
            </div>
        @endforeach
    @endif

    @if($workout->trainer_id === Auth::id())
        <a href="{{ route('trainer.workouts.edit', $workout->id) }}" class="bfh-btn" style="margin-top:16px">Edit Workout</a>
    @endif

</x-becky-layout>