<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'email',
        'city',
        'district',
        'note',
        'is_for_relative',
        'relative_name',
        'relative_phone',
        'relative_address',
        'total_amount',
        'status',
        'payment_method',
    ];

    protected $casts = [
        'is_for_relative' => 'boolean',
        'total_amount' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate order code
     */
    public static function generateCode()
    {
        return 'ORD' . date('Ymd') . str_pad(self::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }
}


