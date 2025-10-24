<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\CandidaturesController;
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
// Users route :
Route::get('/users', [AuthController::class, 'index']);
Route::get('/users/{id}', [AuthController::class, 'show']);
Route::put('/users/{id}', [AuthController::class, 'update']);
Route::delete('/users/{id}', [AuthController::class, 'destroy']);
// Role routes :
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles-users', [RoleController::class, 'getUsers']);
Route::get('/roles-emps', [RoleController::class, 'getEmps']);
Route::get('/roles-admins', [RoleController::class, 'getAdmins']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
// Jobs routes :
Route::get('/jobs', [JobsController::class, 'index']);
Route::get('/jobs/{id}', [JobsController::class, 'show']);
Route::post('/jobs', [JobsController::class, 'store']);
Route::put('/jobs/{id}', [JobsController::class, 'update']);
Route::delete('/jobs/{id}', [JobsController::class, 'destroy']);
Route::get('/jobs-search', [JobsController::class, 'search']);
// Candidatures routes :
Route::post('/candidatures', [CandidaturesController::class, 'store']);
Route::get('/candidatures/{id}', [CandidaturesController::class, 'show']);
Route::get('/candidatures/user/{user_id}', [CandidaturesController::class, 'getByUser']);
Route::get('/candidatures/job/{job_id}', [CandidaturesController::class, 'getByJob']);
Route::put('/candidatures/{id}', [CandidaturesController::class, 'update']);
Route::delete('/candidatures/{id}', [CandidaturesController::class, 'destroy']);
