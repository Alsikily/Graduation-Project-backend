<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Controllers
use App\Http\Controllers\parent\ParentChildrenController;
use App\Http\Controllers\parent\ParentExamsController;
use App\Http\Controllers\parent\ParentResultController;
use App\Http\Controllers\parent\ParentAbsenceController;

Route::group([

    "middleware" => "parentAuth",
    "prefix" => "parent"

], function () {

    Route::apiResource('children', ParentChildrenController::class);
    Route::apiResource('exams', ParentExamsController::class);
    Route::apiResource('absence', ParentAbsenceController::class);

    // Routes
    Route::get("results", [ParentResultController::class, "index"]);
    Route::get("sd", [ParentResultController::class, "index2"]);

});
