<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $data = [
            'categories' => Category::paginate(15),
        ];

        return view('backend.category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
	public function store(Request $request)
    {

		$salida = [];
		$salida['status'] = FALSE;

		$validatedData = $request->validate([
         'imagen' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
		 'nombreCat' => 'required|min:3',
        ]);

		$archivo = $request->file('imagen');
		
		$fileName = 'categoria-' . time() . '.' . $archivo->getClientOriginalExtension();
		$path = $archivo->storeAs('public/categorias', $fileName);

		$datos = [];
		$datos['name'] = $validatedData['nombreCat'];
		$datos['categoryPic'] = $path;
		
		$category = Category::create($datos);
		
		if ( $category !== null && $category->id > 0){
			$salida['status'] = TRUE;
			$salida['path'] = $path;
		}

		return response()->json($salida);
    }
	
	/**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return view('backend.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('backend.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     * @param int               $id
     */
    public function update(Request $request, $id)
    {
        $form = $request->all();

        $customer = Category::findOrFail($id);
        $customer->update($form);

        return redirect('categories')->with('message-success', 'Categoria Actualizada!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     */
    public function destroy($id)
    {
        $customer = Category::findOrFail($id);
        $customer->delete();

        return redirect('categories')->with('message-success', 'Categoria Eliminada.');
    }

    ////// User upload photo and resize to 145x145 to Thumb
    public function updatePhotoCrop(Request $request) 
    {
		$newPathWWWRoot = null;
		if (env('SYNO_PUBLIC_PATH') !== NULL) {
			$newPathWWWRoot = realpath(env('SYNO_PUBLIC_PATH'));
		}
		
		$cropped_value = $request->input("cropped_value"); 
        $image_edit = $request->input("image_edit"); 
        $cp_v = explode(",", $cropped_value);

        $file_name = $image_edit . ".jpg";
        if(empty($image_edit)) { 
            $file_name = "temp_img_c.jpg";
        }
        
        if (Input::hasFile('file')) {

            $store_path = public_path("uploads/category");
            $img = Image::make($store_path . "/$file_name");
			
			// si hay una nueva ruta disponible para guardar imagenes, en production, 
			// y al mismo tiempo en app/public para su migración_
			if ($newPathWWWRoot !== NULL){
				// Se guarda en la nueva ruta WWWRoot, del Synology u otro_
				$img->save($newPathWWWRoot . "/uploads/category" . "/$file_name"); 
				
			}
			
			
            if($img->exif('Orientation')) { 
                $img = orientate($img, $img->exif('Orientation'));
            }
            
            $path2 = public_path("uploads/category/thumb/$file_name");

			// previene el fondo Negro_
			$img->rotate($cp_v[4] * -1, '#ffffff');
			$img->crop($cp_v[0], $cp_v[1], $cp_v[2], $cp_v[3]);
            
			$img->fit(205)->save($path2);   //- edited
			if ($newPathWWWRoot !== NULL){
				// Se guarda en la nueva ruta WWWRoot, del Synology u otro_
				$img->fit(205)->save($newPathWWWRoot . "/uploads/category/thumb" . "/$file_name"); 
				
			}
			return  response(url("uploads/category/thumb/$file_name"), 200);
        }
        
        if($image_edit != "") {
            $path = public_path("uploads/category/$file_name");
            $img = Image::make($path);
            $path2 = public_path("uploads/category/thumb/$file_name");
            $img->rotate($cp_v[4] * -1);                
            $img->crop($cp_v[0], $cp_v[1], $cp_v[2], $cp_v[3]);
             $img->fit(265, 205)->save($path2);
            echo url("uploads/category/thumb/$file_name"); exit;
        }
    }

}
