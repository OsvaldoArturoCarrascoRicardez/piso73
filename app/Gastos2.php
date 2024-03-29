<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gastos2 extends Model
{
    const ACTIVE = 0;

    const UM_VENTA_GNRL = '0000';

    protected $table = 'expenses_dos';

    protected $fillable = ['cashier_id', 'expense_amount', 'quantity', 'unit_value', 'description', 'motive',
        'expencePic', 'created_at'];

    public function scopeGetListaGastosMensual($query, $year, $mes)
    {

        $results = DB::table($this->table)
            ->selectRaw(' count(*) as conteo, 
date(created_at) as dia, 
sum(expense_amount) as suma')
            ->whereRaw('YEAR(created_at) = ? ', [$year])
            ->whereRaw('month(created_at) = ?', [$mes])
            // ->where('deleted', '=', ACTIVE)
            ->where('deleted', '=', self::ACTIVE)


            // ->groupByRaw(' date(created_at) ')
            ->groupBy(DB::raw(" dia "))
            ->orderByRaw(' created_at ASC')
            ->get();


        return $results;

    }

}
