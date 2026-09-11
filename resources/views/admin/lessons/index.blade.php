@extends('admin.layout')

@section('title', 'Уроки')
@section('page-title', 'Уроки')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Список уроков</h2>
            <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary">Добавить урок</a>
        </div>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Курс</th>
                <th>Модуль</th>
                <th>Порядок</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lessons as $lesson)
                <tr>
                    <td>{{ $lesson->id }}</td>
                    <td>{{ $lesson->title }}</td>
                    <td>{{ $lesson->course->title }}</td>
                    <td>{{ $lesson->module->title ?? 'Без модуля' }}</td>
                    <td>{{ $lesson->order }}</td>
                    <td>
                        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="btn btn-outline">Редактировать</a>
                        <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить урок?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $lessons->links() }}
        </div>
    </div>
@endsection
