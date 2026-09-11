@extends('admin.layout')

@section('title', 'Редактировать запись')
@section('page-title', 'Редактировать запись')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Редактировать запись</div>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Пользователь</label>
                    <select name="user_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $enrollment->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Курс</label>
                    <select name="course_id" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ $enrollment->course_id == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Статус</label>
                    <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="pending" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>Ожидание</option>
                        <option value="active" {{ $enrollment->status == 'active' ? 'selected' : '' }}>Активна</option>
                        <option value="completed" {{ $enrollment->status == 'completed' ? 'selected' : '' }}>Завершена</option>
                        <option value="cancelled" {{ $enrollment->status == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Статус оплаты</label>
                    <select name="payment_status" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="pending" {{ $enrollment->payment_status == 'pending' ? 'selected' : '' }}>Ожидание</option>
                        <option value="paid" {{ $enrollment->payment_status == 'paid' ? 'selected' : '' }}>Оплачено</option>
                        <option value="failed" {{ $enrollment->payment_status == 'failed' ? 'selected' : '' }}>Ошибка</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Цена</label>
                    <input type="number" name="price" value="{{ $enrollment->price }}" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.enrollments') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
