<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\UserCourse;
use App\Models\UserLearningProgress;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, $courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Для записи на курс необходимо авторизоваться');
        }

        try {
            DB::beginTransaction();

            $existingEnrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existingEnrollment) {
                if ($existingEnrollment->isActive()) {
                    return redirect()->route('courses.show', $courseSlug)
                        ->with('info', 'Вы уже записаны на этот курс');
                }

                if ($existingEnrollment->status === 'pending') {
                    return redirect()->route('enrollment.checkout', $courseSlug);
                }

                $existingEnrollment->delete();
            }

            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'pending',
                'price' => $course->price,
                'payment_status' => 'pending',
                'enrolled_at' => now(),
                'start_date' => now(),
                'end_date' => now()->addMonths($course->duration),
                'metadata' => [
                    'course_title' => $course->title,
                    'course_level' => $course->level,
                    'duration_months' => $course->duration
                ]
            ]);

            DB::commit();

            return redirect()->route('enrollment.checkout', $courseSlug)
                ->with('success', 'Курс добавлен в корзину. Перейдите к оформлению заказа.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Произошла ошибка при записи на курс');
        }
    }

    public function checkout($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('enrollment.checkout', compact('course', 'enrollment'));
    }

    public function processPayment(Request $request, $courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)->active()->firstOrFail();

        $request->validate([
            'card_number' => 'required|regex:/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/',
            'card_expiry' => 'required|regex:/^\d{2}\/\d{2}$/',
            'card_cvc' => 'required|digits:3',
            'card_holder' => 'required|string|min:2',
            'email' => 'required|email',
            'agreement' => 'required|accepted'
        ]);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $cardNumber = str_replace(' ', '', $request->card_number);
            $lastFourDigits = substr($cardNumber, -4);
            $paymentSuccessful = true;

            if ($paymentSuccessful) {
                $enrollment->update([
                    'payment_method' => 'card',
                    'payment_status' => 'paid',
                    'status' => 'active',
                    'payment_id' => 'pay_' . time() . '_' . uniqid(),
                    'metadata' => array_merge($enrollment->metadata ?? [], [
                        'card_last_four' => $lastFourDigits,
                        'card_holder' => $request->card_holder,
                        'payment_email' => $request->email,
                        'payment_date' => now()->toDateTimeString()
                    ])
                ]);

                $userCourse = UserCourse::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $course->id],
                    [
                        'progress' => 0,
                        'current_lesson' => 0,
                        'started_at' => now(),
                        'stats' => [
                            'enrollment_id' => $enrollment->id,
                            'start_date' => now()->toDateString(),
                            'payment_method' => $enrollment->payment_method,
                            'payment_id' => $enrollment->payment_id
                        ]
                    ]
                );

                $learningProgress = UserLearningProgress::updateOrCreate(
                    ['user_id' => $user->id, 'course_id' => $course->id],
                    [
                        'current_week' => 1,
                        'total_weeks' => $course->duration * 4,
                        'start_date' => now(),
                        'estimated_completion' => now()->addMonths($course->duration),
                        'weekly_goal_hours' => 5,
                        'notifications_enabled' => true,
                        'learning_pace' => 'normal',
                        'overall_progress' => 0,
                        'lessons_completed' => 0,
                        'total_study_time' => 0
                    ]
                );

                $user->increment('xp', 50);

                // Создаем чек
                $receipt = $this->createReceipt($user, $course, $enrollment, $request);

                DB::commit();

                $this->sendPaymentConfirmation($user, $course, $enrollment);

                return redirect()->route('enrollment.receipt', $receipt->id)
                    ->with('success', 'Поздравляем! Вы успешно записались на курс "' . $course->title . '"');

            } else {
                $enrollment->update([
                    'payment_status' => 'failed',
                    'metadata' => array_merge($enrollment->metadata ?? [], [
                        'payment_error' => 'Payment declined',
                        'last_payment_attempt' => now()->toDateTimeString()
                    ])
                ]);

                DB::commit();

                return redirect()->back()
                    ->with('error', 'Оплата не прошла. Пожалуйста, проверьте данные карты и попробуйте снова.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());

            $enrollment->update([
                'payment_status' => 'failed',
                'metadata' => array_merge($enrollment->metadata ?? [], [
                    'payment_error' => $e->getMessage(),
                    'last_payment_attempt' => now()->toDateTimeString()
                ])
            ]);

            return redirect()->back()
                ->with('error', 'Ошибка при обработке платежа: ' . $e->getMessage());
        }
    }

    public function myEnrollments()
    {
        $user = Auth::user();

        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->orderBy('enrolled_at', 'desc')
            ->paginate(10);

        return view('enrollment.my-enrollments', compact('enrollments'));
    }

    public function cancelEnrollment($id)
    {
        $user = Auth::user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        try {
            DB::beginTransaction();

            if ($enrollment->status === 'active' && $enrollment->payment_status === 'paid') {
                if (now()->diffInDays($enrollment->enrolled_at) <= 7) {
                    $enrollment->update([
                        'status' => 'cancelled',
                        'payment_status' => 'refunded',
                        'notes' => 'Отменено пользователем ' . now()->toDateString(),
                        'metadata' => array_merge($enrollment->metadata ?? [], [
                            'refund_date' => now()->toDateTimeString(),
                            'refund_reason' => 'user_cancellation'
                        ])
                    ]);

                    UserCourse::where('user_id', $user->id)
                        ->where('course_id', $enrollment->course_id)
                        ->delete();

                    UserLearningProgress::where('user_id', $user->id)
                        ->where('course_id', $enrollment->course_id)
                        ->delete();

                    DB::commit();

                    return redirect()->route('enrollment.my-enrollments')
                        ->with('success', 'Запись на курс отменена. Средства будут возвращены в течение 3-5 рабочих дней.');
                }
            }

            $enrollment->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->route('enrollment.my-enrollments')
                ->with('info', 'Запись на курс отменена');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment cancellation error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Ошибка при отмене записи');
        }
    }

    public function freeEnrollment($courseSlug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $courseSlug)
            ->where('price', 0)
            ->active()
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'active',
                'price' => 0,
                'payment_method' => 'free',
                'payment_status' => 'paid',
                'enrolled_at' => now(),
                'start_date' => now(),
                'end_date' => now()->addMonths($course->duration),
                'metadata' => [
                    'course_title' => $course->title,
                    'enrollment_type' => 'free'
                ]
            ]);

            UserCourse::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $course->id],
                [
                    'progress' => 0,
                    'current_lesson' => 0,
                    'started_at' => now()
                ]
            );

            UserLearningProgress::updateOrCreate(
                ['user_id' => $user->id, 'course_id' => $course->id],
                [
                    'current_week' => 1,
                    'total_weeks' => $course->duration * 4,
                    'start_date' => now(),
                    'estimated_completion' => now()->addMonths($course->duration)
                ]
            );

            DB::commit();

            return redirect()->route('learning.plan', $courseSlug)
                ->with('success', 'Вы успешно записались на бесплатный курс!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Free enrollment error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ошибка при записи на курс');
        }
    }

    /**
     * Создание чека
     */
    private function createReceipt($user, $course, $enrollment, $request)
    {
        // Генерируем уникальный номер чека
        $receiptNumber = 'RCP-' . date('Ymd') . '-' . str_pad(Receipt::count() + 1, 6, '0', STR_PAD_LEFT);

        $receipt = Receipt::create([
            'enrollment_id' => $enrollment->id,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'receipt_number' => $receiptNumber,
            'amount' => $course->price,
            'payment_method' => $enrollment->payment_method,
            'status' => 'paid',
            'paid_at' => now(),
            'metadata' => [
                'card_last_four' => substr(str_replace(' ', '', $request->card_number), -4),
                'card_holder' => $request->card_holder,
                'payment_email' => $request->email,
                'course_title' => $course->title,
                'course_level' => $course->level,
                'duration_months' => $course->duration,
                'enrollment_date' => now()->toDateTimeString()
            ]
        ]);

        return $receipt;
    }

    /**
     * Показать чек
     */
    public function showReceipt($receiptId)
    {
        $receipt = Receipt::with(['user', 'course', 'enrollment'])
            ->where('id', $receiptId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('receipt.show', compact('receipt'));
    }

    /**
     * Распечатать чек
     */
    public function printReceipt($receiptId)
    {
        $receipt = Receipt::with(['user', 'course', 'enrollment'])
            ->where('id', $receiptId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('receipt.print', compact('receipt'));
    }

    /**
     * Отправка подтверждения оплаты
     */
    private function sendPaymentConfirmation($user, $course, $enrollment)
    {
        Log::info('Payment confirmation sent to user ' . $user->id . ' for course ' . $course->id);
        return true;
    }
}
