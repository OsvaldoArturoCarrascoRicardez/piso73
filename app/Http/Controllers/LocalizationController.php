<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use App;
use Redirect;

class LocalizationController extends Controller
{

    /**
     * Change the Language and save it to session.
     */
    public function index($locale) 
    {
        session(['locale' => $locale]);
        App::setLocale($locale);
        return Redirect::back();
    }

}
