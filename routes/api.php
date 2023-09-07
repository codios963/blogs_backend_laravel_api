<?php

use App\Http\Controllers\AuthApi\AuthController;
use App\Http\Controllers\PostApi\PostController;
use App\Http\Controllers\UserApi\RoleController;
use App\Http\Controllers\UserApi\RoleUserController;
use App\Http\Controllers\UserApi\UserController;
use App\Http\Middleware\AdminMiddlware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user_profile', [AuthController::class, 'userProfile']);
});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'posts'
],function(){
Route::get('/',[UserController::class, 'index']);//all post's user
Route::get('/show/{id}',[PostController::class, 'show']);
Route::post('/create',[PostController::class, 'store']);
Route::post('/{id}',[PostController::class, 'destroy']);
Route::post('/update/{id}',[PostController::class, 'update']);
});



Route::middleware(['auth:api','chek:admin,editor' ])->prefix('roles')->group(

    function(){
Route::get('/showsoft',[PostController::class, 'showsoft']);
Route::post('/restor/{id}',[PostController::class, 'restor']);
Route::post('/finldelet/{id}',[PostController::class, 'finldelet']);
Route::get('/posts',[PostController::class, 'index']);

Route::get('/',[RoleController::class, 'index']);
Route::get('/show/{id}',[RoleController::class, 'show']);
Route::post('/create',[RoleController::class, 'store']);
Route::post('/{id}',[RoleController::class, 'destroy']);
Route::post('/update/{id}',[RoleController::class, 'update']);




Route::get('/user',[RoleUserController::class, 'index']);
Route::get('/user/show/{id}',[RoleUserController::class, 'show']);
Route::post('/user/create',[RoleUserController::class, 'store']);

Route::post('/user/{id}',[RoleUserController::class, 'delete']);
Route::post('/user/update/{id}',[RoleUserController::class, 'update']);



});
