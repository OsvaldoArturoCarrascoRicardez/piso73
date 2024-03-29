<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class AdjustmentItem extends Model
{
    /**
     * setup variable mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'adjustment',
        'diff',
    ];

    public function trackings(): MorphOne
    {
        return $this->morphOne('App\InventoryTracking', 'trackable');
    }
}
