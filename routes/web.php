<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PlacementTestController;
use App\Http\Controllers\CourseRecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/signup', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
});


Route::get('/courses', function () {
    return view('courses');
})->name('courses');


Route::get('/about', function () {
    return view('about');
})->name('about');


// Placement test routes
Route::middleware('auth')->group(function () {
    Route::get('/placement-test', [PlacementTestController::class, 'show'])->name('placement-test');
    Route::post('/placement-test', [PlacementTestController::class, 'store'])->name('placement-test.store');
    Route::get('/placement-test/results/{test}', [PlacementTestController::class, 'results'])->name('placement-test.results');
});


// Course recommendation test routes
Route::get('/course-recommendation-test', [CourseRecommendationController::class, 'showTest'])->name('course-recommendation.test');
Route::post('/course-recommendation-test', [CourseRecommendationController::class, 'processTest'])->name('course-recommendation.store');
Route::get('/course-recommendation-result', [CourseRecommendationController::class, 'showResult'])->name('course-recommendation.result');


Route::get('/free-lesson', function () {
    return view('free-lesson');
});
