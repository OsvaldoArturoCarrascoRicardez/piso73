<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->get('q', '');
        $products = Product::searchByKeyword($keyword)->orderBy("name", "ASC")->get();
        $products = !empty($keyword) ? $products->appends(['q' => $keyword]) : $products;

        $data = [
            'products' => $products,
            'keyword' => $keyword,
        ];

        if (false) {
            dd($data);
        }

        return view('backend.products.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('backend.products.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form = $request->all();
        $price = $request->input("price");
        $titles = $request->input("title");
        unset($form['price']);
        unset($form['title']);
        unset($form['price_counter']);
        $form['prices'] = json_encode($price);
        $form['titles'] = json_encode($titles);

        $product = Product::create($form);

        return redirect('products')->with('message-success', 'Producto creado!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        return view('backend.products.show', compact('product', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        return view('backend.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests $request
     * @param int $id
     */
    public function update(Requests\UpdateProduct $request, $id)
    {
        $form = $request->all();
        $price = $request->input("price");
        $titles = $request->input("title");
        unset($form['price']);
        unset($form['title']);
        unset($form['price_counter']);
        $form['prices'] = json_encode($price);
        $form['titles'] = json_encode($titles);

        $product = Product::findOrFail($id);
        $product->update($form);
        $name = $product->id;


        return redirect('products')
            //->with('message-success', 'Product updated!');
            ->with('message-success', 'Producto Actualizado!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('products')
            ->with('message-success', 'Producto Borrado.');
    }

    public function uploadPhoto(Request $request)
    {
        $file = Input::file('croppedImage');

        if (Input::hasFile('croppedImage')) {

            $file_name = "temp.jpg";
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs("uploads/products/", $file_name);
            $img = Image::make($file->getRealPath());
            if ($img->exif('Orientation')) {
                $img = orientate($img, $img->exif('Orientation'));
            }

            $path2 = public_path("storage/products/$file_name");
            $img->fit(250)->save($path2);

            echo url("storage/products/" . $file_name);
        }
    }

    ////// User upload photo and resize to 145x145 to Thumb
    public function updatePhotoCrop(Request $request)
    {
        //---  2021-12-10--- nueva ruta de ubicacion de achivos
        $newPathWWWRoot = null;
        if (env('SYNO_PUBLIC_PATH') !== NULL) {
            $newPathWWWRoot = realpath(env('SYNO_PUBLIC_PATH'));
        }
        //----
        $cropped_value = $request->input("cropped_value");
        $image_edit = $request->input("image_edit");
        $cp_v = explode(",", $cropped_value);

        $file = Input::file('file');
        $file_name = $image_edit . ".jpg";
        if (empty($image_edit)) {
            $file_name = "temp_img_p.jpg";//-- edited__
        }

        if (Input::hasFile('file')) {

            $extension = $file->getClientOriginalExtension();
            $store_path = public_path("uploads/products");
            $path = $file->move($store_path, $file_name);
            $img = Image::make($store_path . "/$file_name");

            // si hay una nueva ruta disponible para guardar imagenes, en production,
            // y al mismo tiempo en app/public para su migración_
            if ($newPathWWWRoot !== NULL) {
                // Se guarda en la nueva ruta WWWRoot, del Synology u otro_
                $img->save($newPathWWWRoot . "/uploads/products" . "/$file_name");

            }
            //-- End  fix


            if ($img->exif('Orientation')) {
                $img = orientate($img, $img->exif('Orientation'));
            }

            $path2 = public_path("uploads/products/thumb/$file_name");
            //_$img->rotate($cp_v[4] * -1);
            //_$img->crop($cp_v[0], $cp_v[1], $cp_v[2], $cp_v[3]);
            //__$img->fit(250)->save($path2);   //- edited


            // previene el fondo Negro_
            $img->rotate($cp_v[4] * -1, '#ffffff');

            $img->crop($cp_v[0], $cp_v[1], $cp_v[2], $cp_v[3]);


            $img->fit(250)->save($path2);   //- edited
            if ($newPathWWWRoot !== NULL) {
                // Se guarda en la nueva ruta WWWRoot, del Synology u otro_
                $img->fit(250)->save($newPathWWWRoot . "/uploads/products/thumb" . "/$file_name");

            }

            //--echo url("uploads/products/thumb/$file_name"); exit;
            return response(url("uploads/products/thumb/$file_name"), 200);

        }

        if ($image_edit != "") {
            $path = public_path("uploads/products/$file_name");
            $img = Image::make($path);
            $path2 = public_path("uploads/products/thumb/$file_name");
            $img->rotate($cp_v[4] * -1);
            $img->crop($cp_v[0], $cp_v[1], $cp_v[2], $cp_v[3]);
            $img->fit(250)->save($path2);
            /// -original-- echo url("uploads/products/thumb/$file_name"); exit;
            echo url("uploads/products/thumb/$file_name");
            exit;


        }

    }

    public function addToArchive(Request $request)
    {
        $id = $request->input("product_id");
        $product = Product::find($id);
        if ($product->is_delete == 1) {
            $value = 0;
        }

        if ($product->is_delete == 0) {
            $value = 1;
        }
        Product::where("id", $id)->update(array('is_delete' => $value));
    }
}
