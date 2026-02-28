<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobModel extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu khác tên mặc định
    protected $table = 'jobs';

    // Các thuộc tính có thể được gán hàng loạt
    protected $fillable = [
        'description',
        'status',
        'url_job_details',
        'role_category_id',
        'position_category_id',
        'location_category_id',
    ];

    // Không sử dụng timestamps
    public $timestamps = true; // Thay đổi tùy theo nhu cầu của bạn

    // Mối quan hệ với bảng categories
    public function roleCategory()
    {
        return $this->belongsTo(Categories::class, 'role_category_id', 'category_id');
    }

    public function positionCategory()
    {
        return $this->belongsTo(Categories::class, 'position_category_id', 'category_id');
    }

    public function locationCategory()
    {
        return $this->belongsTo(Categories::class, 'location_category_id', 'category_id');
    }

    /**
     * Relationship với JobDetails
     */
    public function jobDetail()
    {
        return $this->hasOne(JobDetailsModel::class, 'job_id', 'job_id');
    }

    /**
     * Relationship với CVs
     */
    public function cvs()
    {
        return $this->hasMany(Cv::class, 'job_id', 'job_id');
    }
}