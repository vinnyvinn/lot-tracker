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
Route::get('create-batch/{id}','BatchesController@createBatch');
Route::post('store-batch','BatchesController@storeBatch');
Route::get('sample','BatchesController@Sample');
Route::get('sample-op','BatchesController@SampleOp');
Route::post('store-bal','BatchesController@storeBalance');
Route::get('fetch-po/{id}','BatchesController@fetchDetails');
Route::post('update-po/{id}','BatchesController@updatePoDetails');
Route::resource('approved-pos','ApprovedPurchaseOrdersController');
Route::post('new-reason','ApprovedPurchaseOrdersController@newReason');
Route::post('update-batch','ApprovedPurchaseOrdersController@update');
Route::get('process-pos/{id}','ApprovedPurchaseOrdersController@processPos');
Route::get('check-po-status/{id}','ApprovedPurchaseOrdersController@poStatus');
Route::resource('so','SaleOrdersController');
Route::get('fetch-so/{id}','SaleOrdersController@fetchSo');
Route::post('update-so/{id}','SaleOrdersController@updateSo');
Route::get('approved-so/{id}','SaleOrdersController@approvedSo');
Route::get('process-so/{id}','SaleOrdersController@processSo');
Route::post('add-reason','SaleOrdersController@addReason');
Route::post('update-lines','SaleOrdersController@update');
Route::get('lot-qty/{id}','SaleOrdersController@lotQty');
Route::post('update-inv-lines','SaleOrdersController@updateInvLines');
Route::get('show-batches/{id}','SaleOrdersController@create');
Route::resource('reports','ReportsController');
Route::get('/so/excel/{from}/{to}','ReportsController@soExcelExport');
Route::get('/po/excel/{from}/{to}','ReportsController@poExcelExport');
Route::resource('settings','SettingsController');

