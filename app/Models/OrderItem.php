<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'food_id',
        'food_name',    // snapshot
        'food_image',   // snapshot
        'price',   
        'unit_price',   // price per unit when order placed
        'quantity',
        'subtotal',     // unit_price * quantity
        'options',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function food(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Food::class);
    }
}
