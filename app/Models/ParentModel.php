<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;
    protected $table = 'parents';
    protected $fillable = ['stt', 'parent_name', 'parent_url'];
    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Categories::class, 'parent_id');
    }
}
