<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceivingItem extends Model
{

    protected $fillable = [
        'product_id',
        'price',
        'quantity',
    ];

    public function getSubtotalAttribute()
    {
        return $this->attributes['price'] * $this->attributes['quantity'];
    }

    public function trackings()
    {
        return $this->morphOne('App\InventoryTracking', 'trackable');
    }
}
