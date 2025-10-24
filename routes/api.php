<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Auth route :
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'legout'])->middleware('auth:sanctum');
// Role routes :
Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index']);
Route::get('/roles/users', [App\Http\Controllers\RoleController::class, 'getUsers']);
Route::get('/roles/emps', [App\Http\Controllers\RoleController::class, 'getEmps']);
Route::get('/roles/admins', [App\Http\Controllers\RoleController::class, 'getAdmins']);
Route::get('/roles/{id}', [App\Http\Controllers\RoleController::class, 'show']);
Route::put('/roles/{id}', [App\Http\Controllers\RoleController::class, 'update']);
Route::delete('/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy']);
