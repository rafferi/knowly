@extends('app')

@section('title', 'Мои записи на курсы - Knowly')

@section('content')
    <div class="enrollments-container">
        <div class="enrollments-header">
            <h1>Мои записи на курсы</h1>
            <p>Здесь вы можете управлять своими записями на курсы</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="enrollments-grid">
            @forelse($enrollments as $enrollment)
                <div class="enrollment-card">
                    <div class="enrollment-header">
                        <div class="enrollment-status">
                        <span class="status-badge status-{{ $enrollment->status }}">
                            {{ $enrollment->status_label }}
                        </span>
                            <span class="payment-status payment-{{ $enrollment->payment_status }}">
                            {{ $enrollment->payment_status_label }}
                        </span>
                        </div>
                        <div class="enrollment-date">
                            {{ $enrollment->enrolled_at->format('d.m.Y') }}
                        </div>
                    </div>

                    <div class="enrollment-course">
                        <h3>{{ $enrollment->course->title }}</h3>
                        <div class="course-level">{{ $enrollment->course->level }}</div>
                    </div>

                    <div class="enrollment-details">
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span>Начало: {{ $enrollment->start_date->format('d.m.Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-check"></i>
                            <span>Завершение: {{ $enrollment->end_date->format('d.m.Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Стоимость: {{ number_format($enrollment->price, 0, '', ' ') }} ₽</span>
                        </div>
                    </div>

                    <div class="enrollment-actions">
                        @if($enrollment->canAccessCourse())
                            <a href="{{ route('learning.plan', $enrollment->course->slug) }}" class="btn btn-primary">
                                <i class="fas fa-play-circle"></i> Продолжить обучение
                            </a>
                        @elseif($enrollment->status === 'pending' && $enrollment->payment_status === 'pending')
                            <a href="{{ route('enrollment.checkout', $enrollment->course->slug) }}" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Оплатить
                            </a>
                            <form action="{{ route('enrollment.cancel', $enrollment->id) }}" method="POST" class="cancel-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" onclick="return confirm('Вы уверены, что хотите отменить запись?')">
                                    <i class="fas fa-times"></i> Отменить
                                </button>
                            </form>
                        @endif

                        @if($enrollment->status === 'active' && $enrollment->payment_status === 'paid')
                            @if(now()->diffInDays($enrollment->enrolled_at) <= 7)
                                <form action="{{ route('enrollment.cancel', $enrollment->id) }}" method="POST" class="cancel-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" onclick="return confirm('Вы уверены, что хотите отменить запись? Возврат средств возможен в течение 3-5 рабочих дней.')">
                                        <i class="fas fa-times"></i> Отменить запись
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>У вас нет записей на курсы</h3>
                    <p>Начните обучение, записавшись на один из курсов</p>
                    <a href="/courses" class="btn btn-primary">Выбрать курс</a>
                </div>
            @endforelse
        </div>

        @if($enrollments->hasPages())
            <div class="pagination">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>

    <style>
        .enrollments-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .enrollments-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .enrollments-header h1 {
            color: var(--black-beauty);
            margin-bottom: 0.5rem;
        }

        .enrollments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .enrollment-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--nimbus-cloud);
        }

        .enrollment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .enrollment-status {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .status-badge, .payment-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-active { background: #d4edda; color: #155724; }
        .status-completed { background: #cce5ff; color: #004085; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .status-expired { background: #e2e3e5; color: #383d41; }

        .payment-pending { background: #fff3cd; color: #856404; }
        .payment-paid { background: #d4edda; color: #155724; }
        .payment-failed { background: #f8d7da; color: #721c24; }
        .payment-refunded { background: #e2e3e5; color: #383d41; }

        .enrollment-course h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
        }

        .course-level {
            display: inline-block;
            background: var(--grenadine);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
        }

        .enrollment-details {
            margin: 1.5rem 0;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--nimbus-cloud);
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item i {
            width: 20px;
            color: var(--grenadine);
        }

        .enrollment-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .cancel-form {
            margin-top: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--nimbus-cloud);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: var(--black-beauty);
        }

        .empty-state p {
            color: var(--ultimate-gray);
            margin-bottom: 1.5rem;
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        @media (min-width: 768px) {
            .enrollment-actions {
                flex-direction: row;
            }

            .cancel-form {
                margin-top: 0;
            }
        }
    </style>
@endsection
