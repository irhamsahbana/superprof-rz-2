<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

Route::get('/', [CrudController::class, 'index']);
Route::get('crud', [CrudController::class, 'index']);
Route::get('list', [CrudController::class, 'list']);
Route::post('store-company', [CrudController::class, 'store']);
Route::post('edit-company', [CrudController::class, 'edit']);
Route::post('update-company', [CrudController::class, 'update']);
Route::post('delete-company', [CrudController::class, 'destroy']);

// Route::get('crud', function (Request $request) {
//     $response = Http::get('http://127.0.0.1:8000/api/crud');
//     $list = json_decode($response->getBody(), true);
//     dd($list['data']);
// });
