<?php

use App\Http\Controllers\Api\IntegerTargetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\UserEmployeesController;
use App\Http\Controllers\Api\PositionEmployeesController;

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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/arrayTarget', [IntegerTargetController::class, 'generateIndex'])->name('array_target');
Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
        return $request->user();
    })
    ->name('api.user');

Route::name('api.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('positions', PositionController::class);

        // Position Employees
        Route::get('/positions/{position}/employees', [PositionEmployeesController::class, 'index',])
            ->name('positions.employees.index');
        Route::post('/positions/{position}/employees', [PositionEmployeesController::class, 'store',])
            ->name('positions.employees.store');

        Route::apiResource('employees', EmployeeController::class);

        Route::apiResource('users', UserController::class);

        // User Employees
        Route::get('/users/{user}/employees', [
            UserEmployeesController::class,
            'index',
        ])->name('users.employees.index');
        Route::post('/users/{user}/employees', [
            UserEmployeesController::class,
            'store',
        ])->name('users.employees.store');
    });
