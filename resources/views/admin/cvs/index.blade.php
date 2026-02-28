@extends('admin.layout')

@section('title', 'Quản lý CV')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-file-alt"></i> Quản lý CV</h2>
</div>

<div class="admin-card">
    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.cvs.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email, SĐT..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="job_id" class="form-select">
                    <option value="">Tất cả công việc</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->job_id }}" {{ request('job_id') == $job->job_id ? 'selected' : '' }}>
                            Job #{{ $job->job_id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.cvs.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-redo"></i> Làm mới
                </a>
            </div>
        </div>
    </form>

    <!-- CVs Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Tuổi</th>
                    <th>Giới tính</th>
                    <th>Công việc</th>
                    <th>File CV</th>
                    <th>Ngày gửi</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cvs as $cv)
                <tr>
                    <td>{{ $cv->cvs_id }}</td>
                    <td>{{ $cv->ho_ten }}</td>
                    <td>{{ $cv->email }}</td>
                    <td>{{ $cv->phone }}</td>
                    <td>{{ $cv->age }}</td>
                    <td>
                        @if($cv->sex == 'male')
                            <span class="badge bg-primary">Nam</span>
                        @elseif($cv->sex == 'female')
                            <span class="badge bg-danger">Nữ</span>
                        @else
                            <span class="badge bg-secondary">Khác</span>
                        @endif
                    </td>
                    <td>
                        @if($cv->applied_position)
                            <span class="badge bg-info">{{ $cv->applied_position }}</span>
                        @elseif($cv->job)
                            <span class="badge bg-info">Job #{{ $cv->job->job_id }}</span>
                        @else
                            <span class="text-muted">Không xác định</span>
                        @endif
                    </td>
                    <td>
                        @if($cv->file_path)
                            <a href="{{ route('admin.cvs.view', $cv->cvs_id) }}" target="_blank" class="btn btn-sm btn-secondary" title="Xem CV">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.cvs.download', $cv->cvs_id) }}" class="btn btn-sm btn-primary" download title="Tải CV">
                                <i class="fas fa-download"></i>
                            </a>
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </td>
                    <td>{{ $cv->created_at ? $cv->created_at->format('d/m/Y H:i') : '' }}</td>
                    <td>
                        <a href="{{ route('admin.cvs.show', $cv->cvs_id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.cvs.destroy', $cv->cvs_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa CV này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <p class="text-muted">Chưa có CV nào được gửi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($cvs->hasPages())
    <div class="mt-4">
        {{ $cvs->links() }}
    </div>
    @endif
</div>
@endsection

