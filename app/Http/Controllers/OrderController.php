<?php

namespace App\Http\Controllers;

use App\Sale;
use Session;
use Validator;

class OrderController extends Controller
{

    public function __construct() 
    {
        $this->middleware('auth');
    }

    /**
     * Page Lisitng on admin.
     */
    public function index() 
    {
        $data['incomplete'] = Sale::where("type", "order")->where("status", 2)->orderBy("id", "DESC")->limit(10)->get();
        $data['completed'] = Sale::where("type", "order")->where("status", 1)->orderBy("id", "DESC")->limit(10)->get();
        $data['canceled'] = Sale::where("type", "order")->where("status", 0)->orderBy("id", "DESC")->limit(10)->get();
        $data['title'] = "Orders";
        return view('backend.orders.index', $data);
    }

    public function orders() 
    {
        $orders = Sale::select("*" , "sales.id as id")->where("type", "order")->leftJoin("sale_items as s" , "s.sale_id" , '=', "sales.id" )->orderBy("sales.id", "DESC")->paginate(25);
        return view('backend.orders.allorders', ["orders" => $orders, "title" => "Orders"]);
    }

}
