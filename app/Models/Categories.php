<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParentModel;

class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['stt_category', 'category_name', 'parent_id'];
    public $timestamps = false;
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'parent_id');
    }
}
