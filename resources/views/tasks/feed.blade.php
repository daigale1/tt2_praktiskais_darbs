@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Available tasks near you</h1>
</div>

<div class="task-feed">

    @if($tasks->isEmpty())
        <div class="empty-state">
            <i class="ti ti-map-search" aria-hidden="true"></i>
            <span>No more tasks nearby.</span>
            <span style="font-size:13px;">Check back later!</span>
        </div>
    @else

        @php $task = $tasks->first() @endphp

        <div class="task-card" id="taskCard">

            {{-- photo_path is the correct column name in Deniss's migration --}}
            @if($task->photo_path)
                <div class="task-img-wrap">
                    <img src="{{ asset('storage/' . $task->photo_path) }}" alt="Task photo">
                </div>
            @endif

            <div class="task-info">

                <div class="dist-badge">
                    <i class="ti ti-map-pin" aria-hidden="true"></i>
                    {{ $task->distance_label }}
                </div>

                <p class="task-line">
                    <strong>
                        <span class="poster-link"
                              onclick="showPosterPopup(
                                  '{{ addslashes($task->user->name) }}',
                                  {{ $task->user->age ?? 'null' }},
                                  '{{ addslashes($task->user->location ?? '') }}',
                                  {{ $task->user->published_tasks_count ?? 0 }},
                                  {{ $task->user->offers_count ?? 0 }},
                                  {{ $task->user->completed_tasks_count ?? 0 }},
                                  '{{ $task->user->created_at->format('M jS Y') }}'
                              )">
                            {{ $task->user->name }}
                        </span>:
                    </strong>
                    {{ $task->description }}
                </p>

                <p class="task-line">
                    <strong>Location:</strong> {{ $task->location }}
                </p>

                <p class="task-line">
                    <strong>Time:</strong>
                    {{ $task->scheduled_at ? $task->scheduled_at->format('D, M j · H:i') : 'Whenever you can' }}
                </p>

                <div class="task-actions">

                    <form action="{{ route('swipe.store') }}" method="POST" style="flex:1;"
                          onsubmit="return animateCardOut(this);">
                        @csrf
                        <input type="hidden" name="task_id"  value="{{ $task->id }}">
                        <input type="hidden" name="direction" value="right">
                        <button type="submit" class="btn-offer" style="width:100%;">Offer to help</button>
                    </form>

                    <form action="{{ route('swipe.store') }}" method="POST" style="flex:1;"
                          onsubmit="return animateCardOut(this);">
                        @csrf
                        <input type="hidden" name="task_id"  value="{{ $task->id }}">
                        <input type="hidden" name="direction" value="left">
                        <button type="submit" class="btn-skip" style="width:100%;">View next task</button>
                    </form>

                </div>
            </div>
        </div>

    @endif
</div>

@endsection

@push('scripts')
<script>
function showPosterPopup(name, age, location, published, offers, completed, since) {
    document.getElementById('popupName').textContent = 'About ' + name;
    document.getElementById('popupDetails').innerHTML =
        (age      ? 'Age: '      + age      + '<br>' : '') +
        (location ? 'Location: ' + location          : '');
    document.getElementById('popupStats').innerHTML =
        'Published tasks: ' + published + '<br>' +
        'Offers to help: '  + offers    + '<br>' +
        'Completed tasks: ' + completed;
    document.getElementById('popupSince').innerHTML = 'Member since:<br>' + since;
    document.getElementById('posterPopup').classList.add('open');
}
</script>
@endpush