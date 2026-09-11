@extends('admin.layout')

@section('title', 'Тесты')
@section('page-title', 'Тесты')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Список тестов</h2>
            <a href="{{ route('admin.tests.create') }}" class="btn btn-primary">Добавить тест</a>
        </div>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Курс</th>
                <th>Тип</th>
                <th>Минимальный балл</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($tests as $test)
                <tr>
                    <td>{{ $test->id }}</td>
                    <td>{{ $test->title }}</td>
                    <td>{{ $test->course->title }}</td>
                    <td>
                        @if($test->type == 'placement')
                            Placement Test
                        @elseif($test->type == 'module_test')
                            Module Test
                        @elseif($test->type == 'weekly_test')
                            Weekly Test
                        @elseif($test->type == 'midterm')
                            Midterm
                        @elseif($test->type == 'final')
                            Final Test
                        @else
                            Practice Test
                        @endif
                    </td>
                    <td>{{ $test->passing_score }}%</td>
                    <td>
                        <a href="{{ route('admin.tests.edit', $test) }}" class="btn btn-outline">Редактировать</a>
                        <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить тест?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $tests->links() }}
        </div>
    </div>
@endsection
