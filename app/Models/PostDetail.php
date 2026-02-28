<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostDetail extends Model
{
    use HasFactory;

    // Nếu tên bảng không phải là dạng số nhiều của tên model
    protected $table = 'posts_detail';

    // Cột khóa chính (Nếu không phải là 'id')
    protected $primaryKey = 'posts_detail_id';

    // Chỉ định các cột có thể được gán đại trà
    protected $fillable = ['content', 'post_id', 'post_url'];

    // Thiết lập mối quan hệ với model Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}
