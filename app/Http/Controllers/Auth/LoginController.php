<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Activity;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($request->input('email'), FILTER_VALIDATE_INT) ? 'ID_Number' : 'email';
        $request->merge([$field => $request->input('email')]);
        if (Auth::attempt($request->only($field, 'password'))) {

            $user = User::find(Auth::user()->id);

            Activity::insert(array("user_id" => $user->id, "datetime" => date("Y-m-d H:i:s"), "activity" => "Login"));

            return redirect()->route('reportes.diario');
        }

        /* withSuccess */
        return redirect('/login')->
        withInput()->
        withErrors(
            [
                'error' => 'These credentials do not match our records.',
            ]
        );
    }

    public function logout()
    {

        $user_id = (Auth::user()) ? Auth::user()->id : null;

        Activity::insert(array("user_id" => $user_id, "datetime" => date("Y-m-d H:i:s"), "activity" => "Logout"));

        session_start();

        $_SESSION = [];

        Auth::logout();
        return redirect('login');
    }
}
