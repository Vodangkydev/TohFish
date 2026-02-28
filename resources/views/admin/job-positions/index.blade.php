@extends('admin.layout')

@section('title', 'Quản lý Chức Vụ')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-user-tie"></i> Quản lý Chức vụ</h2>
    <div>
        <a href="{{ route('admin.cvs.index') }}" class="btn btn-info me-2">
            <i class="fas fa-file-alt"></i> Quản lý CVs
        </a>
        <a href="{{ route('admin.job-positions.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm chức vụ
        </a>
    </div>
</div>

<div class="admin-card">
    <form method="GET" action="{{ route('admin.job-positions.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm chức vụ, nội dung..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Ngày đăng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $pos)
                <tr>
                    <td><b>{{ $pos->title }}</b></td>
                    <td style="min-width: 350px;">
                        <div style="max-width:420px;white-space: pre-line;overflow: hidden;text-overflow: ellipsis;">{!! Str::limit(strip_tags($pos->content), 100) !!}</div>
                    </td>
                    <td>{{ $pos->published_at ? $pos->published_at->format('d/m/Y') : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.job-positions.edit', $pos->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('admin.job-positions.destroy', $pos->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted">Chưa có chức vụ nào</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2">
            {{ $positions->links() }}
        </div>
    </div>
</div>
@endsection

