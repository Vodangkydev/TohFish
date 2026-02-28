@extends('admin.layout')

@section('title', 'Chỉnh sửa chức vụ')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-edit"></i> Chỉnh sửa chức vụ</h2>
    <a href="{{ route('admin.job-positions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>
<div class="admin-card">
    <form action="{{ route('admin.job-positions.update', $position->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $position->title) }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
            <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" style="font-weight:bold;border-left: 2px solid #43b;">{{ old('content', $position->content) }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Ngày đăng</label>
            <input type="date" id="published_at" name="published_at" value="{{ old('published_at', $position->published_at ? $position->published_at->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control">
        </div>
        <div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Cập nhật</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>
@endpush


