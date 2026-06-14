
@extends('layouts.app')

@section('content')

<div style="display:flex; flex:1; height:calc(100vh - 0px); overflow:hidden;">

    {{-- Left panel: list of all conversations --}}
    @include('partials.conversation_list', ['matches' => $matches, 'activeMatch' => $match])

    <div style="flex:1; display:flex; flex-direction:column; overflow:hidden; background:var(--pg);">

        @php
            {{--
                Determine who the "other" person is:
                - Task owner  → talking to the helper (offer->user)
                - Helper      → talking to the task owner (task->user)
            --}}
            $other = $match->task->user_id === Auth::id()
                ? $match->offer->user
                : $match->task->user;
        @endphp

        {{-- Chat header: avatar, other user's name, task title, Mark complete button --}}
        <div style="padding:12px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px; background:var(--surf); flex-shrink:0;">
            {{-- Initials avatar: first letter of first + last name --}}
            <div style="width:32px; height:32px; border-radius:50%; background:var(--sage-lightest); color:var(--green-pressed); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:500; flex-shrink:0;">
                {{ strtoupper(substr($other->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $other->name)[1] ?? '', 0, 1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:14px; font-weight:500; color:var(--tx);">{{ $other->name }}</div>
                <div style="font-size:12px; color:var(--tx3);">{{ $match->task->title }}</div>
            </div>
            {{--
                Mark complete button — only the task owner sees this,
                and only while the task is still open.
                Submits PATCH to tasks.close which sets status = 'completed'.
            --}}
            @if($match->task->user_id === Auth::id() && $match->task->status === 'open')
                <form action="{{ route('tasks.close', $match->task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            style="padding:6px 12px; border-radius:6px; border:1px solid var(--sage-mid); background:var(--sage-lightest); color:var(--green-pressed); font-size:12px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif;">
                        <i class="ti ti-circle-check" style="vertical-align:-2px; margin-right:4px;"></i>
                        Mark complete
                    </button>
                </form>
            @endif
        </div>

        {{-- Message list — scrollable, auto-scrolled to bottom on load --}}
        <div style="flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:10px;" id="messageList">
            @forelse($messages as $msg)
                @php $isMine = $msg->sender_id === Auth::id(); @endphp
                {{-- My messages: right-aligned row; theirs: left-aligned --}}
                <div style="display:flex; gap:8px; align-items:flex-end; {{ $isMine ? 'flex-direction:row-reverse;' : '' }}">
                    {{-- Small avatar circle: "Me" for self, initial for other --}}
                    <div style="width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:500; flex-shrink:0;
                                background:{{ $isMine ? 'var(--green-pressed)' : 'var(--beige)' }};
                                color:{{ $isMine ? '#fff' : 'var(--tx2)' }};">
                        {{ $isMine ? 'Me' : strtoupper(substr($other->name, 0, 1)) }}
                    </div>
                    {{-- max-width on the column wrapper so the bubble and timestamp are both constrained --}}
                    <div style="display:flex; flex-direction:column; max-width:70%;">
                        {{-- Chat bubble: green (right) for mine, surface (left) for theirs --}}
                        <div style="padding:9px 12px; border-radius:14px; font-size:13px; line-height:1.5; word-break:break-word;
                            {{ $isMine
                                ? 'background:var(--green-pressed); color:#fff; border-bottom-right-radius:4px;'
                                : 'background:var(--surf); border:1px solid var(--border); color:var(--tx); border-bottom-left-radius:4px;' }}">
                            {{ $msg->content }}
                        </div>
                        {{-- Timestamp below the bubble --}}
                        <div style="font-size:10px; color:var(--tx3); margin-top:3px; {{ $isMine ? 'text-align:right;' : '' }}">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="ti ti-message-2"></i>
                    <span>No messages yet</span>
                    <span style="font-size:12px;">Say hello to get started!</span>
                </div>
            @endforelse
        </div>

        {{-- Message input — sticky at the bottom of the chat area --}}
        <div style="padding:12px 14px; border-top:1px solid var(--border); display:flex; gap:8px; align-items:flex-end; background:var(--surf); flex-shrink:0;">
            <form action="{{ route('chat.store', $match) }}" method="POST"
                  style="display:flex; gap:8px; align-items:flex-end; width:100%;" id="msgForm">
                @csrf
                {{--
                    Auto-growing textarea: oninput resets height to 'auto' then
                    sets it to scrollHeight (capped at 100px) so it expands as
                    the user types and collapses when text is deleted.
                --}}
                <textarea name="content" id="msgInput"
                          placeholder="Type a message…"
                          rows="1" required
                          style="flex:1; padding:9px 12px; border:1px solid var(--border); border-radius:20px; background:var(--pg); color:var(--tx); font-size:14px; font-family:'DM Sans',sans-serif; outline:none; resize:none; max-height:100px; transition:border-color .15s; line-height:1.5;"
                          onfocus="this.style.borderColor='var(--sage-mid)'"
                          onblur="this.style.borderColor='var(--border)'"
                          oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,100)+'px'"
                          onkeydown="handleKey(event)"></textarea>
                <button type="submit"
                        style="width:36px; height:36px; border-radius:50%; border:none; background:var(--green-pressed); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .15s;"
                        onmouseover="this.style.background='var(--green-hover)'"
                        onmouseout="this.style.background='var(--green-pressed)'"
                        aria-label="Send message">
                    <i class="ti ti-send"></i>
                </button>
            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Scroll the message list to the bottom on page load so the latest message is visible
const messageContainer = document.getElementById('messageList');
if (messageContainer) messageContainer.scrollTop = messageContainer.scrollHeight;

/**
 * handleKey(e)
 * Submits the message form when Enter is pressed without Shift.
 * Shift+Enter inserts a newline as normal.
 * Prevents submission if the textarea is empty or only whitespace.
 */
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        const val = document.getElementById('msgInput').value.trim();
        if (!val) return;
        document.getElementById('msgForm').submit();
    }
}
</script>
@endpush
