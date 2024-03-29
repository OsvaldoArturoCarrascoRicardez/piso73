<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class GastosGeneral extends Model
{

    use SoftDeletes;

    protected $table = 'expenses';

    protected $fillable = ['id', 'title', 'description', 'created_at', 'price'];


    protected $dates = [
        'deleted_at'
    ];

    public function scopeGetListaMensual($query, $year, $mes)
    {
        return DB::table($this->table)
            ->selectRaw('count(id) as conteo, date(created_at) as dia, sum(price) as suma')
            ->whereRaw('YEAR(created_at) = ? ', [$year])
            ->whereRaw('month(created_at) = ?', [$mes])
            ->groupBy((array)DB::raw(" dia "))
            ->orderByRaw(' created_at ASC')
            ->get();
    }

}
