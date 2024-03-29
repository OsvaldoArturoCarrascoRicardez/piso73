<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'permissions' => Permission::all(),
        ];

        //return view('settings.permissions.index', $data);
		//   backend.roles.home
		return view('backend.settings.permissions.index', $data);
    }

    /**
     * Show the form for creating a new resource.

     */
    public function create()
    {
        return view('settings.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     */
    public function store(Request $request)
    {
        $form = $request->all();

        $permission = Permission::create($form);

        return redirect('settings/permissions')
            ->with('message-success', 'Permission created!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        return view('settings.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return view('settings.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param int               $id
     */
    public function update(Request $request, $id)
    {
        $form = $request->all();

        $permission = Permission::findOrFail($id);
        $permission->update($form);

        return redirect('settings/permissions')
            ->with('message-success', 'Permission updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect('settings/permissions')
            ->with('message-success', 'Permission deleted!');
    }
}
