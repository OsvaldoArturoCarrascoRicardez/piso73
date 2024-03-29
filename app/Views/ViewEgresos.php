<?php

namespace App\Views;

use Illuminate\Database\Eloquent\Model;
use App\Gastos2;
use Illuminate\Support\Facades\DB;

class ViewEgresos extends Model
{
    protected $table = 'expenses_dos';

    public function scopeGetListaEgresosDiaro($query, $fecha)
    {

        $nextFecha = date('Y-m-d', strtotime($fecha . ' +1 day'));

        $sttm = 'SELECT v.id as id, v.cashier_id, v.description, v.motive, v.expense_amount, v.image_id, v.expencePic, v.created_at, v.updated_at, u.clave, u.nombre , v.quantity ' .
            'FROM expenses_dos v INNER JOIN unidadmedidavarios u ON u.clave = v.unit_value ' .
            'where v.deleted = ? and  v.created_at >= ? and v.created_at < ? ' .
            ' order by v.created_at asc';

        $lista = DB::select($sttm, array(Gastos2::ACTIVE, $fecha, $nextFecha));

        return $lista;
    }

    public function scopeDefaultQuery($query)
    {
        return DB::table('expenses_dos as gastos')
            ->join('unidadmedidavarios as um', 'um.clave', '=', 'gastos.unit_value')
            ->select('gastos.id', 'gastos.cashier_id', 'gastos.description', 'gastos.motive', 'gastos.expense_amount', 'gastos.quantity',
                'gastos.image_id', 'gastos.expencePic', 'gastos.created_at', 'gastos.updated_at', 'um.clave', 'um.nombre')
            ->where('deleted', Gastos2::ACTIVE);

    }

}
