<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompteurController;
use App\Http\Controllers\API\EquipementController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\RechargementController;
use App\Http\Controllers\API\ReleveController;
use App\Http\Controllers\API\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/send-reset-link', [ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'userProfile']);
    Route::get('/auth/refresh', [AuthController::class, 'refresh']);

    // route pour ajouter un compteurs

    Route::post('/compteurs/new', [CompteurController::class, 'store']);
    Route::get('/compteurs/user/{id}', [CompteurController::class, 'index']);
    Route::get('/compteurs/update/{id}', [CompteurController::class, 'update']);

    // route les rechargements

    Route::post('/rechargements/new', [RechargementController::class, 'store']);
    Route::get('/rechargements/user/{id}', [RechargementController::class, 'index']);

    // route pour les equipements

    Route::post('/equipements/new', [EquipementController::class, 'store']);
    Route::get('/equipements/show/{id}', [EquipementController::class, 'show']);
    Route::get('/equipements/user/{userId}/compteur/{compteurId}', [EquipementController::class, 'index']);
    Route::get('/equipements/{id}/edit', [EquipementController::class, 'edit']);
    Route::get('/equipements/update/{id}', [EquipementController::class, 'edit']);
    Route::delete('/equipements/delete/{id}', [EquipementController::class, 'destroy']);

    // route pour les releves

    Route::post('/releves/new', [ReleveController::class, 'store']);
    Route::get('/releves/compteurs/{id}', [ReleveController::class, 'index']);

});
