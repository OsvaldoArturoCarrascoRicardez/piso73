<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gastos1;
use App\Gastos2;
use App\Views\ViewSales;

class TotalMonthlyController extends Controller
{

    public function resumenMensual(Request $request)
    {

        $fecha1 = date('Y-m-d');

        // prepared for november
        $lsGastos1 = Gastos1::getListaMensual(2023, "01");

        $lsGastos_dos = Gastos2::getListaGastosMensual(2023, "01");


        $lsVentas = ViewSales::getListaMensualVentas(2023, 1);

        $data = array();

        $data['gastos_uno'] = $lsGastos1;
        $data['gastos_dos'] = $lsGastos_dos;
        $data['totVentas'] = $lsVentas;


        $data["fecha1"] = $fecha1;

        return view('backend.reports.mensual.index', $data);
    }
}
