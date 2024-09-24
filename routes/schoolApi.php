<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\school\classController;
use App\Http\Controllers\school\RoomStudentsController;
use App\Http\Controllers\school\subjectController;
use App\Http\Controllers\school\RoomController;
use App\Http\Controllers\school\TeacherController;
use App\Http\Controllers\school\PeriodController;
use App\Http\Controllers\school\LessonsController;
use App\Http\Controllers\school\SchoolAbsence;
use App\Http\Controllers\school\SchoolResultsController;

// Main Controller
use App\Http\Controllers\school\SchoolController;

Route::group([

    "middleware" => "schoolAuth",
    "prefix" => "school"

], function () {

    // Routes Resource
    Route::apiResource('subject', subjectController::class);
    Route::apiResource('class', classController::class);
    Route::apiResource('room', RoomController::class);
    Route::apiResource('students', RoomStudentsController::class);
    Route::apiResource('teacher', TeacherController::class);
    Route::apiResource('period', PeriodController::class);
    Route::apiResource('lesson', LessonsController::class);
    Route::apiResource('absence', SchoolAbsence::class);
    Route::apiResource('results', SchoolResultsController::class);

    // Routes
    Route::get('const_classes', [SchoolController::class, "const_classes"]);
    Route::get('const_subjects', [SchoolController::class, "const_subjects"]);
    Route::get('days', [SchoolController::class, "days"]);

});
