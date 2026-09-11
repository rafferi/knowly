@extends('admin.layout')

@section('title', 'Создать пользователя')
@section('page-title', 'Создать пользователя')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Новый пользователь</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Имя</label>
                    <input type="text" name="name" value="{{ old('name') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Пароль</label>
                    <input type="password" name="password" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Роль</label>
                    <select name="role" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="student">Студент</option>
                        <option value="teacher">Преподаватель</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Уровень английского</label>
                    <select name="level" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="beginner">Начинающий</option>
                        <option value="elementary">Элементарный</option>
                        <option value="intermediate">Средний</option>
                        <option value="upper_intermediate">Выше среднего</option>
                        <option value="advanced">Продвинутый</option>
                        <option value="master">Мастер</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
