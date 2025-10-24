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
    $user=$request->user();
    $role= $user->role->role;
    return response()->json([
        'user' => $user , 
        'role' => $role
    ],200);
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
Route::get('/roles', [RoleController::class, 'index'])->middleware('auth:sanctum');
Route::get('/roles-users', [RoleController::class, 'getUsers'])->middleware('auth:sanctum');
Route::get('/roles-admins', [RoleController::class, 'getAdmins'])->middleware('auth:sanctum');
Route::get('/roles-emps', [RoleController::class, 'getEmps'])->middleware('auth:sanctum');
Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware('auth:sanctum');
Route::post('/roles', [RoleController::class, 'store'])->middleware('auth:sanctum');
Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('auth:sanctum');
// Jobs routes :
Route::get('/jobs', [JobsController::class, 'index'])->middleware('auth:sanctum');
Route::get('/jobs/{id}', [JobsController::class, 'show'])->middleware('auth:sanctum');
Route::post('/jobs', [JobsController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/jobs/{id}', [JobsController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/jobs/{id}', [JobsController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/jobs-search', [JobsController::class, 'search'])->middleware('auth:sanctum');
// Candidatures routes :
Route::post('/candidatures', [CandidaturesController::class, 'store'])->middleware('auth:sanctum');
Route::get('/candidatures/{id}', [CandidaturesController::class, 'show'])->middleware('auth:sanctum');
Route::get('/candidatures/user/{user_id}', [CandidaturesController::class, 'getByUser'])->middleware('auth:sanctum');
Route::get('/candidatures/job/{job_id}', [CandidaturesController::class, 'getByJob'])->middleware('auth:sanctum');
Route::put('/candidatures/{id}', [CandidaturesController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/candidatures/{id}', [CandidaturesController::class, 'destroy'])->middleware('auth:sanctum');
