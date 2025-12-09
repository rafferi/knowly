@extends('app')

@section('title', 'Чек об оплате - Knowly')

@section('content')
    <div class="receipt-container">
        <div class="receipt-card">
            <div class="receipt-header">
                <h1>Чек об оплате</h1>
                <p>Держите ваш чек в безопасности. Он может понадобиться для бухгалтерии.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="receipt-content">
                <div class="receipt-info">
                    <div class="info-row">
                        <span>Номер чека:</span>
                        <strong>{{ $receipt->receipt_number }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Дата оплаты:</span>
                        <strong>{{ $receipt->paid_at->format('d.m.Y H:i') }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Статус:</span>
                        <strong class="status-paid">Оплачено</strong>
                    </div>
                </div>

                <div class="course-details">
                    <h3>Курс: {{ $receipt->course->title }}</h3>
                    <div class="course-meta">
                        <span class="meta-item"><i class="fas fa-graduation-cap"></i> {{ $receipt->course->level }}</span>
                        <span class="meta-item"><i class="fas fa-clock"></i> {{ $receipt->course->duration }} месяцев</span>
                        <span class="meta-item"><i class="fas fa-book"></i> {{ $receipt->course->lessons_count }} уроков</span>
                    </div>
                </div>

                <div class="price-details">
                    <div class="price-row">
                        <span>Стоимость курса:</span>
                        <span>{{ number_format($receipt->amount, 0, '', ' ') }} ₽</span>
                    </div>
                    <div class="price-row">
                        <span>Способ оплаты:</span>
                        <span>Банковская карта ****{{ $receipt->metadata['card_last_four'] ?? '****' }}</span>
                    </div>
                    <div class="price-row total">
                        <span>Итого:</span>
                        <span>{{ number_format($receipt->amount, 0, '', ' ') }} ₽</span>
                    </div>
                </div>

                <div class="user-details">
                    <h4>Плательщик</h4>
                    <div class="user-info">
                        <p><strong>ФИО:</strong> {{ $receipt->user->name }}</p>
                        <p><strong>Email:</strong> {{ $receipt->user->email }}</p>
                        <p><strong>Дата заказа:</strong> {{ $receipt->created_at->format('d.m.Y') }}</p>
                    </div>
                </div>

                <div class="receipt-actions">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Распечатать чек
                    </button>
                    <a href="{{ route('learning.plan', $receipt->course->slug) }}" class="btn btn-outline">
                        <i class="fas fa-play-circle"></i> Перейти к обучению
                    </a>
                    <a href="{{ route('enrollment.my-enrollments') }}" class="btn btn-outline">
                        <i class="fas fa-list"></i> К моим записям
                    </a>
                </div>

                <div class="receipt-footer">
                    <p><i class="fas fa-info-circle"></i> Для печати нажмите кнопку "Распечатать чек" или Ctrl+P</p>
                    <p><i class="fas fa-save"></i> Чтобы сохранить как PDF, при печати выберите "Сохранить как PDF"</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .receipt-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .receipt-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid var(--nimbus-cloud);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--grenadine);
        }

        .receipt-header h1 {
            color: var(--black-beauty);
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .receipt-header p {
            color: var(--ultimate-gray);
        }

        .receipt-content {
            margin-top: 1rem;
        }

        .receipt-info {
            background: var(--gray-light);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .status-paid {
            color: #28a745;
            font-weight: bold;
        }

        .course-details {
            margin: 2rem 0;
            padding: 1.5rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
        }

        .course-details h3 {
            margin-top: 0;
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .course-meta {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--ultimate-gray);
        }

        .meta-item i {
            color: var(--grenadine);
        }

        .price-details {
            background: white;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .price-row.total {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--grenadine);
            padding-top: 1.5rem;
        }

        .user-details {
            margin: 2rem 0;
            padding: 1.5rem;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .user-details h4 {
            margin-top: 0;
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .user-info p {
            margin: 0.5rem 0;
        }

        .receipt-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 3rem 0;
            padding-top: 2rem;
            border-top: 2px solid var(--nimbus-cloud);
        }

        .receipt-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--nimbus-cloud);
            color: var(--ultimate-gray);
            font-size: 0.9rem;
        }

        .receipt-footer p {
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .receipt-footer i {
            color: var(--grenadine);
        }

        @media print {
            .receipt-actions,
            .receipt-footer,
            .alert {
                display: none !important;
            }

            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 20px !important;
            }

            .receipt-container {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            body {
                background: white !important;
            }

            .course-details,
            .price-details,
            .user-details,
            .receipt-info {
                border: 1px solid #ccc !important;
                margin: 10px 0 !important;
            }
        }

        @media (min-width: 768px) {
            .receipt-actions {
                flex-direction: row;
            }

            .receipt-actions .btn {
                flex: 1;
            }
        }
    </style>
@endsection
