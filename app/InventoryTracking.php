<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryTracking extends Model
{

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public static function search($params = [])
    {
        return self::when(
            !empty($params), function ($query) use ($params) {
            switch ($params['date_range']) {
                case 'today':
                    $query->whereDay('created_at', '=', date('d'));
                    break;
                case 'current_month':
                    $query->whereMonth('created_at', '=', date('m'));
                    break;
                default:

                    break;
            }

            return $query;
        }
        )->orderBy('created_at', 'DESC');
    }
}
