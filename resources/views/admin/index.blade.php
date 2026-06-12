@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Admin panel</h1>
</div>

<div style="padding:20px 24px; max-width:760px; margin:0 auto;">

    <div style="display:flex; border:1px solid var(--border); border-radius:8px; overflow:hidden; margin-bottom:20px;">
        <button onclick="switchTab('tasks',this)" class="admin-tab"
                style="flex:1; padding:9px 0; text-align:center; font-size:13px; font-weight:500; cursor:pointer; border:none; border-right:1px solid var(--border); background:var(--green-pressed); color:#fff; font-family:'DM Sans',sans-serif;">
            All tasks
        </button>
        <button onclick="switchTab('users',this)" class="admin-tab"
                style="flex:1; padding:9px 0; text-align:center; font-size:13px; font-weight:500; cursor:pointer; border:none; background:var(--surf); color:var(--tx2); font-family:'DM Sans',sans-serif;">
            Users
        </button>
    </div>

    @if(session('success'))
        <div style="background:var(--sage-lightest); border:1px solid var(--sage-mid); border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:var(--green-pressed);">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tasks tab --}}
    <div id="tab-tasks">
        <p style="font-size:13px; color:var(--tx3); margin-bottom:14px;">{{ $tasks->total() }} tasks in the system</p>

        @forelse($tasks as $task)
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px 14px; margin-bottom:10px; display:flex; align-items:flex-start; gap:12px;">
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:500; color:var(--tx); margin-bottom:3px;">{{ $task->title }}</div>
                    <div style="font-size:12px; color:var(--tx3); margin-bottom:4px;">
                        Posted by <strong>{{ $task->user->name }}</strong>
                        · 🖈 {{ $task->location }}
                        · {{ $task->created_at->diffForHumans() }}
                    </div>
                    <div style="font-size:12px; color:var(--tx2);">{{ Str::limit($task->description, 100) }}</div>
                </div>
                <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-end; flex-shrink:0;">
                    <span style="font-size:11px; padding:3px 8px; border-radius:4px; font-weight:500;
                        background:{{ $task->status === 'open' ? 'var(--sage-lightest)' : 'var(--beige)' }};
                        color:{{ $task->status === 'open' ? 'var(--green-pressed)' : 'var(--tx2)' }};">
                        {{ ucfirst($task->status) }}
                    </span>
                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST"
                          onsubmit="return confirm('Remove this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="padding:5px 10px; border-radius:5px; border:none; background:var(--rose-deep); color:#fff; font-size:12px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="ti ti-clipboard-list"></i>
                <span>No tasks in the system</span>
            </div>
        @endforelse

        <div style="margin-top:16px;">{{ $tasks->links() }}</div>
    </div>

    {{-- Users tab --}}
    <div id="tab-users" style="display:none;">
        <p style="font-size:13px; color:var(--tx3); margin-bottom:14px;">{{ $users->total() }} registered users</p>

        @forelse($users as $user)
            <div style="background:var(--surf); border:1px solid var(--border); border-radius:8px; padding:12px 14px; margin-bottom:10px; display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:50%; background:var(--sage-lightest); color:var(--green-pressed); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:500; flex-shrink:0;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:500; color:var(--tx); margin-bottom:2px;">
                        {{ $user->name }}
                        @if($user->hasRole('admin'))
                            <span style="font-size:10px; background:var(--beige); color:var(--tx2); padding:2px 6px; border-radius:4px; margin-left:4px;">Admin</span>
                        @endif
                    </div>
                    <div style="font-size:12px; color:var(--tx3);">
                        {{ $user->email }} · Member since {{ $user->created_at->format('M Y') }}
                    </div>
                </div>
                <div>
                    @if($user->blocked_at)
                        <form action="{{ route('admin.users.block', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="unblock">
                            <button type="submit"
                                    style="padding:5px 10px; border-radius:5px; border:1px solid var(--border); background:var(--surf); color:var(--tx2); font-size:12px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                                Unblock
                            </button>
                        </form>
                    @elseif(!$user->hasRole('admin'))
                        <form action="{{ route('admin.users.block', $user) }}" method="POST"
                              onsubmit="return confirm('Block {{ $user->name }}?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="block">
                            <button type="submit"
                                    style="padding:5px 10px; border-radius:5px; border:none; background:var(--rose-deep); color:#fff; font-size:12px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                                Block
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="ti ti-users"></i>
                <span>No users found</span>
            </div>
        @endforelse

        <div style="margin-top:16px;">{{ $users->links() }}</div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function switchTab(name, el) {
    document.querySelectorAll('.admin-tab').forEach(t => {
        t.style.background = 'var(--surf)';
        t.style.color = 'var(--tx2)';
    });
    el.style.background = 'var(--green-pressed)';
    el.style.color = '#fff';
    ['tasks','users'].forEach(id => {
        document.getElementById('tab-' + id).style.display = 'none';
    });
    document.getElementById('tab-' + name).style.display = 'block';
}
</script>
@endpush