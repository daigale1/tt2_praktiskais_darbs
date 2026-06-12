@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Edit task</h1>
</div>

<div class="task-feed">
    <div class="form-card" style="max-width:560px;">

        @if($errors->any())
            <div style="background:var(--rose-lightest);border:1px solid var(--rose-deep);border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:var(--rose-text);">
                <strong>Please fix the following:</strong>
                <ul style="margin:6px 0 0 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="form-group">
                <label for="title">Task title</label>
                <input type="text" id="title" name="title"
                       placeholder="e.g. Help carry groceries"
                       maxlength="80"
                       value="{{ old('title', $task->title) }}"
                       oninput="updateChar('title','titleCount',80)"
                       style="{{ $errors->has('title') ? 'border-color:var(--rose-deep);' : '' }}"
                       required>
                <div class="char-count"><span id="titleCount">{{ strlen(old('title', $task->title)) }}</span>/80</div>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                          placeholder="Describe what you need help with..."
                          maxlength="400"
                          oninput="updateChar('description','descCount',400)"
                          style="{{ $errors->has('description') ? 'border-color:var(--rose-deep);' : '' }}"
                          required>{{ old('description', $task->description) }}</textarea>
                <div class="char-count"><span id="descCount">{{ strlen(old('description', $task->description)) }}</span>/400</div>
            </div>

            {{-- Location + Time --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location"
                           placeholder="e.g. Rīgas centrs"
                           value="{{ old('location', $task->location) }}"
                           required>
                </div>
                <div class="form-group">
                    <label for="available_time">
                        Time
                        <span style="font-size:11px;color:var(--tx3);font-weight:400;">(optional)</span>
                    </label>
                    <input type="text" id="available_time" name="available_time"
                           placeholder="e.g. Whenever you can"
                           value="{{ old('available_time', $task->available_time) }}">
                </div>
            </div>

            {{-- Current photo --}}
            @if($task->photo)
                <div class="form-group">
                    <label>Current photo</label>
                    <div style="position:relative;border-radius:8px;overflow:hidden;margin-bottom:8px;">
                        <img src="{{ asset('storage/' . $task->photo) }}" alt="Current photo"
                             style="width:100%;height:160px;object-fit:cover;display:block;border-radius:8px;">
                    </div>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--tx2);font-weight:400;cursor:pointer;">
                        <input type="checkbox" name="remove_photo" value="1" {{ old('remove_photo') ? 'checked' : '' }}>
                        Remove current photo
                    </label>
                </div>
            @endif

            {{-- New photo upload --}}
            <div class="form-group">
                <label>
                    {{ $task->photo ? 'Replace photo' : 'Photo' }}
                    <span style="font-size:11px;color:var(--tx3);font-weight:400;">(optional)</span>
                </label>
                <div id="uploadBox"
                     style="border:1.5px dashed var(--border);border-radius:8px;padding:20px;text-align:center;cursor:pointer;background:var(--surf);"
                     onclick="document.getElementById('photoInput').click()">
                    <i class="ti ti-photo-plus" aria-hidden="true" style="font-size:24px;color:var(--tx3);display:block;margin-bottom:6px;"></i>
                    <span style="font-size:13px;color:var(--tx3);">Click to upload a new photo</span>
                </div>
                <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;" onchange="previewPhoto(this)">
                <div id="previewWrap" style="display:none;position:relative;margin-top:10px;border-radius:8px;overflow:hidden;">
                    <img id="previewImg" src="" alt="Preview"
                         style="width:100%;height:160px;object-fit:cover;display:block;border-radius:8px;">
                    <button type="button" onclick="removePhoto()" aria-label="Remove"
                            style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.5);border:none;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="task-actions" style="margin-top:20px;">
                <button type="submit" class="btn-offer">Save changes</button>
                <a href="{{ route('profile.show') }}" class="btn-skip"
                   style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updateChar(inputId, countId, max) {
    document.getElementById(countId).textContent = document.getElementById(inputId).value.length;
}
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'block';
        document.getElementById('uploadBox').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
function removePhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('previewWrap').style.display = 'none';
    document.getElementById('uploadBox').style.display = 'block';
}
document.addEventListener('DOMContentLoaded', () => {
    updateChar('title', 'titleCount', 80);
    updateChar('description', 'descCount', 400);
});
</script>
@endpush
