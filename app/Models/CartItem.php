<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'product_image',
        'is_selected',
    ];

    protected $casts = [
        'product_price' => 'integer',
        'quantity' => 'integer',
        'is_selected' => 'boolean',
    ];

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Image (Product)
     */
    public function product()
    {
        return $this->belongsTo(Image::class, 'product_id', 'images_id');
    }

    /**
     * Chuyển đổi CartItem thành array format giống session cart
     */
    public function toCartArray(): array
    {
        return [
            'id' => $this->product_id,
            'name' => $this->product_name,
            'price' => $this->product_price,
            'quantity' => $this->quantity,
            'image' => $this->product_image,
            'is_selected' => $this->is_selected ?? true,
        ];
    }
}
