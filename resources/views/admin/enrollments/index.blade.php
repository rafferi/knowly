@extends('admin.layout')

@section('title', 'Записи на курсы')
@section('page-title', 'Записи на курсы')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Список записей</h2>
            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">Добавить запись</a>
        </div>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Курс</th>
                <th>Статус</th>
                <th>Статус оплаты</th>
                <th>Цена</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->id }}</td>
                    <td>{{ $enrollment->user->name }} ({{ $enrollment->user->email }})</td>
                    <td>{{ $enrollment->course->title }}</td>
                    <td>
                        @if($enrollment->status == 'active')
                            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Активна</span>
                        @elseif($enrollment->status == 'pending')
                            <span style="background: #ffc107; color: black; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Ожидание</span>
                        @elseif($enrollment->status == 'completed')
                            <span style="background: #17a2b8; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Завершена</span>
                        @elseif($enrollment->status == 'cancelled')
                            <span style="background: #dc3545; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Отменена</span>
                        @endif
                    </td>
                    <td>
                        @if($enrollment->payment_status == 'paid')
                            <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Оплачено</span>
                        @elseif($enrollment->payment_status == 'pending')
                            <span style="background: #ffc107; color: black; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Ожидание</span>
                        @elseif($enrollment->payment_status == 'failed')
                            <span style="background: #dc3545; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">Ошибка</span>
                        @endif
                    </td>
                    <td>{{ $enrollment->price ? number_format($enrollment->price, 0, '', ' ') . ' ₽' : 'Бесплатно' }}</td>
                    <td>{{ $enrollment->created_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="btn btn-outline">Редактировать</a>
                        <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить запись?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $enrollments->links() }}
        </div>
    </div>
@endsection
