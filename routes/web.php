<?php

use App\Http\Controllers\AjaxCheckController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CorteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EgresosCajaController;
use App\Http\Controllers\EySDiarioController;
use App\Http\Controllers\GastosEnGeneralController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\MngrImages;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TempSales;
use App\Http\Controllers\TotalMonthlyController;
use App\Http\Controllers\UserController;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middlewareGroups' => 'web'], function () {
    Route::get('logout', [LoginController::class, 'logout']);
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('localization/{locale}', [LocalizationController::class, 'index']);
    Route::get('/admin', [DashboardController::class, 'index'])->name('page.admin');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('page.dashboard');

    Route::get('/home', function () {
        return redirect('/dashboard');
    });
    Route::get('/ingresar', function () {
        return redirect('/dashboard');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('categories', 'CategoryController');
    Route::resource('products', 'ProductController');

    Route::resource('users', 'UserController');
    Route::post('product/upload_photo', [ProductController::class, 'uploadPhoto']);
    Route::post('product/upload_photo_crop', [ProductController::class, 'updatePhotoCrop']);
    Route::post('category/upload_photo_crop', [CategoryController::class, 'updatePhotoCrop']);
    Route::post('product/addToArchive', [ProductController::class, 'addToArchive']);

    Route::resource('sales', SaleController::class)->only(['create', 'store']);
    Route::get('sales/receipt/{id}', [SaleController::class, 'receipt']);
    Route::post('sales/complete_sale', [SaleController::class, 'completeSale']);
    Route::get('sales/', [SaleController::class, 'index']);
    Route::get('sales/cancel/{id}', [SaleController::class, 'cancel']);

    Route::post('sale/hold_order', [SaleController::class, 'holdOrder']);
    Route::post('sale/hold_orders', [SaleController::class, 'holdOrders']);
    Route::post('sale/view_hold_order', [SaleController::class, 'viewHoldOrder']);
    Route::post('sale/hold_order_remove', [SaleController::class, 'removeHoldOrder']);

    //  ruta temp para arreglar caja
    Route::resource('tmpsales', 'TempSales', ['only' => ['create', 'store']]);
    Route::resource('newsale', 'TempSales', ['only' => ['create', 'store']]);

    Route::any('sales/nextStep', [AjaxCheckController::class, 'checkVentas'])->name('nextStep');

    Route::post('/uploads/images', [MngrImages::class, 'uploadImage']);

    Route::get('imagenes/gastos/{filename}', [ImagenController::class, 'getImageGastos'])->name('gastos.displayImage');

    Route::get('reports/sales_by_products', [ReportController::class, 'SalesByProduct'])->name('reportes.porProductos');
    Route::get('reports/graphs', [ReportController::class, 'Graphs']);
    Route::get('reporte/corte', [CorteController::class, 'index'])->name('reportes.cortes');
    Route::get('reporte/diario', [EySDiarioController::class, 'index'])->name('reportes.diario');

    // Egresos de Fondo de Caja
    Route::resource('fondocaja', 'EgresosCajaController');
    Route::post('/fondocaja/update', [EgresosCajaController::class, 'update'])->name('fondocaja.update');

    // otros gastos
    Route::resource('ctrlgastos', 'GastosEnGeneralController');
    Route::post('/ctrlgastos/update', [GastosEnGeneralController::class, 'update'])->name('ctrlgastos.update2');

    Route::get('reporte/mensual', [TotalMonthlyController::class, 'resumenMensual'])->name('reporte.mensual');

    //// Tables
    Route::get("/tables", [TableController::class, 'index']);
    Route::post("tables/save", [TableController::class, 'store']);
    Route::post("tables/get", [TableController::class, 'get']);
    Route::post("tables/delete", [TableController::class, 'delete']);

    Route::get('/roles', [RoleController::class, 'index']);
    Route::get("/roles/edit/{id}", [RoleController::class, 'edit']);
    Route::post("/roles/update", [RoleController::class, 'update']);

    Route::group(['prefix' => 'settings'], function () {
        Route::get('profile', [ProfileController::class, 'edit']);
        Route::post('profile', [ProfileController::class, 'update']);
        Route::get('general', [SettingController::class, 'edit']);
        Route::post('general', [SettingController::class, 'update']);
        Route::post('update_password', [ProfileController::class, 'updatePassword']);
    });
}
);
