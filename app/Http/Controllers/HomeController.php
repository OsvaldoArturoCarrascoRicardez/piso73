<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Page;
use App\Sale;
use DB,
    Auth,
    Mail;
use App\Mail\Test;
use App\Mail\Contact;

class HomeController extends Controller
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

    public function index(Request $request)
    {
        $pagina = $request->input("page", "0");
        $getParams = $request->all();
        if ($pagina === "2") {
            $categories = Category::get();
            return view('home', compact('categories'));
        }
        return view('welcome');
    }

    public function about()
    {
        $page = Page::find(3);
        return view('pages.about', ['page' => $page]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function import()
    {
        $sales = Sale::get();
        foreach ($sales as $sale) {
            $items = DB::table("sale_items")->where("sale_id", $sale->id)->get();
            $amount = 0;
            foreach ($items as $item) {
                $amount = $item->quantity * $item->price;
            }
            Sale::where("id", $sale->id)->update(array("amount" => $amount));
        }
        echo "Done";
        //DB::unprepared(file_get_contents('db/pos.sql'));
    }

}
