<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\WishListController;
use App\Http\Controllers\Api\CustomerAddressController;




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

Route::group(['prefix' => 'password'], function () {
	Route::post('forgot', [PasswordController::class, 'forgot']);
	Route::post('reset', [PasswordController::class, 'reset']);
});





// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'auth:api'], function () {
	Route::group(['prefix' => 'password'], function () {
		Route::post('change', [PasswordController::class, 'change']);
	});
	Route::group(['prefix' => '/home'], function () {
		Route::get('counters', [HomeController::class, 'counters']);
		Route::get('filter', [HomeController::class, 'filter']);
	});
	Route::group(['prefix' => '/wishlist'], function () {
		Route::post('/store', [WishlistController::class, 'store']);
		Route::get('/show/{id}', [WishlistController::class, 'show']);
		Route::get('/list', [WishlistController::class, 'list']);
		Route::delete('/remove/{id}', [WishlistController::class, 'remove']);
	});
	Route::group(['prefix' => '/review'], function () {
		Route::post('/store', [ReviewController::class, 'store']);
		Route::post('/update/{id}', [ReviewController::class, 'update']);
		Route::delete('/remove/{id}', [ReviewController::class, 'remove']);
	});
	Route::group(['prefix' => '/address'], function () {
		Route::get('/show', [CustomerAddressController::class, 'show']);
		Route::post('/update', [CustomerAddressController::class, 'update']);
		Route::delete('/delete', [CustomerAddressController::class, 'delete']);
	});
	Route::group(['prefix' => '/cart'], function () {
		Route::post('/store', [CartController::class, 'store']);
		Route::get('/list', [CartController::class, 'list']);
		Route::post('/update/{id}', [CartController::class, 'update']);
		Route::delete('/remove/{id}', [CartController::class, 'remove']);
	});
	Route::group(['prefix' => '/order'], function () {
		Route::post('/store', [OrderController::class, 'store']);
		Route::get('/list', [OrderController::class, 'list']);
		Route::post('/update/{id}', [OrderController::class, 'update']);
		Route::delete('/remove/{id}', [OrderController::class, 'remove']);
	});
});
Route::group(['prefix' => 'auth'], function () {
	Route::post('login', [AuthController::class, 'login']);
	Route::post('signup', [AuthController::class, 'signup']);

	Route::group(['middleware' => 'auth:api'], function () {
		Route::post('logout', [AuthController::class, 'logout']);
		Route::delete('delete', [AuthController::class, 'delete']);
		Route::get('user', [AuthController::class, 'user']);
		Route::post('profile-update', [AuthController::class, 'profileUpdate']);
	});
});
Route::group(['prefix' => '/category'], function () {
	Route::get('/list', [CategoryController::class, 'list']);
});


Route::group(['prefix' => '/product'], function () {
	Route::get('/list', [ProductController::class, 'list']);
	Route::get('/show/{id}', [ProductController::class, 'show']);
	Route::get('/popular', [ProductController::class, 'popular']);
	Route::get('/search/{search_query}', [ProductController::class, 'search']);
	Route::get('/related/{id}', [ProductController::class, 'related']);
});


Route::group(['prefix' => '/review'], function () {
	Route::get('/list/{product_id}', [ReviewController::class, 'list']);
	Route::get('/percentage/{product_id}', [ReviewController::class, 'percentage']);
});

Route::group(['prefix' => '/contact'], function () {
	Route::post('/store', [ContactController::class, 'store']);
});

Route::group(['prefix' => '/home'], function () {

	Route::get('filter', [HomeController::class, 'filter']);
});
