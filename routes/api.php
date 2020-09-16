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

Route::resource('motivation_program', 'ImproveProgram');
Route::resource('conversation_scenario', 'ConversationScenario');
Route::resource('players_of_providers', 'PlayerController');
Route::get('players_of_providers/{id}/motivation_program', 'PlayerController@motivationSchedule');
Route::post('players_of_providers/attach_program', 'ImproveProgram@attach');

Route::get('part', 'BotPrototype@parts');
