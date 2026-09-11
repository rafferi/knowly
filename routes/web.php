<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\UniversalTestController;
use App\Http\Controllers\CourseRecommendationController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PlacementTestController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ReportsController;

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

// Placement Test Routes (public)
Route::get('/placement-test', [PlacementTestController::class, 'show'])->name('placement-test.show');
Route::post('/placement-test', [PlacementTestController::class, 'store'])->name('placement-test.store');
Route::get('/placement-test/results/{test}', [PlacementTestController::class, 'results'])->name('placement-test.results');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Fallback для неправильных ссылок
Route::get('/lectures/course/lesson/{lessonId}', function ($lessonId) {
    $lesson = \App\Models\Lesson::with('course')->findOrFail($lessonId);
    return redirect()->route('lectures.show', [
        'courseSlug' => $lesson->course->slug,
        'lessonId' => $lessonId
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Certificate routes
    Route::get('/certificate/{courseSlug}', [CertificateController::class, 'show'])->name('certificate.show');
    Route::get('/certificate/{certificateNumber}/download', [CertificateController::class, 'download'])->name('certificate.download');
    Route::get('/certificate/{certificateNumber}/print', [CertificateController::class, 'print'])->name('certificate.print');
    Route::get('/certificate/{certificateNumber}/pdf', [CertificateController::class, 'generatePdf'])->name('certificate.pdf');
    Route::get('/certificate/verify/{verificationCode}', [CertificateController::class, 'verify'])->name('certificate.verify');
    Route::get('/certificate-check/{verificationCode}', [CertificateController::class, 'check'])->name('certificate.check');

    Route::prefix('enrollment')->name('enrollment.')->group(function () {
        Route::post('/course/{courseSlug}', [EnrollmentController::class, 'enroll'])->name('enroll');
        Route::get('/course/{courseSlug}/checkout', [EnrollmentController::class, 'checkout'])->name('checkout');
        Route::post('/course/{courseSlug}/process-payment', [EnrollmentController::class, 'processPayment'])->name('process-payment');
        Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments'])->name('my-enrollments');
        Route::delete('/{id}/cancel', [EnrollmentController::class, 'cancelEnrollment'])->name('cancel');
        Route::post('/course/{courseSlug}/free', [EnrollmentController::class, 'freeEnrollment'])->name('free-enroll');

        Route::get('/receipt/{receiptId}', [EnrollmentController::class, 'showReceipt'])->name('receipt');
        Route::get('/receipt/{receiptId}/print', [EnrollmentController::class, 'printReceipt'])->name('receipt.print');
    });

    // Основной путь обучения: лекции + тесты
    Route::prefix('lectures')->name('lectures.')->group(function () {
        Route::get('/course/{courseSlug}', [LectureController::class, 'index'])->name('index');
        Route::get('/course/{courseSlug}/next', [LectureController::class, 'next'])->name('next');
        Route::get('/course/{courseSlug}/lesson/{lessonId}', [LectureController::class, 'show'])->name('show');
        Route::post('/course/{courseSlug}/lesson/{lessonId}/complete', [LectureController::class, 'complete'])->name('complete');
        Route::get('/course/{courseSlug}/roadmap', [LectureController::class, 'roadmap'])->name('roadmap');
    });

    // Universal Tests (all tests in one place)
    Route::get('/universal-tests', [UniversalTestController::class, 'index'])->name('universal-tests.index');
    Route::get('/universal-tests/{testId}', [UniversalTestController::class, 'show'])->name('universal-tests.show');
    Route::get('/universal-tests/{testId}/start', [UniversalTestController::class, 'start'])->name('universal-tests.start');
    Route::post('/universal-tests/{testId}/submit', [UniversalTestController::class, 'submit'])->name('universal-tests.submit');
    Route::get('/universal-tests/results/{resultId}', [UniversalTestController::class, 'results'])->name('universal-tests.results');

    // Universal placement test (redirects to main placement test)
    Route::get('/placement-test-universal', [UniversalTestController::class, 'placement'])->name('placement-test-universal');

    // Course-specific Tests (within learning context)
   // Route::get('/courses/{courseSlug}/tests', [TestController::class, 'index'])->name('tests.index');
    Route::get('/courses/{courseSlug}/tests', function ($courseSlug) {
        return redirect()->route('lectures.index', $courseSlug);
    })->name('tests.index');
    Route::get('/tests/{test}', [TestController::class, 'show'])->name('tests.show');
    Route::get('/tests/{test}/start', [TestController::class, 'start'])->name('tests.start');
    Route::post('/tests/{test}/submit', [TestController::class, 'submit'])->name('tests.submit');
    Route::get('/tests/results/{testResult}', [TestController::class, 'results'])->name('tests.results');

    // Оставлен для обратной совместимости (если где-то ещё вызывается)
    Route::post('/lesson/{lesson}/complete', [LectureController::class, 'completeFromOldRoute'])->name('complete-lesson');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Courses
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses');
    Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

    // Enrollments
    Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])->name('enrollments');
    Route::get('/enrollments/create', [AdminEnrollmentController::class, 'create'])->name('enrollments.create');
    Route::post('/enrollments', [AdminEnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/enrollments/{enrollment}/edit', [AdminEnrollmentController::class, 'edit'])->name('enrollments.edit');
    Route::put('/enrollments/{enrollment}', [AdminEnrollmentController::class, 'update'])->name('enrollments.update');
    Route::delete('/enrollments/{enrollment}', [AdminEnrollmentController::class, 'destroy'])->name('enrollments.destroy');

    // Lessons
    Route::get('/lessons', [AdminLessonController::class, 'index'])->name('lessons');
    Route::get('/lessons/create', [AdminLessonController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [AdminLessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('lessons.destroy');

    // Tests
    Route::get('/tests', [AdminTestController::class, 'index'])->name('tests');
    Route::get('/tests/create', [AdminTestController::class, 'create'])->name('tests.create');
    Route::post('/tests', [AdminTestController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [AdminTestController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [AdminTestController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [AdminTestController::class, 'destroy'])->name('tests.destroy');

    // Экспорт данных
    Route::get('/export/users', [ExportController::class, 'exportUsers'])->name('export.users');
    Route::get('/export/courses', [ExportController::class, 'exportCourses'])->name('export.courses');
    Route::get('/export/enrollments', [ExportController::class, 'exportEnrollments'])->name('export.enrollments');
    Route::get('/export/revenue', [ExportController::class, 'exportRevenue'])->name('export.revenue');

    // Отчеты
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/users', [ReportsController::class, 'usersReport'])->name('reports.users');
    Route::get('/reports/enrollments', [ReportsController::class, 'enrollmentsReport'])->name('reports.enrollments');
    Route::get('/reports/revenue', [ReportsController::class, 'revenueReport'])->name('reports.revenue');
    Route::get('/reports/learning-progress', [ReportsController::class, 'learningProgressReport'])->name('reports.learning-progress');
});
