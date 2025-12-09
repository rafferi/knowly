<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LearningPlanController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UniversalTestController;
use App\Http\Controllers\CourseRecommendationController;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/free-lesson', function () {
    return view('free-lesson');
})->name('free-lesson');

Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/course-recommendation', [CourseRecommendationController::class, 'showTest'])->name('course-recommendation.test');
Route::post('/course-recommendation', [CourseRecommendationController::class, 'processTest'])->name('course-recommendation.store');
Route::get('/course-recommendation/result', [CourseRecommendationController::class, 'showResult'])->name('course-recommendation.result');

Route::get('/placement-test', [UniversalTestController::class, 'placement'])->name('placement-test');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::prefix('enrollment')->name('enrollment.')->group(function () {
        Route::post('/course/{course}', [EnrollmentController::class, 'enroll'])->name('enroll');
        Route::get('/course/{course}/checkout', [EnrollmentController::class, 'checkout'])->name('checkout');
        Route::post('/course/{course}/process-payment', [EnrollmentController::class, 'processPayment'])->name('process-payment');
        Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments'])->name('my-enrollments');
        Route::delete('/{id}/cancel', [EnrollmentController::class, 'cancelEnrollment'])->name('cancel');
        Route::post('/course/{course}/free', [EnrollmentController::class, 'freeEnrollment'])->name('free-enroll');

        // Маршрут для чека
        Route::get('/receipt/{receipt}', [EnrollmentController::class, 'showReceipt'])->name('receipt');
        Route::get('/receipt/{receipt}/print', [EnrollmentController::class, 'printReceipt'])->name('receipt.print');
    });

    Route::prefix('learning')->name('learning.')->group(function () {
        Route::get('/course/{course}/plan', [LearningPlanController::class, 'show'])->name('plan');
        Route::get('/course/{course}/week/{week}', [LearningPlanController::class, 'showWeek'])->name('week');
        Route::get('/course/{course}/lesson/{lesson}', [LearningPlanController::class, 'showLesson'])->name('lesson');
        Route::post('/course/{course}/start', [LearningPlanController::class, 'startCourse'])->name('start');
        Route::post('/lesson/{lesson}/complete', [LearningPlanController::class, 'completeLesson'])->name('complete-lesson');
    });

    Route::get('/universal-tests', [UniversalTestController::class, 'index'])->name('universal-tests.index');
    Route::get('/universal-tests/{test}', [UniversalTestController::class, 'show'])->name('universal-tests.show');
    Route::get('/universal-tests/{test}/start', [UniversalTestController::class, 'start'])->name('universal-tests.start');
    Route::post('/universal-tests/{test}/submit', [UniversalTestController::class, 'submit'])->name('universal-tests.submit');
    Route::get('/universal-tests/results/{result}', [UniversalTestController::class, 'results'])->name('universal-tests.results');
    Route::get('/universal-tests/practice/{course}', [UniversalTestController::class, 'practice'])->name('universal-tests.practice');
});
