<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Category;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('q', '');
        $data["q"] = $keyword;
        $ids = array();
        if ($keyword) {
            $users = User::where("role_id", "!=", 4)->where("name", "like", "%$keyword%")->get();
            foreach ($users as $user) {
                $ids[] = $user->id;
            }

        }
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) {
            $sales = Sale::orderBy("sales.id", "DESC")->paginate(25);


        } else {
            $sales = Sale::where("cashier_id", Auth::user()->id)
                ->orderBy("sales.id", "DESC")->paginate(25);

        }

        $data['sales'] = $sales;


        return view('backend.sales.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['categories'] = Category::get();
        $data['products'] = Product::get();
        $data['tables'] = DB::table("tables")->get();

        ///---  2022-01-26
        // $data['credito'] = value of percent.


        return view('backend.sales.create', $data);
    }

    public function receipt($id)
    {
        $data = ['sale' => Sale::findOrFail($id),];

        return view('backend.sales.receipt', $data);
    }

    public function completeSale(Request $request)
    {
        $form = $request->all();
        $items = $request->input('items');

        /// Modif 11 Feb y22
        $suma_importes = 0;
        foreach ($items as $item) {
            //$amount += $item['price'] * $item['quantity'];
            $suma_importes += $item['price'] * $item['quantity'];
        }
        // Calculo anterior
        //--- $amount += $request->input('vat') + $request->input('delivery_cost') - $request->input('discount');
        // operación uno,
        //$amount += $request->input('vat') + $request->input('delivery_cost') - $request->input('discount');

        // suma del pedido, decuento a parte
        $form['amount'] = $suma_importes;

        $rules = Sale::$rules;
        $rules['items'] = 'required';

        //---
        //   $form['amount'] = $amount;    2021-12-27
        if ($form['comments'] === NULL) {
            $form['comments'] = '';

        }

        $tax_card = 0.0;
        $form['tax_card'] = $tax_card;
        if ("Card" === $form['payment_with']) {
            $subtotal_card = $suma_importes + $form['value_tip'];
            $tax_card = $subtotal_card * 0.00;
            $form['tax_card'] = round($tax_card, 2, PHP_ROUND_HALF_DOWN);

        }

        //---


        // tmp oct
        $mesa_ = $request->input('table_id', '10');
        $form['c_meta06'] = $mesa_;


        $validator = Validator::make($form, $rules);
        //---
        // force a error
        /*if (true){
            return response()->json(
                [
                'errors' => $validator->errors()->all(),
                ], 400
            );

        }*/


        if (false) {

            // return response()->json($form, 500);
            return response()->json($form);

        }


        $salida = [];
        $salida['status'] = FALSE;
        //---
        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()->all(),
                ], 400
            );
        }


        $parte1 = date('Y');
        $parte2 = base_convert(time(), 10, 16);
        $parte3 = rand(1001, 9999);

        $newfolio = '' . $parte1 . '-' . $parte2 . '-' . $parte3 . '';


        $form['folio'] = $newfolio;


        // pre update Fecha ingreso___

        $fechaHoy = date('Y-m-d');

        if (array_key_exists('newfecha', $form)) {
            if ($fechaHoy === $form['newfecha']) {
                unset($form['newfecha']);

            } else {
                //en el inser agregra el timestan de hpras
            }
            //  $datos['created_at'] =  $strFecha;  /// $dateformat;
        }


        if (array_key_exists('newfecha', $form)) {
            //---
            $form['NumComanda'] = test_input($form['NumComanda']);
        }


        $sale = Sale::createAll($form);


        if ($sale !== null && $sale->id > 0) {
            $salida['status'] = TRUE;
            $salida['url'] = url("sales/receipt/" . $sale->id);

            $salida['numTicket'] = $sale->id;

            Log::info('-------------------------------------------------');
            Log::info('Nota: ' . PHP_EOL . wordwrap(json_encode($sale), 160, PHP_EOL, true));

            Log::info('items: ' . json_encode($sale->items, JSON_PRETTY_PRINT));
            //Log::info('items: ' . PHP_EOL . wordwrap( json_encode($sale->items), 80, PHP_EOL, true ) )   ;
            //},{
            //Log::info('items: ' . PHP_EOL . explode("},{", json_encode($sale->items) ) )   ;
            // Log::info('items: ' . PHP_EOL ,			 explode("},{", json_encode($sale->items) ) )   ;
            //Log::info('items: ' . PHP_EOL ,	$sale->items )   ;

        }

        //return url("sales/receipt/".$sale->id);
        return response()->json($salida);

    }

    public function cancel($id)
    {
        Sale::where("id", $id)->update(array("status" => 0));
        return redirect("sales");

    }

    ////---- modiff 17 feb
    public function holdOrder(Request $request)
    {
        //---
        $arreglo = array();
        $arreglo['status'] = "success";
        $arreglo['message'] = "";
        $arreglo['process'] = time();

        //---
        $id = $request->input("id");
        $comment = $request->input("comment");
        $table_id = $request->input("table_id");
        $cart = json_encode($request->input("cart"));
        if ($id) {
            DB::table("hold_order")->where("id", $id)->update(array("table_id" => $table_id, "cart" => $cart, "comment" => $comment, "user_id" => Auth::user()->id));
            $arreglo['message'] = 'Mesa id ' . $table_id . ' En espera + Productos.';
            return response()->json($arreglo);
        }
        $table = DB::table("hold_order")->where("table_id", $table_id)->where("status", 0)->count();
        if ($table > 0) {
            $arreglo['message'] = ""/*'Mesa id ' . $table_id . ' En espera'*/
            ;
            $arreglo['holdTable'] = "Mesa en espera.";
            $arreglo['table_id'] = $table_id;
            $arreglo['status'] = "holdTableFailed";
            return response()->json($arreglo);
        }

        DB::table("hold_order")->insert(array("table_id" => $table_id, "cart" => $cart, "comment" => $comment, "user_id" => Auth::user()->id));

        $arreglo['message'] = 'Mesa id ' . $table_id . ' En espera';
        return response()->json($arreglo);
    }

    public function viewHoldOrder(Request $request)
    {
        $id = $request->input("id");
        $order = DB::table("hold_order")->where("id", $id)->first();

        //echo $order->cart;
        return response($order->cart)->header('Content-Type', 'text/plain');
    }

    public function holdOrders(Request $request)
    {
        $orders = DB::table("hold_order")->where("status", 0)->get();
        foreach ($orders as $order) {
            $user = User::find($order->user_id);
            $table = DB::table("tables")->where("id", $order->table_id)->first();
            $order->username = "";
            if (!empty($user)) {
                $order->username = $user->name;
                //$order->table = "No Table Found";
                $order->table = "No Encontrado.";
                if (!empty($table))
                    $order->table = $table->table_name;
            }
        }
        echo json_encode($orders);
    }

    public function removeHoldOrder(Request $request)
    {
        $id = $request->input("id");
        DB::table("hold_order")->where("id", $id)->update(array("status" => 1));
    }

}
