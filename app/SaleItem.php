<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{

    protected $fillable = [
        'product_id',
        'price',
        'size',
        'quantity',
        'p_qty',
        'is_customize',
        'c_meta1',
        'c_meta2',
        'created_at', /* algunas Fechas Anteriores */
    ];

    public function getSubtotalAttribute()
    {
        return $this->attributes['price'] * $this->attributes['quantity'];
    }

    public function trackings()
    {
        return $this->morphOne('App\InventoryTracking', 'trackable');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function getOtroAttribute()
    {
        return random_int(100, 999);
    }

    public function getIsOpenAttribute()
    {
        return ($this->is_customize != null && strnatcasecmp($this->is_customize, 'open') == 0) ? true : false;
    }

    public function getNombreLargoAttribute()
    {
        $texto = "";

        //if (!empty($item->product) && !empty($item->product->name) )
        if (!empty($this->product) && !empty($this->product->name))
            $texto .= $this->product->name;
        // no se aplica else, porque al inicio esta en blanco.
        //else
        //echo "";

        if (!empty($texto))
            $texto .= " (" . $this->size . ")";
        else
            $texto .= "-" . $this->size . "-";

        return $texto;
    }

    public function getNombreOpenProductoAttribute()
    {
        $texto = "";

        if (!empty($this->c_meta1))
            $texto .= $this->c_meta1;

        if (!empty($this->c_meta2))
            $texto .= " (" . $this->c_meta2 . ")";
        else
            $texto .= "...";

        return $texto;
    }

}
