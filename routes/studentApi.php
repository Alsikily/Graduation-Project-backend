<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\student\{
    HomeController,
    CommunityController,
    PostController,
    CommentsController,
    StudentSubjectsController,
    StudentExamsController,
    StudentCourseController,
    StudentVideoController,
    StudentProfileController,
    CartController,
    StudentBooksController,
    StudentResultController,
    StudentAbsenceController,
};

Route::group([

    "middleware" => "studentAuth",
    "prefix" => "student"

], function () {

    Route::apiResource('community', CommunityController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentsController::class);
    Route::apiResource('courses', StudentCourseController::class);
    Route::apiResource('videos', StudentVideoController::class);
    Route::apiResource('cart', CartController::class);
    Route::apiResource('books', StudentBooksController::class);
    Route::apiResource('absence', StudentAbsenceController::class);

    // Routes
    Route::get("home", [HomeController::class, "index"]);
    Route::get("subjects", [StudentSubjectsController::class, "index"]);
    Route::get("exams", [StudentExamsController::class, "index"]);
    Route::get("exams/{id}", [StudentExamsController::class, "show"]);
    Route::post("exams/{id}", [StudentExamsController::class, "store"]);
    Route::get("results", [StudentResultController::class, "index"]);

});