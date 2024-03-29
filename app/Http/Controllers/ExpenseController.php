<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expense;
use App\Gastos2;
use App\UnidadMedida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $data = array();

        $results = DB::table('expenses_dos as gastos')
            ->join('unidadmedidavarios as um', 'um.clave', '=', 'gastos.unit_value')
            ->select('gastos.id', 'gastos.cashier_id', 'gastos.description', 'gastos.motive', 'gastos.expense_amount', 'gastos.quantity',
                'gastos.image_id', 'gastos.expencePic', 'gastos.created_at', 'gastos.updated_at', 'um.clave', 'um.nombre')
            ->where('deleted', Gastos2::ACTIVE)
            ->orderBy("gastos.created_at", "DESC")
            ->paginate(25);


        $data['expenses'] = $results;

        return view('backend.expenses.index3', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitList = UnidadMedida::get();

        return view('backend.expenses.create', ['unitList' => $unitList]);
    }

    public function store(Request $request)
    {

        $salida = [];
        $salida['status'] = FALSE;


        $validatedData = $request->validate([
            'imagen' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            'expense_amount' => 'required|required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'motive' => 'nullable|string',
            'fecha' => 'nullable|string',
            'unidadV' => 'required',
        ]);

        $datos = [];

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $noCripto = md5(time() . $archivo->getClientOriginalExtension() . rand(11111, 99999));
            $fileName = 'gastos-' . $noCripto . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/gastosgastos', $fileName);

            $datos['expencePic'] = $fileName;
        }

        $datos['cashier_id'] = Auth::user()->id;

        $datos['expense_amount'] = $validatedData['expense_amount'];
        $datos['description'] = $validatedData['description'];

        if (array_key_exists('motive', $validatedData)) {
            $datos['motive'] = $validatedData['motive'];
        }

        if (array_key_exists('fecha', $validatedData)) {
            $strFecha = $validatedData['fecha'] . ' ' . date("H:i:s");


            //$datos['created_at'] =  '' . $validatedData['fecha'] . ' ' . date("H:i:s");
            //$dateformat = Carbon::createFromFormat('d-m-Y H:i:s',  $validatedData['fecha'] . ' ' . date("H:i:s")  /*$date*/);
            //$dateformat = Carbon::createFromFormat('Y-m-d H:i:s',  $validatedData['fecha'] . ' ' . date("H:i:s")  /*$date*/)
            $datos['created_at'] = $strFecha;  /// $dateformat;

        }


        $datos['unit_value'] = $validatedData['unidadV'];


        $gastos = Gastos2::create($datos);


        //$id_ = $category->id;
        $salida['input_'] = $datos;

        if ($gastos !== null && $gastos->id > 0) {
            $salida['status'] = TRUE;
            //$salida['url'] = url("sales/receipt/".$sale->id);
            // revomve
            $salida['__id'] = $gastos->id;

        }

        return response()->json($salida);
        //---
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $gastos = Gastos2::findOrFail($id);


        if ($gastos == null) {
            return abort(403, 'Unauthorized action.');

        }

        $unitList = UnidadMedida::all();

        return view('backend.expenses.show2', compact('gastos', 'unitList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        //
        // --->  only if need a query _$unitList = UnidadMedida::get();

        $gastos = Gastos2::findOrFail($id);

        if ($gastos == null) {
            // un posible 404
            //return view('backend.expenses.show', compact('gastos'));
            //return abort(503);
            return abort(403, 'Unauthorized action.');

        }

        $unitList = UnidadMedida::all();


        //_debug ok dd($gastos, $unitList ) ;

        return view('backend.expenses.edit', compact('gastos', 'unitList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request/*, $id*/)
    {
        $salida = [];
        $salida['status'] = FALSE;

        //- passed

        $validatedData = $request->validate([
            '_id' => 'required',
            'imagen' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg', /* max:2048    svg|max:4096*/
            'expense_amount' => 'required|required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'motive' => 'nullable|string',
            'cantidad' => 'required|required|regex:/^\d+(\.\d{1,4})?$/',
            'unidadV' => 'required',
            'fecha' => 'nullable|string',
            '_method' => 'required',   /* Requerido para el método update */

        ]);
        //  {"status":false,"data":{"expense_amount":"35","description":"test precio","unidadV":"0"}}

        $datos = [];    // array Para guardar los datos
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $noCripto = md5(time() . $archivo->getClientOriginalExtension() . rand(11111, 99999));
            //___$fileName = 'gastos-' . $noCripto .  '.' . $archivo->getClientOriginalExtension();
            $fileName = 'gastos-' . $noCripto . '.' . strtolower($archivo->getClientOriginalExtension());
            $savingPath = $archivo->storeAs('public/gastosgastos', $fileName);

            $datos['expencePic'] = $savingPath;
            // usamos el nombre del archivo para su almacenamiento-
            $datos['expencePic'] = $fileName;
        } /*
		el esle por si hay imagen_
		else {
			// si ya no hay image, 
			
			$datos['expencePic'] = '';
			
		}*/

        //-----

        /*
        if (TRUE){

            $salida['data'] = $request->all();
            $salida['data2'] = $validatedData;
            $salida['data2'] = $datos;

            return response()->json($salida, 503);
        }*/


        //---  Saving DataBase Update
        $gastos = Gastos2::findOrFail($validatedData['_id']);

        if ($gastos == null) {
            $salida['404'] = '404';
            return response()->json($salida, 503);

        }


        //$datos = [];
        /// prevent logout:
        //   quien lo actualizó
        $datos['cashier_id'] = Auth::user()->id;

        $datos['expense_amount'] = $validatedData['expense_amount'];
        $datos['description'] = $validatedData['description'];
        //$datos['motive'] =  $validatedData['motive'];

        if (array_key_exists('motive', $validatedData)) {
            $datos['motive'] = $validatedData['motive'];
        }


        if (array_key_exists('fecha', $validatedData)) {
            $strFecha = $validatedData['fecha'] . ' ' . date("H:i:s", strtotime($gastos->created_at));
            $datos['created_at'] = $strFecha;  /// $dateformat;

        }


        $datos['unit_value'] = $validatedData['unidadV'];
        $datos['quantity'] = $validatedData['cantidad'];


        //$gastos = Gastos2::create($datos);
        // $gastos->save();
        $gastos->update($datos);


        /*    ---
        if (TRUE){

            $salida['data'] = $request->all();
            $salida['data2'] = $validatedData;
            //$salida['data2'] = $datos;
            $salida['data2'] = $gastos;

            return response()->json($salida, 503);
        }
        */


        //$id_ = $category->id;

        $salida['__datos'] = $datos;


        if ($gastos !== null && $gastos->id > 0) {
            $salida['status'] = TRUE;
            //$salida['url'] = url("sales/receipt/".$sale->id);
            // revomve
            $salida['__id'] = $gastos->id;
            $salida['valuesss'] = $gastos;
        }

        return response()->json($salida);
        //---
    }

    public function get(Request $request)
    {
        $id = $request->input("id");
        $expense = Expense::where("id", $id)->first();
        echo json_encode($expense);
    }

    public function delete(Request $request)
    {
        $id = $request->input("id");
        $expnese = Expense::where("id", $id)->delete();
        echo json_encode($expnese);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect('expenses')
            ->with('message-success', 'Expense deleted!');
    }

}
