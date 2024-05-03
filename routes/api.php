<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// c2b
Route::get('/recruitments/get', 'RecruitmentController@getPosts');
Route::any('/c2b/confirmation', 'MpesaController@getC2BResults');
Route::any('/c2b/validation', 'MpesaController@getC2BValidation');
//b2c
Route::any('/b2c/queue', 'B2CController@b2cQueue');
Route::any('/b2c/result', 'B2CController@b2cResult');
//simulate b2c
Route::any('/b2c/initiate', 'MpesaController@b2cInit');

Route::any('/c2b/stkpush-result', 'MpesaController@stkpush_results');

//app routing
Route::prefix('/v1/')->group(function () {
    Route::post("login", "API\AuthController@login");
    Route::get("cooperatives", "API\AuthController@get_cooperatives");
    Route::get("banks/{cooperative_id}", "API\AuthController@get_banks");
    Route::get("bank-branches/{bank_id}", "API\AuthController@get_bank_branches");
    Route::get("products/{cooperative_id}", "API\AuthController@get_products");
    Route::get("countries", "API\AuthController@get_countries");
    Route::get('farmers',"API\FarmerController@farmers");
    Route::get('routes',"API\FarmerController@routes");
    Route::get('list_collections',"API\FarmerController@list_collections");
    Route::get('getProducts','API\ProductController@getProducts');

    Route::post("farmer-register", "API\AuthController@farmer_register");
    

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', "API\AuthController@logout");
        Route::get('get-farmers', "API\AgentController@farmers");
      
        Route::middleware('role:cooperative admin')->group(function () {
            Route::get('customers', 'API\CustomerController@get_customers');
            Route::get('pending_payments', 'API\AccountingController@pending_payments');
            Route::get('pending_sales', 'API\SalesController@pending_sales');
            Route::get('total_sales', 'API\SalesController@total_sales');
        });
    });
});
