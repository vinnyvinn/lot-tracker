<?php

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


Auth::routes();

Route::get('/', 'PurchaseOrderController@index');
Route::resource('pos','PurchaseOrderController');
Route::get('sync-po','PurchaseOrderController@syncPO');
Route::resource('batches','BatchesController');
Route::get('sample','BatchesController@Sample');
Route::get('fetch-po/{id}','BatchesController@fetchDetails');
Route::post('update-po/{id}','BatchesController@updatePoDetails');
Route::resource('approved-pos','ApprovedPurchaseOrdersController');
Route::resource('so','SaleOrdersController');
Route::get('fetch-so/{id}','SaleOrdersController@fetchSo');
Route::post('update-so/{id}','SaleOrdersController@updateSo');
Route::get('approved-so/{id}','SaleOrdersController@approvedSo');

