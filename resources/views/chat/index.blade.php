@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Messages</h1>
</div>

<div style="display:flex; flex:1; height:calc(100vh - 44px); overflow:hidden;">

    @include('partials.conversation_list', ['matches' => $matches, 'activeMatch' => null])

    <div class="empty-state" style="flex:1;">
        <i class="ti ti-message-2" aria-hidden="true"></i>
        <span>Select a conversation</span>
        <span style="font-size:12px;">Your matched neighbours will appear here</span>
    </div>

</div>

@endsection