<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\generalController;

Route::controller(AuthController::class)->group(function () {

    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


Route::group([

    "middleware" => "authAll"

], function () {

    // Profile
    Route::get("profile/{AuthType}", [ProfileController::class, "show"]);
    Route::post("profile/{AuthType}", [ProfileController::class, "update"]);
    Route::post("photo/{AuthType}", [ProfileController::class, "updatePhoto"]);

});

Route::get('artisan/{code}', function($code) {

    Artisan::call("$code");
    
});

Route::get("factory", [generalController::class, 'factory']);
