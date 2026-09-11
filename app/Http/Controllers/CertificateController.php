<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\UserCourse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function show($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->firstOrFail();

        $userCourse = UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$userCourse || $userCourse->progress < 100) {
            return redirect()->back()->with('error', 'Курс не завершен на 100%');
        }

        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$certificate) {
            $certificate = $this->createCertificate($user, $course, $userCourse);
        }

        return view('certificate.show', compact('certificate', 'course', 'user'));
    }

    public function download($certificateNumber)
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['user', 'course'])
            ->firstOrFail();

        $user = Auth::user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        // Просто возвращаем HTML, пользователь сам нажмет "Печать в PDF" в браузере
        $html = view('certificate.pdf', compact('certificate'))->render();

        return response($html)->header('Content-Type', 'text/html');
    }

    public function print($certificateNumber)
    {
        $certificate = Certificate::where('certificate_number', $certificateNumber)
            ->with(['user', 'course'])
            ->firstOrFail();

        $user = Auth::user();

        if ($certificate->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        return view('certificate.print', compact('certificate'));
    }

    public function generatePdf($certificateNumber)
    {
        // То же самое что и download, но для просмотра
        return $this->download($certificateNumber);
    }

    public function verify($verificationCode)
    {
        $certificate = Certificate::where('verification_code', $verificationCode)
            ->with(['user', 'course'])
            ->first();

        if (!$certificate) {
            return response()->json([
                'valid' => false,
                'message' => 'Сертификат не найден'
            ]);
        }

        $isExpired = $certificate->expiry_date && now()->greaterThan($certificate->expiry_date);

        return response()->json([
            'valid' => !$isExpired,
            'certificate' => [
                'number' => $certificate->certificate_number,
                'student_name' => $certificate->user->name ?? 'Неизвестно',
                'course_title' => $certificate->course_title,
                'level' => $certificate->level,
                'issue_date' => $certificate->issue_date->format('d.m.Y'),
                'expiry_date' => $certificate->expiry_date ? $certificate->expiry_date->format('d.m.Y') : null,
                'is_expired' => $isExpired
            ]
        ]);
    }

    private function createCertificate(User $user, Course $course, UserCourse $userCourse)
    {
        $averageScore = $this->calculateAverageScore($user->id, $course->id);
        $lessonsCompleted = $this->calculateLessonsCompleted($user->id, $course->id);

        $duration = 3;

        $certificate = new Certificate();
        $certificate->certificate_number = 'CERT-' . strtoupper(substr(md5(uniqid()), 0, 8)) . '-' . date('Y');
        $certificate->user_id = $user->id;
        $certificate->course_id = $course->id;
        $certificate->user_course_id = $userCourse->id;
        $certificate->course_title = $course->title;
        $certificate->level = $course->level;
        $certificate->duration = $duration;
        $certificate->lessons_count = $course->lessons_count;
        $certificate->lessons_completed = $lessonsCompleted;
        $certificate->average_score = $averageScore;
        $certificate->issue_date = now()->toDateString();
        $certificate->expiry_date = now()->addYears(2)->toDateString();
        $certificate->verification_code = strtoupper(substr(md5(uniqid()), 0, 12));
        $certificate->metadata = [
            'user_name' => $user->name,
            'user_email' => $user->email,
            'course_slug' => $course->slug,
            'issued_by' => 'Knowly Online School',
            'completion_date' => $userCourse->completed_at ? $userCourse->completed_at->format('Y-m-d H:i:s') : null
        ];
        $certificate->save();

        return $certificate;
    }

    private function calculateAverageScore($userId, $courseId)
    {
        $averageScore = DB::table('test_results')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereNotNull('score')
            ->avg('score');

        return $averageScore ? round($averageScore) : 0;
    }

    private function calculateLessonsCompleted($userId, $courseId)
    {
        return DB::table('user_lessons')
            ->join('lessons', 'user_lessons.lesson_id', '=', 'lessons.id')
            ->where('user_lessons.user_id', $userId)
            ->where('lessons.course_id', $courseId)
            ->where('user_lessons.completed', 1)
            ->count();
    }
    public function check($verificationCode)
    {
        $certificate = Certificate::where('verification_code', $verificationCode)
            ->with(['user', 'course'])
            ->first();

        if (!$certificate) {
            return view('certificate.not-found', [
                'message' => 'Сертификат не найден. Проверьте правильность кода проверки.'
            ]);
        }

        $isExpired = $certificate->expiry_date && now()->greaterThan($certificate->expiry_date);
        $status = $isExpired ? 'expired' : 'valid';

        return view('certificate.check', compact('certificate', 'status', 'isExpired'));
    }
}

