<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Log;

class TempSales extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $data['categories'] = Category::get();
        $data['products'] = Product::get();
        $data['tables'] = DB::table("tables")->get();

        ///---  2022-01-26
        // $data['credito'] = value of percent.

        Log::info('Log message',
            array('text' => 'Apertura Ventana De Venta',
                'hora' => new \DateTime())
        );

        return view('backend.sales.dic22_create', $data);
    }
}

