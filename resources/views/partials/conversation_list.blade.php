<div style="width:220px; min-width:220px; border-right:1px solid var(--border); background:var(--surf); display:flex; flex-direction:column;">

    <div style="padding:10px 14px; border-bottom:1px solid var(--border); font-size:13px; font-weight:500; color:var(--tx2);">
        <i class="ti ti-messages" style="vertical-align:-2px; margin-right:5px;"></i> Chats
    </div>

    @forelse($matches as $conv)
        @php
            $other    = $conv->task->user_id === Auth::id() ? $conv->offer->user : $conv->task->user;
            $lastMsg  = $conv->messages->last();
            $isActive = isset($activeMatch) && $conv->id === $activeMatch->id;
        @endphp

        <a href="{{ route('chat.show', $conv) }}"
           style="display:block; padding:10px 14px;
                  border-left:3px solid {{ $isActive ? 'var(--sage-mid)' : 'transparent' }};
                  background:{{ $isActive ? 'var(--sage-lightest)' : 'transparent' }};
                  border-bottom:0.5px solid var(--border);
                  text-decoration:none; transition:background .12s;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:6px; margin-bottom:2px;">
                <div style="font-size:11px; color:var(--sage-mid); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $conv->task->title }}</div>
                @if($conv->status === 'completed')
                    <span style="font-size:10px; font-weight:500; color:var(--green-pressed); flex-shrink:0;">✓ Done</span>
                @elseif($conv->status === 'cancelled')
                    <span style="font-size:10px; font-weight:500; color:var(--tx3); flex-shrink:0;">Closed</span>
                @endif
            </div>
            <div style="font-size:13px; font-weight:500; color:var(--tx); margin-bottom:2px;">{{ $other->name }}</div>
            <div style="font-size:12px; color:var(--tx3); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:170px;">
                {{ $lastMsg ? $lastMsg->content : 'No messages yet' }}
            </div>
        </a>

    @empty
        <div style="padding:20px 14px; font-size:13px; color:var(--tx3); text-align:center;">
            No conversations yet.<br>Offer to help on a task to start chatting!
        </div>
    @endforelse

</div>