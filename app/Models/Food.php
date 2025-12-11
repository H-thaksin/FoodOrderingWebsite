<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
     protected $table = 'foods'; // <-- add this line
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
