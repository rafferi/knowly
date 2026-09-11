@extends('admin.layout')

@section('title', 'Редактировать пользователя')
@section('page-title', 'Редактировать пользователя')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Редактировать пользователя</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Имя</label>
                    <input type="text" name="name" value="{{ $user->name }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Роль</label>
                    <select name="role" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Студент</option>
                        <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Преподаватель</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Уровень английского</label>
                    <select name="level" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="beginner" {{ $user->level == 'beginner' ? 'selected' : '' }}>Начинающий</option>
                        <option value="elementary" {{ $user->level == 'elementary' ? 'selected' : '' }}>Элементарный</option>
                        <option value="intermediate" {{ $user->level == 'intermediate' ? 'selected' : '' }}>Средний</option>
                        <option value="upper_intermediate" {{ $user->level == 'upper_intermediate' ? 'selected' : '' }}>Выше среднего</option>
                        <option value="advanced" {{ $user->level == 'advanced' ? 'selected' : '' }}>Продвинутый</option>
                        <option value="master" {{ $user->level == 'master' ? 'selected' : '' }}>Мастер</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">XP</label>
                    <input type="number" name="xp" value="{{ $user->xp }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Телефон</label>
                    <input type="text" name="phone" value="{{ $user->phone }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">О себе</label>
                    <textarea name="bio" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">{{ $user->bio }}</textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
