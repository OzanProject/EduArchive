<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\IntegrationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/students', [IntegrationController::class, 'getStudents']);
    Route::get('/teachers', [IntegrationController::class, 'getTeachers']);
    Route::get('/classrooms', [IntegrationController::class, 'getClassrooms']);
});
