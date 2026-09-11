<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\Course;
use App\Models\UserLesson;
use App\Models\TestResult;
use App\Models\UserAchievement;
use App\Models\Achievement;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Загружаем все необходимые данные
        $user->load([
            'activeCourses.course',
            'completedCourses.course',
            'userLessons' => function($query) {
                $query->with('lesson')
                    ->where('completed', true)
                    ->orderBy('completed_at', 'desc')
                    ->limit(15);
            },
            'testResults' => function($query) {
                $query->with('test')
                    ->orderBy('completed_at', 'desc')
                    ->limit(15);
            },
            'dailyActivities' => function($query) {
                $query->orderBy('activity_date', 'desc')
                    ->limit(7);
            }
        ]);

        // Загружаем достижения через отдельный запрос без pivot
        $achievementsData = \DB::table('user_achievements')
            ->join('achievements', 'user_achievements.achievement_id', '=', 'achievements.id')
            ->where('user_achievements.user_id', $user->id)
            ->select('achievements.*', 'user_achievements.achieved_at')
            ->orderBy('user_achievements.achieved_at', 'desc')
            ->limit(5)
            ->get();

        // Формируем объединенную историю активности
        $activities = collect();

        // Добавляем пройденные уроки
        foreach ($user->userLessons as $userLesson) {
            if ($userLesson->completed && $userLesson->completed_at) {
                $activities->push([
                    'type' => 'lesson',
                    'title' => $userLesson->lesson->title ?? 'Урок',
                    'description' => 'Урок завершен',
                    'score' => $userLesson->score,
                    'time_spent' => $userLesson->time_spent,
                    'date' => $userLesson->completed_at,
                    'icon' => 'fas fa-play-circle'
                ]);
            }
        }

        // Добавляем завершенные тесты
        foreach ($user->testResults as $testResult) {
            if ($testResult->completed_at) {
                $activities->push([
                    'type' => 'test',
                    'title' => $testResult->test->title ?? 'Тест',
                    'description' => 'Тест завершен',
                    'score' => $testResult->score,
                    'date' => $testResult->completed_at,
                    'icon' => 'fas fa-clipboard-check'
                ]);
            }
        }

        // Добавляем полученные достижения
        foreach ($achievementsData as $achievement) {
            $activities->push([
                'type' => 'achievement',
                'title' => $achievement->name,
                'description' => $achievement->description,
                'date' => $achievement->achieved_at,
                'icon' => 'fas fa-trophy'
            ]);
        }

        // Сортируем по дате (новые сверху) и ограничиваем 20 записей
        $activities = $activities->sortByDesc('date')->take(20);

        return view('profile', compact('user', 'activities', 'achievementsData'));
    }

    public function updateAvatar(Request $request)
    {
        try {
            $user = Auth::user();

            \Log::info('Avatar upload started for user: ' . $user->id);

            // Валидация
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка валидации',
                    'errors' => $validator->errors()
                ], 422);
            }

            $avatarPath = public_path('storage/avatars');
            if (!file_exists($avatarPath)) {
                \Log::info('Creating directory: ' . $avatarPath);
                mkdir($avatarPath, 0755, true);
            }

            $avatar = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();

            \Log::info('Attempting to save file: ' . $filename);

            $avatar->move($avatarPath, $filename);

            if ($user->avatar && file_exists($avatarPath . '/' . $user->avatar)) {
                unlink($avatarPath . '/' . $user->avatar);
            }

            $user->avatar = $filename;
            $user->save();

            \Log::info('Avatar uploaded successfully: ' . $filename);

            return response()->json([
                'success' => true,
                'message' => 'Аватар успешно обновлен!',
                'avatar_url' => asset('storage/avatars/' . $filename)
            ]);

        } catch (\Exception $e) {
            \Log::error('Avatar upload error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Ошибка: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect('/profile')
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return redirect('/profile')->with('success', 'Профиль успешно обновлен!');
    }

    public function deleteAvatar(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
                $user->avatar = null;
                $user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Аватар успешно удален!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Avatar delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка сервера при удалении аватара: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/profile')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect('/profile')
                ->withErrors(['current_password' => 'Текущий пароль неверен.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect('/profile')->with('success', 'Пароль успешно изменен!');
    }
}
