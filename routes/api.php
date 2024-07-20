<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

// CrudController routes
Route::post('/MassInsert', [CrudController::class, 'MassInsert'])->name('mass-insert');
Route::post('/MassUpdate', [CrudController::class, 'MassUpdate'])->name('mass-update');
Route::delete('/MassDelete/{TableName}/{id}', [CrudController::class, 'MassDelete'])->name('mass-delete');

// AppController routes
Route::post('/FetchUpdateIndicators', [AppController::class, 'FetchUpdateIndicators'])->name('FetchUpdateIndicators');
Route::post('/FetchIndicators', [AppController::class, 'FetchIndicators'])->name('FetchIndicators');
Route::post('/FetchSpecificDataOneRecord', [AppController::class, 'FetchSpecificDataOneRecord'])->name('FetchSpecificDataOneRecord');
Route::post('/FetchSpecificData', [AppController::class, 'FetchSpecificData'])->name('FetchSpecificData');
Route::post('/RemovedColumns', [AppController::class, 'RemovedColumns'])->name('removed-columns');
Route::post('/MassFetch', [AppController::class, 'MassFetch'])->name('mass-fetch');
Route::post('/FetchUpdateData', [AppController::class, 'FetchUpdateData'])->name('fetch-update-data');