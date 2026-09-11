@extends('admin.layout')

@section('title', 'Создать запись')
@section('page-title', 'Создать запись')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Новая запись на курс</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.enrollments.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Пользователь</label>
                    <select name="user_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Курс</label>
                    <select name="course_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Статус</label>
                    <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="pending">Ожидание</option>
                        <option value="active">Активна</option>
                        <option value="completed">Завершена</option>
                        <option value="cancelled">Отменена</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Статус оплаты</label>
                    <select name="payment_status" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="pending">Ожидание</option>
                        <option value="paid">Оплачено</option>
                        <option value="failed">Ошибка</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Цена</label>
                    <input type="number" name="price" value="{{ old('price') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.enrollments') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
