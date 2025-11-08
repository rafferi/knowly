<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
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

            // Удаляем старый аватар
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
