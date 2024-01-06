<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Certificate\CertificateController;
use App\Http\Controllers\Certificate\CertificateLoginController;
use App\Http\Controllers\Chart\ChartController;
use App\Http\Controllers\DataTables\DatatablesController;
use App\Http\Controllers\Export\ExportController;
use App\Http\Controllers\Graphic\GraphicController;
use App\Http\Controllers\Master\MasterBuyerController;
use App\Http\Controllers\Master\MasterItemController;
use App\Http\Controllers\Master\MasterKpController;
use App\Http\Controllers\Master\MasterSupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\SettingController;
use App\Http\Controllers\System\UserController;
use App\Http\Controllers\Warehouse\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Route Utama ke arah halaman index
 */

Route::get('/', function () {
    return view('main.index');
})->middleware('islogin');

/**
 * Route ke arah Upload Sertifikat
 */

Route::prefix('/verify')
    ->name('verify.')
    ->controller(CertificateLoginController::class)
    ->group(function() {
        Route::get('/upload', 'index')->name('index');
        Route::post('/upload', 'authenticateWithCertificate')->name('login');
        Route::get('/purge', 'purgeCertificateSession')->name('purge');
    });

/**
 * Route ke arah Halaman Login
 */

Route::prefix('/auth')
    ->name('auth.')
    ->controller(AuthController::class)    
    ->group(function() {
        Route::get('/login', 'loginIndex')->name('login.index')->middleware('authenticate-with-certificate');
        Route::post('/login', 'loginProcess')->middleware('authenticate-with-certificate');
        Route::get('/register', 'registerIndex')->middleware('authenticate-with-certificate');        
        Route::post('/register', 'registerProcess')->middleware('authenticate-with-certificate');
        Route::post('/logout', 'logout')->middleware('islogin');
    });

/**
 * Route ke arah Datatables
 * Semua Datatable yang muncul di Web, berasal dari Route ini
 */

Route::prefix('/datatables')
    ->name('datatable.')
    ->middleware('islogin')
    ->controller(DatatablesController::class)
    ->group(function() {
        Route::get('/item', 'item')->name('item');
        Route::get('/buyer', 'buyer')->name('buyer');
        Route::get('/kp', 'kp')->name('kp');
        Route::get('/supplier', 'supplier')->name('supplier');
        Route::get('/purchase', 'purchase')->name('purchase');
        Route::get('/warehouse-receive', 'warehouseReceive')->name('warehouse.receive');
        Route::get('/warehouse-request', 'warehouseRequest')->name('warehouse.request');
        Route::get('/users', 'users')->name('users');
        Route::get('/roles', 'roles')->name('roles');
        Route::get('/certificates', 'certificates')->name('certificates');
    });

/**
 * Route ke arah Chart
 * Semua gambar Chart yang ada di web, semua berasal dari Route ini
 */

Route::prefix('/charts')
    ->name('chart.')
    ->middleware('islogin')
    ->controller(ChartController::class)
    ->group(function() {
        Route::get('/kp', 'kpChart')->name('kp');
    });

/**
 * Route ke arah menu Master
 */

Route::prefix('/master/item')
    ->name('master.item.')
    ->middleware('islogin')
    ->controller(MasterItemController::class)
    ->group(function() {
        Route::get('/', 'itemIndex')->name('index');
        Route::post('/store', 'itemStore')->name('store');
        Route::get('/show/{id}', 'itemShow')->name('show');
        Route::post('/update/{id}', 'itemUpdate');
        Route::delete('/delete/{id}', 'itemDelete')->name('delete');
    }); 

Route::prefix('/master/buyer')
    ->name('master.buyer.')
    ->middleware('islogin')
    ->controller(MasterBuyerController::class)
    ->group(function() {
        Route::get('/', 'buyerIndex')->name('index');
    }); 

Route::prefix('/master/kp')
    ->name('master.kp.')
    ->middleware('islogin')
    ->controller(MasterKpController::class)
    ->group(function() {
        Route::get('/', 'kpIndex')->name('index');
    }); 

Route::prefix('/master/supplier')
    ->name('master.supplier.')
    ->middleware('islogin')
    ->controller(MasterSupplierController::class)
    ->group(function() {
        Route::get('/', 'supplierIndex')->name('index');
    }); 

/**
 * Route ke arah menu Purchase
 */

Route::prefix('/purchase')
    ->name('purchase.')
    ->middleware('islogin')
    ->controller(PurchaseController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/show/{id}', 'showDetail')->name('detail');
    }); 

/**
 * Route ke arah menu Warehouse
 */
Route::prefix('/warehouse')
    ->name('warehouse.')
    ->middleware('islogin')
    ->controller(WarehouseController::class)
    ->group(function() {
        Route::get('/receive', 'receiveIndex')->name('receive.index');
        Route::get('/request', 'requestIndex')->name('request.index');
    }); 

/**
 * Route ke arah menu Graphics
 */

Route::prefix('/graphics')
    ->name('graphic.')
    ->middleware('islogin')
    ->controller(GraphicController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    }); 

/**
 * Route ke arah menu Exports
 */

Route::prefix('/exports')
    ->name('export.')
    ->middleware('islogin')
    ->controller(ExportController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/export/excel', 'exportToExcel')->name('excel');
    }); 

/**
 * Route ke arah menu Settings
 * Mulai dari sini semua menu hanya bisa diakses oleh Admin
 */

Route::prefix('/settings')
    ->name('setting.')
    ->middleware('islogin')
    ->controller(SettingController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    }); 
 
/**
 * Route ke arah menu Users
 */
Route::prefix('/users')
    ->name('user.')
    ->middleware('islogin')
    ->controller(UserController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');        
        Route::get('/show/{id}', 'edit');
        Route::post('/create', 'create');
        Route::post('/update/{id}', 'update');
        Route::get('/name/{id}', 'getName');
        Route::delete('/delete/{id}', 'delete');
    });

/**
 * Route ke arah menu Roles
 */

Route::prefix('/roles')
    ->name('role.')
    ->middleware('islogin')
    ->controller(RoleController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'create')->name('create');
        Route::get('/show/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::get('/name/{id}', 'getRole');
        Route::delete('/delete/{id}', 'delete');
    });

/**
 * Route ke arah menu Certificates
 */

Route::prefix('/certificates')
    ->name('certificate.')
    ->middleware('islogin')
    ->controller(CertificateController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/generate/{id}', 'generateCertificate')->name('generate');
        Route::get('/download/{id}', 'downloadCertificate')->name('download');
    });