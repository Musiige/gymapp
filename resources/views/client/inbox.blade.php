<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">My <span style="color:#FF6B00">Inbox</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Messages from Becky Fitness Hub</p>
    </div>

    @if($messages->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:40px">
            <p style="font-size:32px;margin-bottom:12px">📭</p>
            <p style="color:#555;font-size:14px">No messages yet.</p>
        </div>
    @else
        @foreach($messages as $message)
            <div class="bfh-card" style="margin-bottom:12px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px">
                    <p style="color:#fff;font-size:15px;font-weight:600">{{ $message->title }}</p>
                    <span style="color:#FF6B00;font-size:10px;background:#2a1a0a;padding:3px 8px;border-radius:20px;flex-shrink:0;margin-left:8px">
                        {{ $message->recipient_type === 'all' ? 'Broadcast' : 'Personal' }}
                    </span>
                </div>
                <p style="color:#aaa;font-size:13px;line-height:1.6;white-space:pre-line">{{ $message->message }}</p>
                <p style="color:#555;font-size:11px;margin-top:10px">
                    {{ \Carbon\Carbon::parse($message->created_at)->format('d M Y, h:i A') }}
                </p>
            </div>
        @endforeach
    @endif

</x-becky-layout>