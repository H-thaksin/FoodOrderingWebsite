<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    // App\Models\Order.php
protected $fillable = [
    'user_id',
    'order_number',
    'customer_name',
    'phone',
    'address',
    'total_price',
    'delivery_fee',
    'payment_method',
    'coupon_code',
    'status'
];


    protected $casts = [
        'total_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
