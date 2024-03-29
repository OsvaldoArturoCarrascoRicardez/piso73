<?php

namespace App\Http\Controllers;

use App\Sale;
use PDF;
use Illuminate\Http\Request;
use App\Role;

class StatisticalController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $params = $request->all();
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

    public function ventas()
    {
        $data['roles'] = Role::get();

        return view('backend.graphics.ventas.index', $data);
    }

}
