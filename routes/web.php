<?php

use App\Http\Controllers\DataTables\DatatablesController;
use App\Http\Controllers\Master\MasterBuyerController;
use App\Http\Controllers\Master\MasterItemController;
use App\Http\Controllers\Master\MasterKpController;
use App\Http\Controllers\Master\MasterSupplierController;
use App\Http\Controllers\PurchaseController;
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
});

Route::prefix('/datatables')
    ->name('datatable.')
    ->controller(DatatablesController::class)
    ->group(function() {
        Route::get('/item', 'item')->name('item');
        Route::get('/buyer', 'buyer')->name('buyer');
        Route::get('/kp', 'kp')->name('kp');
        Route::get('/supplier', 'supplier')->name('supplier');
        Route::get('/purchase', 'purchase')->name('purchase');
        Route::get('/warehouse-receive', 'warehouseReceive')->name('warehouse.receive');
        Route::get('/warehouse-request', 'warehouseRequest')->name('warehouse.request');
    });

Route::prefix('/master/item')
    ->name('master.item.')
    ->controller(MasterItemController::class)
    ->group(function() {
        Route::get('/', 'itemIndex')->name('index');
        Route::post('/store', 'itemStore')->name('store');
        Route::get('/show/{item}', 'itemShow')->name('show');
        Route::post('/update/{item}', 'itemUpdate');
        Route::delete('/delete/{item}', 'itemDelete')->name('delete');
    }); 

Route::prefix('/master/buyer')
    ->name('master.buyer.')
    ->controller(MasterBuyerController::class)
    ->group(function() {
        Route::get('/', 'buyerIndex')->name('index');
    }); 

Route::prefix('/master/kp')
    ->name('master.kp.')
    ->controller(MasterKpController::class)
    ->group(function() {
        Route::get('/', 'kpIndex')->name('index');
    }); 

Route::prefix('/master/supplier')
    ->name('master.supplier.')
    ->controller(MasterSupplierController::class)
    ->group(function() {
        Route::get('/', 'supplierIndex')->name('index');
    }); 

Route::prefix('/purchase')
    ->name('purchase.')
    ->controller(PurchaseController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    }); 

Route::prefix('/warehouse')
    ->name('warehouse.')
    ->controller(WarehouseController::class)
    ->group(function() {
        Route::get('/receive', 'receiveIndex')->name('receive.index');
        Route::get('/request', 'requestIndex')->name('request.index');
    }); 
