<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/incidents', [IncidentController::class, 'index']);
    Route::get('/incidents/stats', [IncidentController::class, 'stats']);
    Route::get('/incidents/{id}', [IncidentController::class, 'show']);

    Route::post('/incident/create', [IncidentController::class, 'store']);
    Route::put('/incident/{incident}/update', [IncidentController::class, 'update']); 
    Route::delete('/incident/{incident}', [IncidentController::class, 'destroy']);
    Route::post('/incidents/{incident}/assign', [IncidentController::class, 'assign']);

    Route::get('/incident/{incident}/activity-logs', [ActivityLogController::class, 'activityLogs']);

    //users routes 
    Route::get('/users/operators', [UserController::class, 'operators']);

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
