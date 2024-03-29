<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
     use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];


    public static $rules = [
        'name'  => 'required|unique:products'
    ];

    protected $fillable = [
        'name',
        'prices',
        'category_id',
        'description',
        'titles',
    ];

    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword != '') {
            $query->where(
                function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%'.$keyword.'%')
                        ->orWhere('barcode', 'LIKE', '%'.$keyword.'%');
                }
            );
        }

        return $query;
    }
}
