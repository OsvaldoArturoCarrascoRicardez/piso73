<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Views\ViewEgresos;
use App\UnidadMedida;
use App\Gastos2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EgresosCajaController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $results = ViewEgresos::defaultQuery()
            ->orderBy("gastos.created_at", "DESC")
            ->paginate(25);
        $data['expenses'] = $results;

        if (false) {
            dd($data, $request->all());
        }


        return view('backend.egresos_fondo_caja.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitList = UnidadMedida::get();

        return view('backend.egresos_fondo_caja.create', ['unitList' => $unitList]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $salida = [];
        $salida['status'] = FALSE;


        $validatedData = $request->validate([
            'imagen' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            'expense_amount' => 'required|required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'motive' => 'nullable|string',
            'cantidad' => 'required|required|regex:/^\d+(\.\d{1,4})?$/',
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

        /// prevent logout:
        $datos['cashier_id'] = Auth::user()->id;

        $datos['quantity'] = $validatedData['cantidad'];

        $datos['expense_amount'] = $validatedData['expense_amount'];
        $datos['description'] = $validatedData['description'];

        if (array_key_exists('motive', $validatedData)) {
            $datos['motive'] = $validatedData['motive'];
        }

        if (array_key_exists('fecha', $validatedData)) {
            $strFecha = $validatedData['fecha'] . ' ' . date("H:i:s");

            $datos['created_at'] = $strFecha;  /// $dateformat;

        }

        $datos['unit_value'] = $validatedData['unidadV'];

        $gastos = Gastos2::create($datos);

        $salida['input_'] = $datos;

        if ($gastos !== null && $gastos->id > 0) {

            $clientIP = \Request::ip();

            Log::info('Saving Fondo Caja',
                array('gasto' => json_encode($gastos),
                    'remoteip' => $clientIP,
                    'hora' => new \DateTime()
                ));


            $salida['status'] = TRUE;

            $salida['__id'] = $gastos->id;
        }

        return response()->json($salida);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        //
        $id = test_input($id);

        $gastos = Gastos2::findOrFail($id);

        if ($gastos == null) {
            return abort(403, 'Unauthorized action.');

        }

        $unitList = UnidadMedida::all();

        return view('backend.egresos_fondo_caja.show', compact('gastos', 'unitList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        //
        $id = test_input($id);

        $gastos = Gastos2::findOrFail($id);

        if ($gastos == null) {
            // un posible 404 
            //return view('backend.expenses.show', compact('gastos'));
            //return abort(503);
            return abort(403, 'Unauthorized action.');

        }

        $unitList = UnidadMedida::all();


        return view('backend.egresos_fondo_caja.edit', compact('gastos', 'unitList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        //
        $salida = [];
        $salida['status'] = FALSE;

        $datosU = $this->validateForUpdate($request);

        // Save image
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');

            $noCripto = md5(time() . $archivo->getClientOriginalExtension() . rand(11111, 99999));
            $fileName = 'gastos-' . $noCripto . '.' . strtolower($archivo->getClientOriginalExtension());
            $savingPath = $archivo->storeAs('public/gastosgastos', $fileName);

            // usamos el nombre del archivo para su almacenamiento-
            $datosU['expencePic'] = $fileName;
        }


        //xyz
        $gastos = Gastos2::findOrFail($datosU['id']);

        if ($gastos == null) {
            $salida['404'] = '404';
            return response()->json($salida, 503);

        }

        if (array_key_exists('nuevaFecha', $datosU)) {
            $strFecha = $datosU['nuevaFecha'] . ' ' . date("H:i:s", strtotime($gastos->created_at));
            $datosU['created_at'] = $strFecha;  /// $dateformat;
        }

        $gastos->update($datosU);

        if ($gastos !== null && $gastos->id > 0) {

            $clientIP = \Request::ip();

            Log::info('Update Fondo Caja',
                array('gasto' => json_encode($gastos),
                    'remoteip' => $clientIP,
                    'hora' => new \DateTime()
                ));

            $salida['status'] = TRUE;

            $salida['__id'] = $gastos->id;
            $salida['valuesss'] = $gastos;
        }

        return response()->json($salida);
        //return response()->json($datosU, 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        //
        return abort(403, 'Unauthorized action.');
    }

    protected function validateForUpdate(Request $request)
    {
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


        $datos = array();

        $datos['id'] = $validatedData['_id'];

        $datos['cashier_id'] = Auth::user()->id;

        $datos['expense_amount'] = $validatedData['expense_amount'];
        $datos['description'] = $validatedData['description'];

        if (array_key_exists('motive', $validatedData)) {
            $datos['motive'] = $validatedData['motive'];
        }

        if (array_key_exists('fecha', $validatedData)) {
            //$strFecha = $validatedData['fecha'] . ' ' . date("H:i:s", strtotime($gastos->created_at) );
            ///$datos['created_at'] =  $strFecha;  /// $dateformat;    
            $datos['nuevaFecha'] = $validatedData['fecha'];

        }

        $datos['unit_value'] = $validatedData['unidadV'];
        //$datos['quantity'] =  $validatedData['cantidad'];
        if (Gastos2::UM_VENTA_GNRL === $datos['unit_value']) {
            $datos['quantity'] = 1;
        } else {
            $datos['quantity'] = $validatedData['cantidad'];
        }

        //return $validatedData;
        return $datos;
    }

}
