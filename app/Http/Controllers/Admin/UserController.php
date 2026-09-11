<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Поиск по имени или email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтр по роли
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Фильтр по уровню
        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
        }

        // Сортировка
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $users = $query->paginate(20);

        // Для фильтров
        $roles = ['student', 'teacher', 'admin'];
        $levels = ['beginner', 'elementary', 'intermediate', 'upper_intermediate', 'advanced', 'master'];

        return view('admin.users.index', compact('users', 'roles', 'levels'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,teacher,admin',
            'level' => 'required|in:beginner,elementary,intermediate,upper_intermediate,advanced,master'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'level' => $request->level,
            'xp' => $request->xp ?? 0
        ]);

        return redirect()->route('admin.users')->with('success', 'Пользователь создан');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:student,teacher,admin',
            'level' => 'required|in:beginner,elementary,intermediate,upper_intermediate,advanced,master',
            'xp' => 'nullable|integer|min:0'
        ]);

        $data = $request->only(['name', 'email', 'role', 'level', 'xp', 'phone', 'bio']);

        // Обновляем пароль только если он указан
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Пользователь обновлен');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Пользователь удален');
    }
}
