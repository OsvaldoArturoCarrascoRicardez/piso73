<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MngrImages extends Controller
{
	
	public function uploadImage(Request $request){
		$validator = Validator::make($request->all(), [
			'photo' => 'required|image'
        ]);
     
        if ($validator->passes()) {
            return response()->json(['success'=>'Added new records.']);
        }
     
        return response()->json(['error'=>$validator->errors()->all()]);
	}
	
}
