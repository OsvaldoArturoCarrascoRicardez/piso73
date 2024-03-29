<?php

namespace App\Http\Controllers;

use App\Adjustment;

class AdjustmentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'adjustments' => Adjustment::all(),
        ];

		return view('backend.inventories.adjustments.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
		return view('backend.inventories.adjustments.create');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     */
    public function show($id)
    {
        //
    }
}
