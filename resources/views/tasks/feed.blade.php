{{--
    feed/index.blade.php
    ─────────────────────
    The task discovery feed — shows one task at a time as a card that the
    user can act on. Passed from FeedController@index.
--}}

@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Available tasks near you</h1>
</div>

<div class="task-feed">

    @if($tasks->isEmpty())
        {{-- No tasks left to show --}}
        <div class="empty-state">
            <i class="ti ti-map-search" aria-hidden="true"></i>
            <span>No more tasks nearby.</span>
            <span style="font-size:13px;">Check back later!</span>
        </div>
    @else

        {{-- Always show only the first task — the feed is one card at a time --}}
        @php $task = $tasks->first() @endphp

        <div class="task-card" id="taskCard">

            {{-- Optional task photo, stored via Laravel's public disk --}}
            @if($task->photo_path)
                <div class="task-img-wrap">
                    <img src="{{ asset('storage/' . $task->photo_path) }}" alt="Task photo">
                </div>
            @endif

            <div class="task-info">

                {{-- Distance badge — value comes from the distance_label accessor on Task --}}
                <div class="dist-badge">
                    <i class="ti ti-map-pin" aria-hidden="true"></i>
                    {{ $task->distance_label }}
                </div>

                {{--
                    Poster name — clicking opens the global popup overlay.
                    All data is passed inline as JS arguments so no extra
                    AJAX request is needed.
                --}}
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

                {{-- scheduled_at is cast to Carbon on the Task model --}}
                <p class="task-line">
                    <strong>Time:</strong>
                    {{ $task->scheduled_at ? $task->scheduled_at->format('D, M j · H:i') : 'Whenever you can' }}
                </p>

                <div class="task-actions">

                    {{--
                        Offer to help (swipe right):
                        SwipeController creates an Offer + TaskMatch record,
                        then redirects to the new chat conversation.
                    --}}
                    <form action="{{ route('swipe.store') }}" method="POST" style="flex:1;"
                          onsubmit="return animateCardOut(this);">
                        @csrf
                        <input type="hidden" name="task_id"  value="{{ $task->id }}">
                        <input type="hidden" name="direction" value="right">
                        <button type="submit" class="btn-offer" style="width:100%;">Offer to help</button>
                    </form>

                    {{--
                        Skip (swipe left):
                        SwipeController records the skip so this task won't
                        appear again, then reloads the feed with the next task.
                    --}}
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
/**
 * showPosterPopup(name, age, location, published, offers, completed, since)
 * Populates and opens the global poster popup overlay defined in app.blade.php.
 * Called when a user clicks a poster's name on the task card.
 *
 * @param {string}      name       Poster's full name
 * @param {number|null} age        Poster's age (null if not set)
 * @param {string}      location   Poster's location string (empty if not set)
 * @param {number}      published  Count of tasks the poster has published
 * @param {number}      offers     Count of offers the poster has made
 * @param {number}      completed  Count of tasks the poster has completed
 * @param {string}      since      Formatted join date string (e.g. "Jan 1st 2024")
 */
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