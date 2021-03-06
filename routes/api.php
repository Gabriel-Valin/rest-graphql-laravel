<?php

use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\Products;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'products'], function (){
    Route::get('/', [ ProductController::class, 'getAllProducts' ]);
    Route::get('/{productId}', [ ProductController::class, 'getProductByIdentify' ]);
    Route::post('/', [ ProductController::class, 'createNewProduct' ]);
    Route::put('/{productId}', [ ProductController::class, 'updateProduct' ]);
    Route::delete('/{productId}', [ ProductController::class, 'deleteProduct' ]);
});

Route::fallback(function() {
    return response()->json(['message' => 'No Route Found'], Response::HTTP_BAD_REQUEST);
});