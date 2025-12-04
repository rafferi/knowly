<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PlacementTestController;
use App\Http\Controllers\CourseRecommendationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LearningPlanController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/weekly-progress', [DashboardController::class, 'getWeeklyProgress'])->name('dashboard.weekly-progress');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
});


Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');


Route::get('/about', function () {
    return view('about');
})->name('about');



Route::middleware('auth')->group(function () {
    Route::get('/placement-test', [PlacementTestController::class, 'show'])->name('placement-test');
    Route::post('/placement-test', [PlacementTestController::class, 'store'])->name('placement-test.store');
    Route::get('/placement-test/results/{test}', [PlacementTestController::class, 'results'])->name('placement-test.results');


    // Учебный план
    Route::get('/learning/course/{course}/plan', [LearningPlanController::class, 'show'])->name('learning.plan');
    Route::get('/learning/course/{course}/week/{week}', [LearningPlanController::class, 'showWeek'])->name('learning.week');
    Route::get('/learning/course/{course}/lesson/{lesson}', [LearningPlanController::class, 'showLesson'])->name('learning.lesson');
    Route::post('/learning/course/{course}/start', [LearningPlanController::class, 'startCourse'])->name('learning.start');
    Route::post('/learning/lesson/{lesson}/complete', [LearningPlanController::class, 'completeLesson'])->name('learning.complete-lesson');
});



Route::get('/course-recommendation-test', [CourseRecommendationController::class, 'showTest'])->name('course-recommendation.test');
Route::post('/course-recommendation-test', [CourseRecommendationController::class, 'processTest'])->name('course-recommendation.store');
Route::get('/course-recommendation-result', [CourseRecommendationController::class, 'showResult'])->name('course-recommendation.result');


Route::get('/free-lesson', function () {
    return view('free-lesson');
});
