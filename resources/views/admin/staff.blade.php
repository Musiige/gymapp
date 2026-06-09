<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Staff <span style="color:#FF6B00">Management</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Create and manage trainer and admin accounts</p>
    </div>

    <div class="bfh-section-title">Create new staff account</div>
    <div class="bfh-card" style="margin-bottom:24px">
        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Full name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="bfh-input" placeholder="e.g. John Doe">
                @error('name')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group">
                <label class="bfh-form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="bfh-input" placeholder="e.g. john@gym.com">
                @error('email')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group">
                <label class="bfh-form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="bfh-input" placeholder="e.g. 0771234567">
                @error('phone')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group">
                <label class="bfh-form-label">Role</label>
                <select name="role" class="bfh-select">
                    <option value="trainer" {{ old('role') == 'trainer' ? 'selected' : '' }}>Trainer</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Temporary password</label>
                <input type="text" name="password" value="{{ old('password') }}"
                    class="bfh-input" placeholder="Min 8 characters">
                @error('password')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Create Account</button>
        </form>
    </div>

    <div class="bfh-section-title">Current staff ({{ $staff->count() }})</div>

    @if($staff->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:24px">
            <p style="color:#555;font-size:13px">No staff accounts yet.</p>
        </div>
    @else
        @foreach($staff as $member)
            <div class="bfh-card" style="margin-bottom:10px">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
                    <div style="width:40px;height:40px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:13px;font-weight:700;flex-shrink:0">
                        {{ strtoupper(substr($member->name, 0, 2)) }}
                    </div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:14px;font-weight:600">{{ $member->name }}</p>
                        <p style="color:#555;font-size:12px;margin-top:2px">{{ $member->email }} · {{ $member->phone }}</p>
                    </div>
                    <span class="bfh-badge {{ $member->role }}">{{ $member->role }}</span>
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    {{-- Change role --}}
                    <form method="POST" action="{{ route('admin.staff.role', $member->id) }}" style="flex:1">
                        @csrf
                        <div style="display:flex;gap:6px">
                            <select name="role" class="bfh-select" style="flex:1;padding:8px 10px;font-size:12px">
                                <option value="trainer" {{ $member->role === 'trainer' ? 'selected' : '' }}>Trainer</option>
                                <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="client">Client</option>
                            </select>
                            <button type="submit" class="bfh-btn sm" style="width:auto;padding:8px 12px;background:#1a2a3a;border:0.5px solid #4a9eff;color:#4a9eff;white-space:nowrap">
                                Update
                            </button>
                        </div>
                    </form>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.staff.destroy', $member->id) }}"
                        onsubmit="return confirm('Delete {{ $member->name }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bfh-btn sm" style="width:auto;padding:8px 14px;background:#3a1a1a;border:0.5px solid #ff4444;color:#ff4444">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>