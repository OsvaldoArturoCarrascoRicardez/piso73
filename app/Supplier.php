<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    public static $rules = [
        'name'         => 'required',
        'company_name' => 'required',
        'email'        => 'required|unique:suppliers',
        'phone'        => 'required',
        'address'      => 'required',
    ];

    protected $fillable = [
        'name',
        'company_name',
        'email',
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
