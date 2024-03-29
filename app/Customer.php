<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    public static $rules = [
        'name'    => 'required',
        'email'   => 'required|unique:customers',
        'phone'   => 'required',
        'address' => 'required',
    ];

    /**
     * setup variable mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'neighborhood',
        'comments',
        'phone',
        'address',
    ];

    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword != '') {
            $query->where(
                function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%'.$keyword.'%');
                }
            );
        }

        return $query;
    }
}
