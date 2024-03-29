<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Symfony\Component\HttpFoundation\Response;

class AjaxCheckController extends Controller
{

    public function __construct()
    {

        if (Auth::check()) {
            Log::info('Auth::check');
        } else {
            Log::info('Auth::NonCheck');
        }

    }

    public function checkVentas(Request $request)
    {
        $method = $request->method();
        $user = Auth::user();


        $peticion = 'HTTP';

        $error1 = FALSE;
        $error2 = FALSE;


        if ($request->ajax()) {
            $peticion = 'Ajax';
        } else {
            $error1 = TRUE;
        }


        if ($request->isMethod('post')) {
            //
        } else {
            $error2 = TRUE;
        }


        $respuesta = array(
            "status" => "error",
            "peticion" => $peticion,
            "method" => $method,
            "message" => "Bad Request"
        );

        if ($error1 || $error2) {

            return response()->json($respuesta, Response::HTTP_BAD_REQUEST);
        }

        $respuesta = array(
            "status" => "sucess",
            "peticion" => $peticion,
            "method" => $method,
            'hora' => new \DateTime(),
            "message" => "Request Acepted"
        );


        Log::info('Log message',
            array('text' => 'Ventana de Ventas, Modulo en uso',
                'peticion' => $peticion,
                'usuario' => $user->name,
                'hora' => new \DateTime()
            ));

        return response()->json($respuesta);
    }

}
