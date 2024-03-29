<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

use App\Gastos2;

class Reporte2Controller extends Controller
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

            $fecha1 = $fecha;        // "2022-06-30"
            $fecha2 = date('Y-m-d', strtotime($fecha1 . ' +1 day'));

        }
        $form = $request->all();
        $data['input'] = $form;
        $date_range = $request->input('date_range');
        $start = $request->input('start');
        $end = $request->input('end');

        $query = DB::table("sales");
        $title = "Todo";
        if ($date_range == "today") {
            $title = "Today";
            $title = "hoy";
            $query->whereDay('sales.created_at', '=', date('d'));
        }
        if ($date_range == "current_week") {
            $title = date("Y-m-d") . " - " . date('Y-m-d h:i:s', strtotime("-7 days"));
            $query->where('sales.created_at', '>=', date('Y-m-d h:i:s', strtotime("-7 days")));
        }
        if ($date_range == "current_month") {
            $title = date('F');
            $query->whereMonth('sales.created_at', '=', date('m'));
        }

        if ($date_range == "custom_date") {
            $query->where('sales.created_at', '>=', date('Y-m-d', strtotime($start)));
            $query->where('sales.created_at', '<=', date('Y-m-d', strtotime($end)));
            $title = date('Y-m-d', strtotime($start)) . " - " . date('Y-m-d', strtotime($end));
        }


        // force___


        //$query->where('sales.created_at', '>=', '2022-08-01 00:00:00');
        //$query->where('sales.created_at', '<=', '2022-08-02 00:00:00');
        $query->where('sales.created_at', '>=', $fecha1);
        $query->where('sales.created_at', '<=', $fecha2);


        // comandas activas_
        $query->where('sales.status', '=', 1);


        $title = date('Y-m-d', strtotime($start)) . " - " . date('Y-m-d', strtotime($end));


        /*$data['sales'] = $query->select("*" , "sales.id as id")->leftJoin("sale_items as s" , "s.sale_id" , '=', "sales.id" )->orderBy('sales.created_at', 'DESC')->groupBy("s.sale_id")->get(); */
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

        // return view('backend.reports.'.$type.'.index', $data);

        $filas = null;
        $filas = array();

        //  @forelse ($sales as $key => $sale)


        $con_filas = 0;
        foreach ($data['sales'] as $key => $venta) {

            if (empty($filas[$venta->cashier_id])) {
                $filas[$venta->cashier_id] = array();
            }

            $filas[$venta->cashier_id] [] = $venta;

            $con_filas++;
        }

        $debug__ = array();
        $debug__['otros'] = $con_filas;
        $debug__['filas'] = $filas;


        $data['ventas2'] = $filas;


        $data["fecha1"] = $fecha1;


        $gastosQuery = [];


        if (TRUE) {
            //  ___$results = DB::select( 'SELECT v.id as id, v.cashier_id, v.description, v.motive, v.expense_amount, v.image_id, v.expencePic, v.created_at, v.updated_at, u.clave, u.nombre FROM expenses_dos v INNER JOIN unidadmedidavarios u ON u.clave = v.unit_value order by v.created_at asc' );
            $results = DB::select('SELECT v.id as id, v.cashier_id, v.description, v.motive, v.expense_amount, v.image_id, v.expencePic, v.created_at, v.updated_at, u.clave, u.nombre ' .
                'FROM expenses_dos v INNER JOIN unidadmedidavarios u ON u.clave = v.unit_value ' .
                'where v.created_at >= "' . $fecha1 . '" and v.created_at < "' . $fecha2 . '" ' .
                ' and v.deleted = 0  ' .
                ' order by v.created_at asc');


            // $query->where('sales.created_at', '>=', $fecha1);
            // $query->where('sales.created_at', '<=', $fecha2);

            //.unit_value order by id asc' );

            //dd($results);

            $data["listaGastos"] = $results;

        }


        if (true) {
            // dd($debug__ ,    $data);

        }


        return view('backend.reports.diario.index', $data);
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
        } else if ('septiembre' === $mes) {
            $start = "2022-09-01 00:00:00";
            $end = "2022-10-01 00:00:00";
            $data['expenses'] = Gastos2::where("created_at", ">=", $start)->
            where("created_at", "<", $end)->
            orderBy("created_at", "ASC")->paginate(300);
        } else if ('octubre' === $mes) {
            $start = "2022-10-01 00:00:00";
            $end = "2022-11-01 00:00:00";
            $data['expenses'] = Gastos2::where("created_at", ">=", $start)->
            where("created_at", "<", $end)->
            orderBy("created_at", "ASC")->paginate(300);
        } /*
		else if(!empty($_GET['start']) and !empty($_GET['end'])) { 
			$start = $_GET['start'] . " 00:00:00";
			$end = $_GET['end'] . " 23:59:00";
			$data['expenses'] = Gastos2::where("created_at" , ">=" , $start)->where("created_at" , "<=" , $end)->paginate(20);
		} */ else {
            $data['expenses'] = Gastos2::paginate(20);
        }

        return view('backend.reports.expenses', $data);
    }
}
