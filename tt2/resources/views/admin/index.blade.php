@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Messages</h1>
</div>

<div style="display:flex;flex:1;height:calc(100vh - 44px);overflow:hidden;">

    {{-- Conversation list --}}
    <div style="width:220px;min-width:220px;border-right:1px solid var(--border);background:var(--surf);display:flex;flex-direction:column;">

        <div style="padding:10px 14px;border-bottom:1px solid var(--border);font-size:13px;font-weight:500;color:var(--tx2);">
            <i class="ti ti-messages" style="vertical-align:-2px;margin-right:5px;" aria-hidden="true"></i>Chats
        </div>

        @forelse($matches as $match)
            @php
                $other = $match->task->user_id === Auth::id()
                    ? $match->helper
                    : $match->task->user;
                $lastMsg = $match->messages->last();
                $unread  = $match->messages->where('sender_id', '!=', Auth::id())->where('read', false)->count();
            @endphp
            <a href="{{ route('chat.show', $match) }}"
               style="display:block;padding:10px 14px;cursor:pointer;border-left:3px solid {{ request()->route('match')?->id === $match->id ? 'var(--sage-mid)' : 'transparent' }};background:{{ request()->route('match')?->id === $match->id ? 'var(--sage-lightest)' : 'transparent' }};border-bottom:0.5px solid var(--border);text-decoration:none;transition:background .12s;">
                <div style="font-size:11px;color:var(--sage-mid);margin-bottom:2px;">{{ $match->task->title }}</div>
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
        @empty
            <div style="padding:20px 14px;font-size:13px;color:var(--tx3);text-align:center;">
                No conversations yet.<br>Offer to help on a task to start chatting!
            </div>
        @endforelse

    </div>

    {{-- Empty state when no chat selected --}}
    <div class="empty-state" style="flex:1;">
        <i class="ti ti-message-2" aria-hidden="true"></i>
        <span>Select a conversation</span>
        <span style="font-size:12px;">Your matched neighbours will appear here</span>
    </div>

</div>

@endsection
