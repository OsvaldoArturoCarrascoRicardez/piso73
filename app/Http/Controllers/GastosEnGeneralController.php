<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GastosGeneral;
use Illuminate\Support\Facades\Log;

class GastosEnGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //$unitList = Gastos1::get();
        $data = [
            'expenses' => GastosGeneral::latest()->paginate(20),
        ];


        if (false) {
            dd($data, $request->all());
        }

        return view('backend.gastos_general.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //        
        return view('backend.gastos_general.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $salida = [];
        $salida['status'] = FALSE;

        $datos_insert = $this->validateForCreate($request);

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        $gastos = GastosGeneral::create($datos_insert);


        // $salida['input_'] = $datos;

        if ($gastos !== null && $gastos->id > 0) {

            $clientIP = \Request::ip();

            Log::info('Saving Gastos en General',
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $id = test_input($id);

        $gastos = GastosGeneral::findOrFail($id);

        if ($gastos == null) {
            return abort(403, 'Unauthorized action.');
        }

        return view('backend.gastos_general.edit', compact('gastos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $salida = [];
        $salida['status'] = FALSE;

        $datosU = $this->validateForUpdate($request);

        //----------------------------------

        $gastos = GastosGeneral::findOrFail($datosU['id']);

        if ($gastos == null) {
            $salida['404'] = '404';
            return response()->json($salida, 503);
        }

        //-------------
        if (array_key_exists('nuevaFecha', $datosU)) {
            $strFecha = $datosU['nuevaFecha'] . ' ' . date("H:i:s", strtotime($gastos->created_at));
            $gastos['created_at'] = $strFecha;  /// $dateformat;
        }
        //-------------


        $gastos->update($datosU);

        if ($gastos !== null && $gastos->id > 0) {

            $clientIP = \Request::ip();

            Log::info('Update Gastos en General',
                array('gasto' => json_encode($gastos),
                    'remoteip' => $clientIP,
                    'hora' => new \DateTime()
                ));

            $salida['status'] = TRUE;

            $salida['__id'] = $gastos->id;
            $salida['valuesss'] = $gastos;
        }

        return response()->json($salida);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function validateForCreate(Request $request)
    {
        $validatedData = $request->validate([
            'expense_amount' => 'required|required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'descriptionLarga' => 'nullable|string',
            'fecha' => 'nullable|string',
            //'_method' => 'required',   /* Requerido para el método update */

        ]);

        $datos = array();

        $datos['price'] = $validatedData['expense_amount'];
        $datos['title'] = $validatedData['description'];
        $datos['description'] = $validatedData['descriptionLarga'];

        if (array_key_exists('fecha', $validatedData)) {
            $strFecha = $validatedData['fecha'] . ' ' . date("H:i:s");
            $datos['created_at'] = $strFecha;  /// $dateformat;
        }

        return $datos;
    }

    protected function validateForUpdate(Request $request)
    {
        $validatedData = $request->validate([
            '_id' => 'required',
            'expense_amount' => 'required|required|regex:/^\d+(\.\d{1,2})?$/',
            'description' => 'required',
            'descriptionLarga' => 'nullable|string',
            'fecha' => 'nullable|string',

        ]);

        $datos = array();

        $datos['id'] = $validatedData['_id'];
        $datos['price'] = $validatedData['expense_amount'];
        $datos['title'] = $validatedData['description'];
        $datos['description'] = $validatedData['descriptionLarga'];

        if (array_key_exists('fecha', $validatedData)) {
            $datos['nuevaFecha'] = $validatedData['fecha'];
        }

        return $datos;
    }

}
