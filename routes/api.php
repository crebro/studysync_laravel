<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotesController;
use App\Http\Controllers\Api\SpacesController;

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

Route::post(
    'register',
    [UserController::class, 'register']
);
Route::post(
    'login',
    [UserController::class, 'login']
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource(
    'spaces',
    SpacesController::class,
    ['except' => ['create', 'edit',]]
)->middleware('auth:sanctum');

Route::resource(
    'notes',
    NotesController::class,
    ['except' => ['index', 'create', 'edit', 'show']]
)->middleware('auth:sanctum');

Route::post('/invitation/join', [SpacesController::class, 'invitationJoin'])->middleware('auth:sanctum');
