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

            {{-- Optional photo --}}
            @if($task->photo)
                <div class="task-img-wrap">
                    <img src="{{ asset('storage/' . $task->photo) }}" alt="Task photo">
                </div>
            @endif

            <div class="task-info">

                {{-- Distance badge --}}
                <div class="dist-badge">
                    <i class="ti ti-map-pin" aria-hidden="true"></i>
                    {{ $task->distance_label }}
                </div>

                {{-- Description with clickable poster name --}}
                <p class="task-line">
                    <strong>
                        <span class="poster-link"
                              onclick="showPosterPopup(
                                  '{{ $task->user->name }}',
                                  {{ $task->user->age ?? 'null' }},
                                  '{{ $task->user->location ?? '' }}',
                                  {{ $task->user->published_tasks_count }},
                                  {{ $task->user->offers_count }},
                                  {{ $task->user->completed_tasks_count }},
                                  '{{ $task->user->created_at->format('M jS Y') }}'
                              )">
                            {{ $task->user->name }}
                        </span>:
                    </strong>
                    {{ $task->description }}
                </p>

                <p class="task-line">
                    <strong>Location:</strong> {{ $task->distance_label }}
                </p>

                <p class="task-line">
                    <strong>Time:</strong> {{ $task->time_label }}
                </p>

                {{-- Action buttons --}}
                <div class="task-actions">

                    {{-- Offer to help --}}
                    <form action="{{ route('swipe.store') }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <input type="hidden" name="direction" value="right">
                        <button type="submit" class="btn-offer" style="width:100%;">
                            Offer to help
                        </button>
                    </form>

                    {{-- View next task --}}
                    <form action="{{ route('swipe.store') }}" method="POST" style="flex:1;">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <input type="hidden" name="direction" value="left">
                        <button type="submit" class="btn-skip" style="width:100%;">
                            View next task
                        </button>
                    </form>

                </div>

            </div>
        </div>

    @endif

</div>

@endsection

@push('scripts')
<script>
// Poster data passed from controller for the popup
function showPosterPopup(name, age, location, published, offers, completed, since) {
    document.getElementById('popupName').textContent = 'About ' + name;
    document.getElementById('popupDetails').innerHTML =
        (age ? 'Age: ' + age + '<br>' : '') +
        (location ? 'Location: ' + location : '');
    document.getElementById('popupStats').innerHTML =
        'Published tasks: ' + published + '<br>' +
        'Offers to help: ' + offers + '<br>' +
        'Completed tasks: ' + completed;
    document.getElementById('popupSince').innerHTML = 'Member since:<br>' + since;
    document.getElementById('posterPopup').classList.add('open');
}
</script>
@endpush
