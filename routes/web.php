<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataTables\DatatablesController;
use App\Http\Controllers\Master\MasterBuyerController;
use App\Http\Controllers\Master\MasterItemController;
use App\Http\Controllers\Master\MasterKpController;
use App\Http\Controllers\Master\MasterSupplierController;
use App\Http\Controllers\PurchaseController;
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

Route::get('/', function () {
    return view('main.index');
})->middleware('islogin');

Route::prefix('/auth')
    ->name('auth.')
    ->controller(AuthController::class)
    ->group(function() {
        Route::get('/login', 'loginIndex')->name('login.index');
        Route::post('/login', 'loginProcess');
        Route::get('/register', 'registerIndex');        
        Route::post('/register', 'registerProcess');
        Route::post('/logout', 'logout')->middleware('islogin');
    });

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
    });

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

Route::prefix('/purchase')
    ->name('purchase.')
    ->middleware('islogin')
    ->controller(PurchaseController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    }); 

Route::prefix('/warehouse')
    ->name('warehouse.')
    ->middleware('islogin')
    ->controller(WarehouseController::class)
    ->group(function() {
        Route::get('/receive', 'receiveIndex')->name('receive.index');
        Route::get('/request', 'requestIndex')->name('request.index');
    }); 

Route::prefix('/settings')
    ->name('setting.')
    ->middleware('islogin')
    ->controller(SettingController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    }); 
    
Route::prefix('/users')
    ->name('user.')
    ->middleware('islogin')
    ->controller(UserController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    });
