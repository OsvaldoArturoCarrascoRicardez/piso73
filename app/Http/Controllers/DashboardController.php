<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $now = date('Y-m-d 23:59:59');
        $yersterday = date('Y-m-d 00:00:00', strtotime('- 1 day'));
        $today_date = date('Y-m-d 00:00:00');
        $last_week = date('Y-m-d h:i:s', strtotime('- 1 week'));
        $last_month = date('Y-m-d h:i:s', strtotime('- 1 month'));
        $total_date = date('Y-m-d h:i:s', strtotime('- 100 month'));

        $data['today'] = $this->getSalesPrice($today_date, $now);
        $data['yesterday'] = $this->getSalesPrice($yersterday, $today_date);
        $data['last_week'] = $this->getSalesPrice($last_week, $now);
        $data['last_month'] = $this->getSalesPrice($last_month, $now);
        $data['total_earning'] = $this->getSalesPrice($total_date, $now);
        $data['total_sales'] = count(Sale::get());
        $data['total_sales_today'] = $this->getSalesTotal($today_date, $now);
        $data['total_sales_yesterday'] = $this->getSalesTotal($yersterday, $today_date);
        $data['total_sales_last_week'] = $this->getSalesTotal($last_week, $now);
        $data['total_sales_last_month'] = $this->getSalesTotal($last_month, $now);

        $data['transections_7_days'] = $this->getRevenueRransections(7);
        $data['transections_30_days'] = $this->getRevenueRransections(30);
        $data['get_orders_365'] = $this->getRevenueTransectionsYearly(365);

        $data['transections_7_days_online'] = $this->getRevenueRransections(7, 'order');
        $data['transections_30_days_online'] = $this->getRevenueRransections(30, 'order');
        $data['get_orders_365_online'] = $this->getRevenueTransectionsYearly(365, 'order');


        $sales_by_product = DB::select("SELECT  SUM(quantity) as total_sales,product_id FROM sale_items GROUP BY (product_id) ORDER BY total_sales DESC LIMIT 10");
        if (!empty($sales_by_product)) {
            foreach ($sales_by_product as $sale) {

                $product = DB::table("products")->where("id", $sale->product_id)->first();
                $sale->product_name = "";
                if (!empty($product))
                    $sale->product_name = $product->name;

            }
        }
        $data["sales_by_product"] = $sales_by_product;

        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $data['sales'] = Sale::orderBy("sales.id", "DESC")->limit(10)->get();
        } else {
            $data['sales'] = Sale::where("cashier_id", Auth::user()->id)
                ->orderBy("sales.id", "DESC")->limit(10)->get();
        }
        return view('backend.dashboard.home', $data);
    }

    public function getSalesPrice($start, $end)
    {

        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $query = DB::table("sales")->where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("status", 1)->sum("amount");
        } else {
            $query = DB::table("sales")->where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("status", 1)->where("cashier_id", Auth::user()->id)->sum("amount");
        }

        return $query;
    }

    public function getSalesTotal($start, $end)
    {
        $query = Sale::where("created_at", ">=", $start)->where("created_at", "<=", $end)->where("status", 1)->get();
        return count($query);
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
        //$query = DB::select("SELECT SUM(amount) as amount, DATE_FORMAT(created_at,'%W') as day, DATE_FORMAT(created_at,'%d') as dat, DATE_FORMAT(created_at,'%M') as mon, created_at as dated FROM `sales` WHERE type='$type' AND  ".$where." GROUP BY DATE(created_at) ORDER BY created_at DESC");
        $query = DB::select("SELECT SUM(amount) as amount, DATE_FORMAT(created_at,'%W') as day, DATE_FORMAT(created_at,'%d') as dat, DATE_FORMAT(created_at,'%M') as mon, DATE(created_at) as  dated   FROM `sales` WHERE type='$type' AND  status = 1    AND " . $where . " GROUP BY dated ORDER BY created_at DESC");
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
