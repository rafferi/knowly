<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    ProfileController,
    CourseController,
    LearningPlanController,
    HomeworkController,
    TestController,
    PlacementTestController,
    CourseRecommendationController
};


Route::middleware(['auth'])->group(function () {
    Route::get('/homeworks', [HomeworkController::class, 'index'])->name('homeworks.index');
    Route::get('/homeworks/create/{lesson}', [HomeworkController::class, 'create'])->name('homeworks.create');
    Route::post('/homeworks/{lesson}', [HomeworkController::class, 'store'])->name('homeworks.store');
    Route::get('/homeworks/{homework}', [HomeworkController::class, 'show'])->name('homeworks.show');
    Route::get('/homeworks/{homework}/edit', [HomeworkController::class, 'edit'])->name('homeworks.edit');
    Route::put('/homeworks/{homework}', [HomeworkController::class, 'update'])->name('homeworks.update');
    Route::delete('/homeworks/{homework}', [HomeworkController::class, 'destroy'])->name('homeworks.destroy');


    Route::post('/homeworks/{homework}/grade', [HomeworkController::class, 'grade'])->name('homeworks.grade');
    Route::post('/homeworks/{homework}/return', [HomeworkController::class, 'returnForRevision'])->name('homeworks.return');
});


Route::prefix('learning')->name('learning.')->group(function () {
    Route::get('/course/{course}/plan', [LearningPlanController::class, 'show'])->name('plan');
    Route::get('/course/{course}/week/{week}', [LearningPlanController::class, 'showWeek'])->name('week');
    Route::get('/course/{course}/lesson/{lesson}', [LearningPlanController::class, 'showLesson'])->name('lesson');
    Route::post('/course/{course}/start', [LearningPlanController::class, 'startCourse'])->name('start');
    Route::post('/lesson/{lesson}/complete', [LearningPlanController::class, 'completeLesson'])->name('complete-lesson');
});


Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});


Route::get('/placement-test', [PlacementTestController::class, 'show'])->name('placement-test');
Route::post('/placement-test', [PlacementTestController::class, 'store']);
Route::get('/placement-test/results/{test}', [PlacementTestController::class, 'results'])->name('placement-test.results');


Route::get('/course-recommendation', [CourseRecommendationController::class, 'showTest'])->name('course-recommendation.test');
Route::post('/course-recommendation', [CourseRecommendationController::class, 'processTest']);
Route::get('/course-recommendation/result', [CourseRecommendationController::class, 'showResult'])->name('course-recommendation.result');
