<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use App\Gastos2;
use App\Category;
use Carbon\Carbon;

class CorteController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $fecha1 = date('Y-m-d');        // "2022-06-30"
        //$fecha2 = date('Y-m-d', strtotime('+1 day', $fecha1));
        $fecha2 = date('Y-m-d', strtotime($fecha1 . ' +1 day'));

        // modiff viernes:
        $fecha = $request->input('porfecha', "");

        $fecha = test_input($fecha);

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fecha)) {
            //return true;
            $fecha1 = $fecha;        // "2022-06-30"
            $fecha2 = date('Y-m-d', strtotime($fecha1 . ' +1 day'));
        }

        $form = $request->all();
        $data['input'] = $form;
        $date_range = $request->input('date_range');
        $start = $request->input('start');
        $end = $request->input('end');

        $query = DB::table("sales");
        if ($date_range == "today") {
            $query->whereDay('sales.created_at', '=', date('d'));
        }
        if ($date_range == "current_week") {
            $query->where('sales.created_at', '>=', date('Y-m-d h:i:s', strtotime("-7 days")));
        }
        if ($date_range == "current_month") {
            $query->whereMonth('sales.created_at', '=', date('m'));
        }

        if ($date_range == "custom_date") {
            $query->where('sales.created_at', '>=', date('Y-m-d', strtotime($start)));
            $query->where('sales.created_at', '<=', date('Y-m-d', strtotime($end)));
        }

        $query->where('sales.created_at', '>=', $fecha1);
        $query->where('sales.created_at', '<=', $fecha2);

        $title = date('Y-m-d', strtotime($start)) . " - " . date('Y-m-d', strtotime($end));

        $data['sales'] = $query->select("*", "sales.id as id")->leftJoin("sale_items as s", "s.sale_id", '=', "sales.id")->orderBy('sales.created_at', 'ASC')->groupBy("s.sale_id")->get();

        $pdf = "";
        if (!empty($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
        }

        if ($pdf == "yes") {
            $data['title'] = "Reporte Ventas ($title)";
            $pdf = PDF::loadView('backend.reports.sales.sales_pdf', $data);
            return $pdf->download('staff_sold.pdf');
        }

        $filas = array();

        foreach ($data['sales'] as $key => $venta) {

            if (empty($filas[$venta->cashier_id])) {
                $filas[$venta->cashier_id] = array();
            }

            $filas[$venta->cashier_id] [] = $venta;
        }

        $data['ventas2'] = $filas;


        $data["fecha1"] = $fecha1;

        // lista productos normales
        $listaItems = DB::select('SELECT p.category_id as id_cat,    p.name as nom_prod, items.product_id, items.price as precio, ' .
            'SUM(items.price * items.quantity) as suma_1, SUM(items.quantity) as cant_vend, items.size, items.is_customize, ' .
            'items.c_meta1, items.c_meta2, items.created_at, items.updated_at ' .
            'FROM sale_items items INNER JOIN products p on p.id = items.product_id ' .
            'INNER JOIN sales vent on items.sale_id = vent.id ' .
            'WHERE (items.is_customize ="original" or items.is_customize IS NULL) ' .
            ' AND vent.status = 1 ' .
            ' AND items.created_at >= "' . $fecha1 . '" and items.created_at < "' . $fecha2 . '" ' .
            ' GROUP by items.product_id, items.size ORDER BY `items`.`product_id`, id_cat  ASC ');


        $listaPorItems = [];

        if (!empty($listaItems)) {
            foreach ($listaItems as $key => $item) {

                $id_Categoria = $item->id_cat;

                if (empty($listaPorItems[$id_Categoria])) {
                    $listaPorItems[$id_Categoria] = array();
                }

                $_index = $item->product_id;
                $_name = $item->nom_prod;

                if (empty($listaPorItems [$id_Categoria] [$_index])) {
                    $listaPorItems [$id_Categoria] [$_index] = array();
                    $listaPorItems [$id_Categoria] [$_index] ['nombre'] = $_name;
                }

                $listaPorItems [$id_Categoria] [$_index] ['data'][] = $item;
            }
        }

        $data["listaPorItems"] = $listaPorItems;

        $listaPorItems4 = [];

        if (!empty($listaItems)) {
            foreach ($listaItems as $key => $item) {

                $_index = $item->product_id;
                $_name = $item->nom_prod;

                if (empty($listaPorItems4[$_index])) {
                    $listaPorItems4[$_index] = array();
                    $listaPorItems4[$_index] ['nombre'] = $_name;
                }

                $listaPorItems4[$_index] ['data'][] = $item;
            }
        }

        $data["listaPorItems4"] = $listaPorItems4;

        /************************************* */

        $listaCategorias = Category::where('id', '<>', 599999)->get();

        $data["listaCategorias"] = $listaCategorias;

        /************************************* */
        $listaOtrosItems = DB::select('SELECT items.product_id, items.price as precio, items.quantity as cant_vend, ' .
            'items.size, items.is_customize, items.c_meta1, items.c_meta2, items.created_at, items.updated_at ' .
            'FROM sale_items items ' .
            'INNER JOIN sales vent on items.sale_id = vent.id ' .
            'WHERE items.is_customize ="open" ' .
            'AND vent.status = 1 ' .
            'AND items.created_at >= "' . $fecha1 . '" and items.created_at < "' . $fecha2 . '"  ' .
            'ORDER BY `items`.`product_id` ASC');


        $data["listaOtrosItems"] = $listaOtrosItems;
        /************************************* */

        $listaComandas = Sale::whereBetween('created_at', [$fecha1, $fecha2])
            ->where('sales.status', '=', 1)
            ->orderBy('created_at', 'asc')->get();

        $listaVentas2 = array();
        $con_filas = 0;

        $horaLimite = Carbon::createFromFormat('Y-m-d H:i:s', $fecha1 . ' 15:30:00');

        foreach ($listaComandas as $key => $venta) {

            if ($venta->created_at->lt($horaLimite)) {
                $columna = 'manana';
            } else {
                $columna = 'tarde';
            }

            if (empty($listaVentas2[$columna])) {
                $listaVentas2[$columna] = array();
            }

            $listaVentas2 [$columna][] = $venta;

            $con_filas++;
        }

        $listaVentasPorProd = DB::select('SELECT vent.cashier_id id_vendedor, p.name as nom_prod, items.product_id, items.price as precio, ' .
            'SUM(items.price * items.quantity) as suma_1, SUM(items.quantity) as cant_vend, items.size, items.is_customize, ' .
            'items.c_meta1, items.c_meta2, items.created_at, items.updated_at ' .
            'FROM sale_items items INNER JOIN products p on p.id = items.product_id ' .
            'INNER JOIN sales vent on items.sale_id = vent.id ' .
            'WHERE (items.is_customize ="original" or items.is_customize IS NULL) ' .
            'AND items.created_at >= "' . $fecha1 . '" and items.created_at < "' . $fecha2 . '" ' .
            ' GROUP by items.product_id, items.size , vent.cashier_id ORDER BY  items.created_at ASC');

        $listaProduct_por_turno = [];

        foreach ($listaVentasPorProd as $key => $item) {

            $tempFecha = new Carbon($item->created_at);

            if ($tempFecha->lt($horaLimite)) {
                $columna = 'manana';
            } else {
                $columna = 'tarde';
            }

            if (empty($listaProduct_por_turno[$columna])) {
                $listaProduct_por_turno[$columna] = array();
            }

            $listaProduct_por_turno [$columna][] = $item;

            $con_filas++;
        }

        $data["productosPorTurno"] = $listaProduct_por_turno;

        //  otros productos
        $listaVentasOtrosProd = DB::select('SELECT vent.cashier_id id_vendedor, items.product_id, items.price as precio, ' .
            'SUM(items.price * items.quantity) as suma_1, SUM(items.quantity) as cant_vend, items.size, items.is_customize, ' .
            'items.c_meta1, items.c_meta2, items.created_at, items.updated_at ' .
            'FROM sale_items items INNER JOIN sales vent on items.sale_id = vent.id WHERE items.is_customize ="open" ' .
            'AND items.created_at >= "' . $fecha1 . '" and items.created_at < "' . $fecha2 . '"     ' .
            'GROUP by items.product_id, items.size , vent.cashier_id ORDER BY items.created_at ASC');

        $listaProduct_por_turno = [];

        foreach ($listaVentasOtrosProd as $key => $item) {
            $tempFecha = new Carbon($item->created_at);

            if ($tempFecha->lt($horaLimite)) {
                $columna = 'manana';
            } else {
                $columna = 'tarde';
            }

            if (empty($listaProduct_por_turno[$columna])) {
                $listaProduct_por_turno[$columna] = array();
            }

            $listaProduct_por_turno [$columna][] = $item;
        }

        $data["listaItems"] = $listaItems;
        $data["listaComandas"] = $listaComandas;
        $data["listaVentas2"] = $listaVentas2;

        $listaTurnos = array();

        $tMatutino = new \stdClass;
        $tMatutino->nombre = 'Matutino';
        $tMatutino->clave = 'manana';

        $tMatutino->limite = $horaLimite;

        $__index = 0;
        $listaTurnos[$__index++] = $tMatutino;

        $tVespertino = new \stdClass;
        $tVespertino->nombre = 'Vespertino';
        $tVespertino->clave = 'tarde';
        $horaLimite = Carbon::createFromFormat('Y-m-d H:i:s', $fecha1 . ' 22:30:00');
        $tVespertino->limite = $horaLimite;

        $listaTurnos[$__index++] = $tVespertino;

        $data["listaTurnos"] = $listaTurnos;


        $turno_manana = new \stdClass;
        $turno_manana->venta_efe = 0;
        $turno_manana->card = 0;
        $turno_manana->PUNTOS = 0;

        $suma = $turno_manana->venta_efe;

        if (isset($turno_manana->card)) {
            $suma = $suma + $turno_manana->card;
        }

        $_debug = $request->input('debug', '');
        if ('true' === strtolower($_debug)) {
            dd($data);
        }

        $printer = $request->input('printer', '');

        if ('80mm' === $printer) {
            return view('backend.reports.corte.corte_80mm', $data);
        }

        return view('backend.reports.corte.page_report_tres', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($type, $id)
    {
        $data = [];
        $data['sale'] = Sale::find($id);
        return view('backend.reports.' . $type . '.show', $data);
    }

    public function expenses(Request $request)
    {
        $fecha = $request->input('porfecha', "");

        $fecha = test_input($fecha);

        $mes = $request->input('mes', "");

        $mes = test_input($mes);


        if ('julio' === $mes) {
            $start = "2022-07-01 00:00:00";
            $end = "2022-08-01 00:00:00";
            $data['expenses'] = Gastos2::where("created_at", ">=", $start)->
            where("created_at", "<", $end)->
            orderBy("created_at", "ASC")->paginate(300);
        } else if ('agosto' === $mes) {
            $start = "2022-08-01 00:00:00";
            $end = "2022-09-01 00:00:00";
            $data['expenses'] = Gastos2::where("created_at", ">=", $start)->
            where("created_at", "<", $end)->
            orderBy("created_at", "ASC")->paginate(300);
        }  else {
            $data['expenses'] = Gastos2::paginate(20);
        }

        return view('backend.reports.expenses', $data);
    }
}
