<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link',
        'button_text',
        'link_2',
        'button_text_2',
        'type',
        'order',
        'status',
        'background_color',
        'icon',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope để lấy slider theo type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope để lấy slider active
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope để sắp xếp theo order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
