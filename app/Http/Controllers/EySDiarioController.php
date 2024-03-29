<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Views\ViewSales;
use App\Views\ViewEgresos;

class EySDiarioController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $valid = $this->getParams($request);


        //---
        $listaVentas = ViewSales::getListaVentaDiaro($valid->fecha);


        $filas = array();

        foreach ($listaVentas as $key => $venta) {

            if (empty($filas[$venta->cashier_id])) {
                $filas[$venta->cashier_id] = array();
            }

            $filas[$venta->cashier_id] [] = $venta;
        }


        $listaEgresos = ViewEgresos::getListaEgresosDiaro($valid->fecha);


        if (false) {
            dd($filas, $valid, $listaEgresos);

        }

        $data = array();

        $data["listaGastos"] = $listaEgresos;
        $data["cFecha"] = $valid->fecha;
        $data['ventas2'] = $filas;


        return view('backend.reports.diario.index', $data);
    }

    protected function getParams(Request $request)
    {

        $objeto = new \stdClass();

        $fecha = $request->input('porfecha', "");

        $fecha = test_input($fecha);

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fecha)) {

            $objeto->fecha = $fecha;
        } else {
            $objeto->fecha = date('Y-m-d');       // "2022-06-30"
        }

        $objeto->anterior = date('Y-m-d', strtotime($objeto->fecha . ' -1 day'));
        $objeto->siguiente = date('Y-m-d', strtotime($objeto->fecha . ' +1 day'));

        return $objeto;
    }

}
