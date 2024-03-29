<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->role = Role::find($user->role_id);
        }

        $data['users'] = $users;

        return view('backend.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'roles' => Role::get()
        ];

        return view('backend.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Requests\StoreUser $request)
    {
        $form = $request->all();

        $user = User::create($form);
        DB::table('role_user')->insert(array("role_id" => $form['role_id'], 'user_id' => $user->id));


        return redirect('users')
            //->with('message-success', 'User created!');
            ->with('message-success', 'Usuario  ' . $user->name . ' Creado.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('backend.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'user' => $user,
            'roles' => Role::get()
        ];

        return view('backend.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests $request
     * @param int $id
     */
    public function update(Requests\UpdateUser $request, $id)
    {
        $form = $request->all();

        $user = User::findOrFail($id);
        $user->update($form);

        $role = DB::table('role_user')->where("user_id", $id)->first();
        if ($role) {
            DB::table('role_user')->where("user_id", $id)->update(array("role_id" => $form['role_id']));
        } else {
            DB::table('role_user')->insert(array("role_id" => $form['role_id'], "user_id" => $id));
        }

        return redirect('users')
            //->with('message-success', 'User updated!');
            ->with('message-success', 'Se han guardado los cambios de  [' . $user->name . '] .');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('users')->with('message-success', 'Usuario dado de baja.!');
    }

}
