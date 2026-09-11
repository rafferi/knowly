@extends('admin.layout')

@section('title', 'Курсы')
@section('page-title', 'Курсы')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Список курсов</h2>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">Добавить курс</a>
        </div>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Уроков</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->title }}</td>
                    <td>{{ $course->category->name ?? 'Без категории' }}</td>
                    <td>{{ number_format($course->price, 0, '', ' ') }} ₽</td>
                    <td>{{ $course->lessons_count }}</td>
                    <td>
                        @if($course->is_active)
                            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Активен</span>
                        @else
                            <span style="background: #dc3545; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Не активен</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-outline">Редактировать</a>
                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить курс?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $courses->links() }}
        </div>
    </div>
@endsection
