
@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>My profile</h1>
</div>

<div style="padding:20px 24px; max-width:640px; margin:0 auto;">

    {{-- Tab switcher --}}
    <div style="display:flex; border:1px solid var(--border); border-radius:8px; overflow:hidden; margin-bottom:20px;">
        <button onclick="switchTab('profile',this)" class="dash-tab"
                style="flex:1; padding:9px 0; text-align:center; font-size:13px; font-weight:500; cursor:pointer; border:none; border-right:1px solid var(--border); background:var(--green-pressed); color:#fff; font-family:'DM Sans',sans-serif;">
            Profile
        </button>
        <button onclick="switchTab('mytasks',this)" class="dash-tab"
                style="flex:1; padding:9px 0; text-align:center; font-size:13px; font-weight:500; cursor:pointer; border:none; border-right:1px solid var(--border); background:var(--surf); color:var(--tx2); font-family:'DM Sans',sans-serif;">
            My tasks
        </button>
        <button onclick="switchTab('completed',this)" class="dash-tab"
                style="flex:1; padding:9px 0; text-align:center; font-size:13px; font-weight:500; cursor:pointer; border:none; background:var(--surf); color:var(--tx2); font-family:'DM Sans',sans-serif;">
            Completed
        </button>
    </div>

    {{-- ── PROFILE TAB ── --}}
    <div id="tab-profile">

        {{-- Avatar + name + location card --}}
        <div style="background:var(--surf); border:1px solid var(--border); border-radius:10px; padding:20px; display:flex; gap:16px; align-items:flex-start; margin-bottom:16px;">
            {{-- Initials avatar: first letters of first and last name --}}
            <div style="width:56px; height:56px; border-radius:50%; background:var(--sage-lightest); color:var(--green-pressed); display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:500; flex-shrink:0;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
            </div>
            <div>
                <div style="font-family:'Lora',serif; font-size:17px; font-weight:600; color:var(--tx); margin-bottom:4px;">
                    {{ Auth::user()->name }}
                </div>
                <div style="font-size:13px; color:var(--tx2); line-height:1.7;">
                    📍 {{ Auth::user()->location ?? 'No location set' }}<br>
                    Member since {{ Auth::user()->created_at->format('F Y') }}
                </div>
            </div>
        </div>

        {{-- Stat counters --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:20px;">
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px; text-align:center;">
                <div style="font-size:22px; font-weight:500; color:var(--tx);">{{ $publishedCount }}</div>
                <div style="font-size:11px; color:var(--tx3); margin-top:2px;">Published</div>
            </div>
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px; text-align:center;">
                <div style="font-size:22px; font-weight:500; color:var(--tx);">{{ $offersCount }}</div>
                <div style="font-size:11px; color:var(--tx3); margin-top:2px;">Offers made</div>
            </div>
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px; text-align:center;">
                <div style="font-size:22px; font-weight:500; color:var(--tx);">{{ $completedCount }}</div>
                <div style="font-size:11px; color:var(--tx3); margin-top:2px;">Completed</div>
            </div>
        </div>

        {{-- Success flash after saving profile --}}
        @if(session('status') === 'profile-updated')
            <div style="background:var(--sage-lightest); border:1px solid var(--sage-mid); border-radius:8px; padding:10px 14px; margin-bottom:14px; font-size:13px; color:var(--green-pressed);">
                Changes saved successfully!
            </div>
        @endif

        {{-- Profile edit form --}}
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location"
                       value="{{ old('location', Auth::user()->location ?? '') }}"
                       placeholder="e.g. Rīgas centrs">
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age"
                       value="{{ old('age', Auth::user()->age ?? '') }}"
                       style="width:100px;" min="16" max="120">
            </div>

            <button type="submit" class="btn-offer" style="width:auto; padding:9px 20px;">
                Save changes
            </button>
        </form>

    </div>

    {{-- ── MY TASKS TAB ── --}}
    {{-- min-height prevents the empty state from squishing when there are no tasks --}}
    <div id="tab-mytasks" style="display:none; min-height:260px;">

        <div style="display:flex; justify-content:flex-end; margin-bottom:12px;">
            <a href="{{ route('tasks.create') }}"
               class="btn-offer" style="padding:8px 16px; text-decoration:none; font-size:13px;">
                <i class="ti ti-plus" style="vertical-align:-2px; margin-right:4px;"></i> New task
            </a>
        </div>

        @forelse($myTasks as $task)
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px 14px; margin-bottom:10px; display:flex; align-items:center; gap:12px;">
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:500; color:var(--tx); margin-bottom:3px;">{{ $task->title }}</div>
                    <div style="font-size:12px; color:var(--tx3);">📍 {{ $task->location }} · {{ $task->created_at->diffForHumans() }}</div>
                </div>
                <span style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:500; background:var(--sage-lightest); color:var(--green-pressed);">
                    Open
                </span>
                <div style="display:flex; gap:4px;">
                    <a href="{{ route('tasks.edit', $task) }}"
                       style="padding:5px 10px; border-radius:5px; background:var(--green-pressed); color:#fff; font-size:12px; font-weight:500; text-decoration:none;">
                        Edit
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                          onsubmit="return confirm('Delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="padding:5px 10px; border-radius:5px; border:none; background:var(--rose-deep); color:#fff; font-size:12px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="ti ti-clipboard-list"></i>
                <span>No tasks yet</span>
                <a href="{{ route('tasks.create') }}" style="font-size:13px; color:var(--sage-mid);">Create your first task</a>
            </div>
        @endforelse

    </div>

    {{-- ── COMPLETED TAB ── --}}
    <div id="tab-completed" style="display:none; min-height:260px;">

        @forelse($completedTasks as $task)
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px 14px; margin-bottom:10px; display:flex; align-items:center; gap:12px;">
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:500; color:var(--tx); margin-bottom:3px;">{{ $task->title }}</div>
                    <div style="font-size:12px; color:var(--tx3);">📍 {{ $task->location }} · Completed {{ $task->updated_at->format('M Y') }}</div>
                </div>
                <span style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:500; background:var(--beige); color:var(--tx2);">Done</span>
            </div>
        @empty
            <div class="empty-state">
                <i class="ti ti-circle-check"></i>
                <span>No completed tasks yet</span>
            </div>
        @endforelse

    </div>

</div>

@endsection

@push('scripts')
<script>
/**
 * switchTab(name, el)
 * Switches the visible profile tab.
 * - Resets all .dash-tab buttons to inactive style.
 * - Highlights the clicked button (el) in green.
 * - Hides all three tab panels, then shows the one matching `name`.
 */
function switchTab(name, el) {
    document.querySelectorAll('.dash-tab').forEach(t => {
        t.style.background = 'var(--surf)';
        t.style.color = 'var(--tx2)';
    });
    el.style.background = 'var(--green-pressed)';
    el.style.color = '#fff';
    ['profile','mytasks','completed'].forEach(id => {
        document.getElementById('tab-' + id).style.display = 'none';
    });
    document.getElementById('tab-' + name).style.display = 'block';
}

/**
 * On page load, read the ?tab= query parameter and activate the matching tab.
 * This allows sidebar links (e.g. ?tab=mytasks) to open directly to the right tab.
 * Falls back to the Profile tab if no param is present or the value is unrecognised.
 */
document.addEventListener('DOMContentLoaded', function () {
    const validTabs = ['profile', 'mytasks', 'completed'];
    const param = new URLSearchParams(window.location.search).get('tab');
    if (param && validTabs.includes(param)) {
        const buttons = document.querySelectorAll('.dash-tab');
        const idx = validTabs.indexOf(param);
        switchTab(param, buttons[idx]);
    }
});
</script>
@endpush
