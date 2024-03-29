<?php

namespace App\Views;

use Illuminate\Database\Eloquent\Model;
use App\Sale;
use Illuminate\Support\Facades\DB;

class ViewSales extends Model
{
    protected $table = 'sales';

    public function scopeGetListaMensualVentas($query, $year, $mes)
    {
        $results = DB::table($this->table)
            ->selectRaw("
                count(id) as conteo, 
                date(created_at) as dia, 
                sum(amount) as suma, 
                
                
                 COALESCE(SUM(CASE
                        WHEN payment_with = 'cash' THEN amount
                    END), 0) sum_ventas_efe,
                COALESCE(    SUM(CASE
                        WHEN payment_with = 'card' THEN amount
                    END), 0) sum_ventas_tarj, 
                    
                    
                COALESCE(COUNT(CASE
                        WHEN payment_with = 'cash' THEN id
                    END), 0) con_ventas_efe,
                COALESCE(    COUNT(CASE
                        WHEN payment_with = 'card' THEN id
                    END), 0) con_ventas_tarj
  
            ")
            ->whereRaw('YEAR(created_at) = ? ', [$year])
            ->whereRaw('month(created_at) = ?', [$mes])
            ->where('status', '=', '1')

            // ->groupByRaw(' date(created_at) ')
            ->groupBy((array)DB::raw(" dia "))
            ->orderByRaw(' created_at ASC')
            ->get();


        return $results;

    }

    /**
     *  Consulta diario por fecha , ejem: '2022-11-05'
     */
    public function scopeGetListaVentaDiaro($query, $fecha)
    {

        $nextFecha = date('Y-m-d', strtotime($fecha . ' +1 day'));

        $lista = DB::table($this->table)
            ->where('sales.created_at', '>=', $fecha)
            ->where('sales.created_at', '<', $nextFecha)
            ->where('sales.status', '=', 1)
            ->orderBy('sales.created_at', 'ASC')
            ->get();

        return $lista;
    }


    // ventas diarias
    public function scopeGetListaComandas($query, $fecha)
    {

        $nextFecha = date('Y-m-d', strtotime($fecha . ' +1 day'));

        $results = Sale::where('sales.status', '=', Sale::ACTIVE)
            ->where('sales.created_at', '>=', $fecha)
            ->where('sales.created_at', '<', $nextFecha)
            /*whereBetween('created_at', [$fecha1, $fecha2]  )*/
            ->orderBy('created_at', 'asc')->get();
        return $results;
    }

}
