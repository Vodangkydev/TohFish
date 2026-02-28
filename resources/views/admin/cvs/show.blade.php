@extends('admin.layout')

@section('title', 'Chi tiết CV #' . $cv->cvs_id)

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-file-alt"></i> Chi tiết CV #{{ $cv->cvs_id }}</h2>
    <a href="{{ route('admin.cvs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="admin-card">
    <div class="row">
        <div class="col-md-6">
            <h4 class="mb-4">Thông tin cá nhân</h4>
            <table class="table table-bordered">
                <tr>
                    <th width="40%">Họ và tên:</th>
                    <td>{{ $cv->ho_ten }}</td>
                </tr>
                <tr>
                    <th>Tuổi:</th>
                    <td>{{ $cv->age }}</td>
                </tr>
                <tr>
                    <th>Giới tính:</th>
                    <td>
                        @if($cv->sex == 'male')
                            <span class="badge bg-primary">Nam</span>
                        @elseif($cv->sex == 'female')
                            <span class="badge bg-danger">Nữ</span>
                        @else
                            <span class="badge bg-secondary">Khác</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Nơi sinh:</th>
                    <td>{{ $cv->place_of_birth }}</td>
                </tr>
                <tr>
                    <th>Nơi ở hiện tại:</th>
                    <td>{{ $cv->current_residence }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><a href="mailto:{{ $cv->email }}">{{ $cv->email }}</a></td>
                </tr>
                <tr>
                    <th>Số điện thoại:</th>
                    <td><a href="tel:{{ $cv->phone }}">{{ $cv->phone }}</a></td>
                </tr>
                <tr>
                    <th>Facebook:</th>
                    <td>
                        @if($cv->url_facebook)
                            <a href="{{ $cv->url_facebook }}" target="_blank">{{ $cv->url_facebook }}</a>
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Trình độ học vấn:</th>
                    <td>{{ $cv->level }}</td>
                </tr>
                <tr>
                    <th>Công việc ứng tuyển:</th>
                    <td>
                        @if($cv->applied_position)
                            <span class="badge bg-info">{{ $cv->applied_position }}</span>
                        @elseif($cv->job)
                            <span class="badge bg-info">Job #{{ $cv->job->job_id }}</span>
                        @else
                            <span class="text-muted">Không xác định</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <h4 class="mb-4">Thông tin bổ sung</h4>
            <table class="table table-bordered">
                <tr>
                    <th width="40%">Sẵn sàng đi công tác:</th>
                    <td>
                        @if($cv->willing_to_travel)
                            <span class="badge bg-success">Có</span>
                        @else
                            <span class="badge bg-secondary">Không</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Sẵn sàng làm thêm giờ:</th>
                    <td>
                        @if($cv->willing_to_work_overtime)
                            <span class="badge bg-success">Có</span>
                        @else
                            <span class="badge bg-secondary">Không</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Ngày gửi CV:</th>
                    <td>{{ $cv->created_at ? $cv->created_at->format('d/m/Y H:i:s') : '' }}</td>
                </tr>
                <tr>
                    <th>File CV:</th>
                    <td>
                        @if($cv->file_path)
                            <a href="{{ route('admin.cvs.view', $cv->cvs_id) }}" target="_blank" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> Xem CV
                            </a>
                            <a href="{{ route('admin.cvs.download', $cv->cvs_id) }}" class="btn btn-primary btn-sm ms-2" download>
                                <i class="fas fa-download"></i> Tải xuống CV
                            </a>
                        @else
                            <span class="text-muted">Không có file</span>
                        @endif
                    </td>
                </tr>
            </table>

            @if($cv->previous_experiences)
            <div class="mt-4">
                <h5>Kinh nghiệm làm việc:</h5>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($cv->previous_experiences)) !!}
                </div>
            </div>
            @endif

            @if($cv->personal_experience)
            <div class="mt-4">
                <h5>Kỹ năng cá nhân:</h5>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($cv->personal_experience)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <form action="{{ route('admin.cvs.destroy', $cv->cvs_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa CV này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Xóa CV
            </button>
        </form>
    </div>
</div>
@endsection

