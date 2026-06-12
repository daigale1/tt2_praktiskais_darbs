@extends('layouts.app')

@section('content')

<div style="display:flex;flex:1;height:calc(100vh - 0px);overflow:hidden;">

    {{-- Conversation list (same as index, repeated for layout) --}}
    <div style="width:220px;min-width:220px;border-right:1px solid var(--border);background:var(--surf);display:flex;flex-direction:column;">

        <div style="padding:10px 14px;border-bottom:1px solid var(--border);font-size:13px;font-weight:500;color:var(--tx2);">
            <i class="ti ti-messages" style="vertical-align:-2px;margin-right:5px;" aria-hidden="true"></i>Chats
        </div>

        @foreach($matches as $m)
            @php
                $other = $m->task->user_id === Auth::id() ? $m->helper : $m->task->user;
                $lastMsg = $m->messages->last();
                $unread  = $m->messages->where('sender_id', '!=', Auth::id())->where('read', false)->count();
                $isActive = $m->id === $match->id;
            @endphp
            <a href="{{ route('chat.show', $m) }}"
               style="display:block;padding:10px 14px;cursor:pointer;border-left:3px solid {{ $isActive ? 'var(--sage-mid)' : 'transparent' }};background:{{ $isActive ? 'var(--sage-lightest)' : 'transparent' }};border-bottom:0.5px solid var(--border);text-decoration:none;transition:background .12s;">
                <div style="font-size:11px;color:var(--sage-mid);margin-bottom:2px;">{{ $m->task->title }}</div>
                <div style="font-size:13px;font-weight:500;color:var(--tx);margin-bottom:2px;">
                    {{ $other->name }}
                    @if($unread > 0)
                        <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--green-pressed);margin-left:6px;vertical-align:2px;"></span>
                    @endif
                </div>
                <div style="font-size:12px;color:var(--tx3);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px;">
                    {{ $lastMsg ? $lastMsg->content : 'No messages yet' }}
                </div>
            </a>
        @endforeach

    </div>

    {{-- Active chat --}}
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden;background:var(--pg);">

        {{-- Chat header --}}
        <div style="padding:12px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;background:var(--surf);flex-shrink:0;">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--sage-lightest);color:var(--green-pressed);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:500;flex-shrink:0;">
                {{ strtoupper(substr($other->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $other->name)[1] ?? '', 0, 1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:500;color:var(--tx);">{{ $other->name }}</div>
                <div style="font-size:12px;color:var(--tx3);">{{ $match->task->title }}</div>
            </div>
            {{-- Mark task as complete (only task owner) --}}
            @if($match->task->user_id === Auth::id() && $match->task->status === 'open')
                <form action="{{ route('tasks.close', $match->task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            style="padding:6px 12px;border-radius:6px;border:1px solid var(--sage-mid);background:var(--sage-lightest);color:var(--green-pressed);font-size:12px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;">
                        <i class="ti ti-circle-check" style="vertical-align:-2px;margin-right:4px;" aria-hidden="true"></i>
                        Mark complete
                    </button>
                </form>
            @endif
        </div>

        {{-- Messages --}}
        <div style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px;" id="messageList">

            @forelse($messages as $msg)
                @php $isMine = $msg->sender_id === Auth::id(); @endphp
                <div style="display:flex;gap:8px;align-items:flex-end;{{ $isMine ? 'flex-direction:row-reverse;' : '' }}">

                    {{-- Avatar --}}
                    <div style="width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:500;flex-shrink:0;background:{{ $isMine ? 'var(--green-pressed)' : 'var(--beige)' }};color:{{ $isMine ? '#fff' : 'var(--tx2)' }};">
                        {{ $isMine ? 'Me' : strtoupper(substr($other->name,0,1)) }}
                    </div>

                    <div style="display:flex;flex-direction:column;">
                        {{-- Bubble --}}
                        <div style="max-width:70%;padding:9px 12px;border-radius:14px;font-size:13px;line-height:1.5;
                            {{ $isMine
                                ? 'background:var(--green-pressed);color:#fff;border-bottom-right-radius:4px;'
                                : 'background:var(--surf);border:1px solid var(--border);color:var(--tx);border-bottom-left-radius:4px;' }}">
                            {{ $msg->content }}
                        </div>
                        {{-- Timestamp --}}
                        <div style="font-size:10px;color:var(--tx3);margin-top:3px;{{ $isMine ? 'text-align:right;' : '' }}">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>

                </div>
            @empty
                <div class="empty-state">
                    <i class="ti ti-message-2" aria-hidden="true"></i>
                    <span>No messages yet</span>
                    <span style="font-size:12px;">Say hello to get started!</span>
                </div>
            @endforelse

        </div>

        {{-- Message input --}}
        <div style="padding:12px 14px;border-top:1px solid var(--border);display:flex;gap:8px;align-items:flex-end;background:var(--surf);flex-shrink:0;">
            <form action="{{ route('chat.store', $match) }}" method="POST"
                  style="display:flex;gap:8px;align-items:flex-end;width:100%;"
                  id="msgForm">
                @csrf
                <textarea name="content"
                          id="msgInput"
                          placeholder="Type a message…"
                          rows="1"
                          required
                          style="flex:1;padding:9px 12px;border:1px solid var(--border);border-radius:20px;background:var(--pg);color:var(--tx);font-size:14px;font-family:'DM Sans',sans-serif;outline:none;resize:none;max-height:100px;transition:border-color .15s;line-height:1.5;"
                          onfocus="this.style.borderColor='var(--sage-mid)'"
                          onblur="this.style.borderColor='var(--border)'"
                          oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,100)+'px'"
                          onkeydown="handleKey(event)"></textarea>
                <button type="submit"
                        style="width:36px;height:36px;border-radius:50%;border:none;background:var(--green-pressed);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s;"
                        onmouseover="this.style.background='var(--green-hover)'"
                        onmouseout="this.style.background='var(--green-pressed)'"
                        aria-label="Send message">
                    <i class="ti ti-send" aria-hidden="true"></i>
                </button>
            </form>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
// Scroll to bottom on load
const list = document.getElementById('messageList');
if (list) list.scrollTop = list.scrollHeight;

// Send on Enter (Shift+Enter for new line)
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('msgForm').submit();
    }
}
</script>
@endpush
