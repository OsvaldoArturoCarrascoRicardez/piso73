<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Product;
use App\Expense;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $type)
    {
        $form = $request->all();
        $data['input'] = $form;
        $date_range = $request->input('date_range');


        $date_range = $request->input('date_range', 'ABC');


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


        // forze
        if ($date_range == "ABC") {
            $title = "Today";
            $title = "hoy";
            //$query->whereDay('sales.created_at', '=', date('d'));
            $query->whereDay('sales.created_at', '=', date('Y-m-d'));
        }


        // comandas activas_
        $query->where('status', '=', 1);


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

        return view('backend.reports.' . $type . '.index', $data);
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

    // old methd	public function SalesByProduct(Request $request) {
    public function SalesByProduct(Request $request, $fecha = "")
    {
        $nuevo_array = [];

        $fecha1 = date('Y-m-d');        // "2022-06-30"
        $fecha2 = date('Y-m-d', strtotime($fecha1 . ' +1 day'));

        // modiff viernes:
        $fecha = $request->input('inputdate', "");

        $fecha = test_input($fecha);

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fecha)) {
            //return true;
            $fecha1 = $fecha;        // "2022-06-30"
            $fecha2 = date('Y-m-d', strtotime($fecha1 . ' +1 day'));
        }

        $data["sales_by_product"] = $nuevo_array;

        $sales_by_product = DB::select("SELECT quantity,product_id , created_at, is_customize, DATE_FORMAT(created_at, \"%H\") as `hora_consumo` FROM sale_items WHERE (create d_at BETWEEN '" . $fecha1 . " 00:00:00' AND '" . $fecha2 . " 00:00:00')");

        if (!empty($sales_by_product)) {

            $nuevo_array = agrupa_por_prod($sales_by_product);

            foreach ($nuevo_array as $key => $sale) {
                if ($sale['product_id'] == 999999999) {
                    $nuevo_array[$key]['product_name'] = "[Open Product.]";
                } else {
                    $s = Product::find($sale['product_id']);
                    if (!empty($s)) {
                        $nuevo_array[$key]['product_name'] = $s->name;
                    }
                }
            }

            $data["sales_by_product"] = $nuevo_array;
        }
        $data["fecha1"] = $fecha1;

        $pdf = "";
        if (!empty($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
        }
        if ($pdf == "yes") {
            $data['title'] = "Ventas por Producto";
            $pdf = PDF::loadView('backend.reports.sales_by_product_pdf', $data);
            return $pdf->download('staff_sold.pdf');
        }

        return view('backend.reports.sales_by_products', $data);
    }

    public function Graphs()
    {
        $data['transections_7_days'] = $this->getRevenueRransections(7);
        $data['transections_30_days'] = $this->getRevenueRransections(30);
        $data['get_orders_365'] = $this->getRevenueTransectionsYearly(365);

        $data['transections_7_days_online'] = $this->getRevenueRransections(7, 'order');
        $data['transections_30_days_online'] = $this->getRevenueRransections(30, 'order');
        $data['get_orders_365_online'] = $this->getRevenueTransectionsYearly(365, 'order');

        return view('backend.reports.graphs', $data);
    }

    public function expenses()
    {

        if (!empty($_GET['start']) and !empty($_GET['end'])) {
            $start = $_GET['start'] . " 00:00:00";
            $end = $_GET['end'] . " 23:59:00";
            $data['expenses'] = Expense::where("created_at", ">=", $start)->where("created_at", "<=", $end)->paginate(20);
        } else {
            $data['expenses'] = Expense::paginate(20);
        }

        return view('backend.reports.expenses', $data);
    }

    public function getRevenueRransections($date_difference = "", $type = "pos")
    {
        $where = "";
        $today = '';
        if ($today != "") {
            $where = "DATE(created_at) = '" . date("Y-m-d") . "'";
        } else {
            $where = "created_at BETWEEN NOW() - INTERVAL " . $date_difference . " DAY AND NOW()";
        }
        $query = DB::select("SELECT SUM(amount) as amount, DATE_FORMAT(created_at,'%W') as day, DATE_FORMAT(created_at,'%d') as dat, DATE_FORMAT(created_at,'%M') as mon, created_at as dated FROM `sales` WHERE type='$type' AND  " . $where . " GROUP BY DATE(created_at) ORDER BY created_at DESC");
        return $query;
    }

    public function getRevenueTransectionsYearly($date_difference = "", $type = "pos")
    {
        $where = "";
        if ($date_difference != "") {
            $where = "created_at BETWEEN NOW() - INTERVAL " . $date_difference . " DAY AND NOW()";
        }

        $query = DB::select("SELECT SUM(amount) as amount, DATE_FORMAT(created_at,'%W') as day, DATE_FORMAT(created_at,'%d') as dat, DATE_FORMAT(created_at,'%M') as mon, created_at as dated FROM `sales` WHERE  type='$type' AND " . $where . " GROUP BY MONTH(created_at) ORDER BY created_at DESC");
        return $query;


    }

}
