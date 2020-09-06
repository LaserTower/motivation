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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('improve_program', 'ImproveProgram');
Route::resource('bot_prototype', 'BotPrototype');
Route::post('improve_program/attach', 'ImproveProgram@attach');
Route::get('users_of_providers/index', 'UserController@index');
Route::get('part/index', 'BotPrototype@parts');
