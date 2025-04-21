<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\EmployeeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('/division', DivisionController::class);
Route::apiResource('/job', App\Http\Controllers\Api\JobController::class);
Route::apiResource('/employee', App\Http\Controllers\Api\EmployeeController::class);

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

// Employee routes with joins
Route::prefix('employees')->group(function () {
    Route::get('/with-details', [EmployeeController::class, 'getEmployeeWithDetails']);
    Route::get('/by-division/{divisiId}', [EmployeeController::class, 'getEmployeesByDivision']);
    Route::get('/by-salary-range', [EmployeeController::class, 'getEmployeesBySalaryRange']);
    Route::get('/division-summary', [EmployeeController::class, 'getDivisionSummary']);
});

Route::get('/employees-with-division', [App\Http\Controllers\Api\EmployeeController::class, 'getEmployeesWithDivision']);

