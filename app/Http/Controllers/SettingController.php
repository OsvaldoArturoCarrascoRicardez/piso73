<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class SettingController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View
     */
    public function edit()
    {
        $data = [
            'settings' => Setting::all(),
        ];

        return view('backend.settings.general.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request)
    {
        $form = $request->except('_method', '_token' , 'logo');
        $form = collect($form);

        $form->each(
            function ($value, $key) {
                $setting = Setting::where(['key' => $key])->first();
                $setting->value = $value;
                $setting->save();
            }
        );
		
		$file = Input::file('logo');

		if (Input::hasFile('logo')) {
			$file_name = "logo.jpg";
			$store_path = public_path("uploads"); 
            $path = $file->move($store_path, $file_name);
			
			if (env('SYNO_PUBLIC_PATH') !== NULL) {
				$newPathWWWRoot = realpath(env('SYNO_PUBLIC_PATH'));
			}
			
			if ($newPathWWWRoot !== NULL){
				$nuevaRuta = $newPathWWWRoot . "/uploads/logo.jpg"; 
				copy($path , $nuevaRuta);
			}

		}
		 
        return redirect('settings/general')->with('message-success', 'Cambios guardados!');
    }

}
