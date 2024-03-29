<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Response;

class ImagenController extends Controller
{
	public function index()
    {
		abort(404);
    }

	public function getImageGastos($filename)
	{

		//// si exite la funcion: $path = storage_public('images/' . $filename);
		$exists = Storage::disk('public')->exists('gastosgastos/'.$filename);

		if(!$exists){
			return view('backend.partials.img404');
		}

		$content = Storage::disk('public')->get('gastosgastos/'.$filename);
		$mime = Storage::disk('public')->getMimeType('gastosgastos/'.$filename);
		$size_ = Storage::disk('public')->size('gastosgastos/'.$filename);

		$response = Response::make($content, 200);

		$response->header("Content-Type", $mime);

		return $response;
	}

}
