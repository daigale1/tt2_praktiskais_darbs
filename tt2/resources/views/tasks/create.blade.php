@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Create a new task</h1>
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

        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" id="taskForm">
            @csrf

            {{-- Title --}}
            <div class="form-group">
                <label for="title">Task title</label>
                <input type="text"
                       id="title"
                       name="title"
                       placeholder="e.g. Help carry groceries"
                       maxlength="80"
                       value="{{ old('title') }}"
                       oninput="updateChar('title','titleCount',80)"
                       style="{{ $errors->has('title') ? 'border-color:var(--rose-deep);' : '' }}"
                       required>
                <div class="char-count"><span id="titleCount">{{ strlen(old('title','')) }}</span>/80</div>
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description"
                          name="description"
                          placeholder="Describe what you need help with..."
                          maxlength="400"
                          oninput="updateChar('description','descCount',400)"
                          style="{{ $errors->has('description') ? 'border-color:var(--rose-deep);' : '' }}"
                          required>{{ old('description') }}</textarea>
                <div class="char-count"><span id="descCount">{{ strlen(old('description','')) }}</span>/400</div>
            </div>

            {{-- Location + Time --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text"
                           id="location"
                           name="location"
                           placeholder="e.g. Rīgas centrs"
                           value="{{ old('location', Auth::user()->location) }}"
                           style="{{ $errors->has('location') ? 'border-color:var(--rose-deep);' : '' }}"
                           required>
                </div>
                <div class="form-group">
                    <label for="available_time">
                        Time
                        <span style="font-size:11px;color:var(--tx3);font-weight:400;">(optional)</span>
                    </label>
                    <input type="text"
                           id="available_time"
                           name="available_time"
                           placeholder="e.g. Whenever you can"
                           value="{{ old('available_time') }}">
                </div>
            </div>

            {{-- Photo upload --}}
            <div class="form-group">
                <label>
                    Photo
                    <span style="font-size:11px;color:var(--tx3);font-weight:400;">(optional)</span>
                </label>

                <div id="uploadBox"
                     style="border:1.5px dashed var(--border);border-radius:8px;padding:24px;text-align:center;cursor:pointer;background:var(--surf);transition:border-color .15s,background .15s;"
                     onclick="document.getElementById('photoInput').click()"
                     onmouseover="this.style.borderColor='var(--sage-mid)';this.style.background='var(--sage-lightest)'"
                     onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surf)'">
                    <i class="ti ti-photo-plus" aria-hidden="true" style="font-size:28px;color:var(--tx3);display:block;margin-bottom:8px;"></i>
                    <span style="font-size:13px;color:var(--tx3);">Click to upload a photo</span>
                    <div style="font-size:11px;color:var(--tx3);margin-top:4px;">JPG, PNG up to 5MB</div>
                </div>

                <input type="file"
                       id="photoInput"
                       name="photo"
                       accept="image/*"
                       style="display:none;"
                       onchange="previewPhoto(this)">

                <div id="previewWrap" style="display:none;position:relative;margin-top:10px;border-radius:8px;overflow:hidden;">
                    <img id="previewImg" src="" alt="Preview"
                         style="width:100%;height:160px;object-fit:cover;display:block;border-radius:8px;">
                    <button type="button"
                            onclick="removePhoto()"
                            aria-label="Remove photo"
                            style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.5);border:none;border-radius:50%;width:26px;height:26px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff;font-size:14px;">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="task-actions" style="margin-top:20px;">
                <button type="submit" class="btn-offer">Post task</button>
                <a href="{{ route('feed.index') }}" class="btn-skip" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
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

// Init char counts from old() values
document.addEventListener('DOMContentLoaded', () => {
    updateChar('title', 'titleCount', 80);
    updateChar('description', 'descCount', 400);
});
</script>
@endpush
