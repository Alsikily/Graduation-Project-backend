<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\teacher\ExamController;
use App\Http\Controllers\teacher\QuestionsController;
use App\Http\Controllers\teacher\AnswersController;
use App\Http\Controllers\teacher\TeacherInfoController;
use App\Http\Controllers\teacher\CoursesController;
use App\Http\Controllers\teacher\VideoController;
use App\Http\Controllers\teacher\BooksController;
use App\Http\Controllers\teacher\TeacherAbsencesController;

Route::group([

    "middleware" => "teacherAuth",
    "prefix" => "teacher"

], function () {

    // Routes Resources
    Route::apiResource('exams', ExamController::class);
    Route::apiResource('questions', QuestionsController::class);
    Route::apiResource('courses', CoursesController::class);
    Route::apiResource('videos', VideoController::class);
    Route::apiResource('books', BooksController::class);
    Route::apiResource('absence', TeacherAbsencesController::class);

    // Answers Routes
    Route::apiResource('answers', AnswersController::class);
    Route::put('answers', [AnswersController::class, "updateTrue"]);
    Route::get("rooms", [TeacherInfoController::class, "rooms"]);

});
