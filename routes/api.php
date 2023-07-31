<?php

use App\Http\Controllers\product\ProductController;
use App\Models\product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
       /**
        * Route Store Product
        */
Route::post('/create',[ProductController::class,'storeProduct']);

/**
 * Route Filter Prduct http://localhost:8000/api/filter?min_price=60;
 */
Route::get('/filter',[ProductController::class,'filteringByPrice']);

/**
 * Route Sort  http://localhost:8000/api/sort?sort=name&order=asc
 */

Route::get('/sort',[ProductController::class,'sortingProducts']);

/**
 * Route Update
 */

Route::post('/update/{id}',[ProductController::class,'updateProduct']);

    /**
   * Route Get All Product
   */
Route::get('products',[ProductController::class,'index']);

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  //  return $request->user();
//});

/**
 * Route Search By Name Or Search By Description
 */
Route::get('/search',[ProductController::class,'searchProduct']);
/**
 * this route Delete Product
 */
Route::get('delete/{id}',[ProductController::class,'destroy']);