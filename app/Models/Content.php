<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    // Tên bảng tương ứng với model (nếu tên bảng không theo quy tắc của Laravel)
    protected $table = 'contents';

    // Khóa chính của bảng
    protected $primaryKey = 'content_id';

    // Các cột có thể điền dữ liệu
    protected $fillable = [
        'content',
        'description',
        'image_url',
    ];
}