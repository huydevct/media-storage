<?php


use Huy\MediaStorage\controllers\MediaStorageController;
use Illuminate\Support\Facades\Route;

Route::prefix('storage')->group(function (){
    Route::post('images',[MediaStorageController::class,'uploadImage'])->name('storage.images');
    Route::post('videos',[MediaStorageController::class,'uploadVideo'])->name('storage.videos');
});