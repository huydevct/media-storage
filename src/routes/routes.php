<?php

use Illuminate\Routing\Route;
use Huy\MediaStorage\controllers\MediaStorageController;

Route::prefix('storage')->group(function (){
    Route::post('images',[MediaStorageController::class,'uploadImage'])->name('storage.images');
    Route::post('videos',[MediaStorageController::class,'uploadVideo'])->name('storage.videos');
});