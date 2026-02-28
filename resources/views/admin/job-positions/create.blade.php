@extends('admin.layout')

@section('title', 'Thêm Chức Vụ')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-plus"></i> Thêm chức vụ</h2>
    <a href="{{ route('admin.job-positions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.job-positions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
            <textarea name="content" id="content" rows="6" class="form-control @error('content') is-invalid @enderror" style="font-weight: bold; border-left: 2px solid #43b;"><b>{{ old('content') }}</b></textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="small text-muted mt-1">Thông tin này sẽ được in đậm trong danh mục chức vụ.</div>
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Ngày đăng</label>
            <input type="date" id="published_at" name="published_at" value="{{ old('published_at') ?? now()->format('Y-m-d') }}" class="form-control">
        </div>
        <div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu chức vụ</button>
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


