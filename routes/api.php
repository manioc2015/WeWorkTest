<?php

use Illuminate\Http\Request;

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

//User Info API Endpoint
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Notes API Endpoints
Route::group(['middleware' => ['auth:api']], function () {
//Create a Note
    Route::post('/note', 'NotesController@createNote');
//Update a Note
    Route::put('/note/{id}', 'NotesController@updateNote');
//Delete a Note
    Route::delete('/note/{id}', 'NotesController@deleteNote');
//Fetch All Notes
    Route::get('/notes', 'NotesController@getNotes');
    Route::get('/notes/{page}', 'NotesController@getNotes');
    Route::get('/notes/{page}/{limit}', 'NotesController@getNotes');
});
