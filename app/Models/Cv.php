<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    // Tên bảng tương ứng với model
    protected $table = 'cvs';

    // Định nghĩa khóa chính của bảng (nếu không phải là `id`)
    protected $primaryKey = 'cvs_id';

    // Chỉ định các thuộc tính có thể được gán giá trị (fillable)
    protected $fillable = [
        'job_id',
        'ho_ten',
        'age',
        'current_residence',
        'email',
        'level',
        'applied_position',
        'willing_to_travel',
        'sex',
        'place_of_birth',
        'phone',
        'url_facebook',
        'file_path',
        'willing_to_work_overtime',
        'previous_experiences',
        'personal_experience',
    ];

    /**
     * Relationship với Job
     */
    public function job()
    {
        return $this->belongsTo(JobModel::class, 'job_id', 'job_id');
    }

    public $timestamps = true;
    // Nếu sử dụng timestamp, đặt tên cột tương ứn
}