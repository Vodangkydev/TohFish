<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetailsModel extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'job_details';

    // Khóa chính của bảng
    protected $primaryKey = 'job_details_id';

    // Các cột có thể được gán giá trị hàng loạt
    protected $fillable = [
        'job_id',
        'vi_tri',
        'total',
        'workplace',
        'work_address',
        'job_description',
        'workday',
        'business_hours',
        'interest',
        'request',
        'age',
        'level',
        'profile_included',
    ];

    // Nếu khóa chính không phải là auto-incrementing (tự tăng), bạn có thể cấu hình nó như sau:
    public $incrementing = true;
    public $timestamps = true; // Thay đổi tùy theo nhu cầu của bạn

    // Nếu khóa chính không phải là số nguyên, bạn cần thiết lập giá trị này:
    protected $keyType = 'int';

    // Thiết lập mối quan hệ với bảng `jobs`
    public function job()
    {
        return $this->belongsTo(JobModel::class, 'job_id', 'job_id');
    }
}