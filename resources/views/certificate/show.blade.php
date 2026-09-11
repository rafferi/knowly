@extends('app')

@section('title', 'Сертификат - Knowly')

@section('content')
    <div class="certificate-container">
        <div class="certificate-card">
            <div class="certificate-header">
                <h1>Сертификат об окончании курса</h1>
                <p>Поздравляем с успешным завершением курса!</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="certificate-content">
                <div class="certificate-preview">
                    <div class="certificate-border">
                        <div class="certificate-inner">
                            <div class="certificate-logo">
                                <h2>Knowly</h2>
                                <p>Онлайн-школа английского языка</p>
                            </div>

                            <div class="certificate-title">
                                <h3>Сертификат</h3>
                                <p>Об окончании курса</p>
                            </div>

                            <div class="certificate-body">
                                <p class="awarded-to">Настоящим сертификатом удостоверяется, что</p>
                                <h2 class="student-name">{{ $certificate->user->name }}</h2>
                                <p class="course-info">успешно завершил(а) курс</p>
                                <h3 class="course-title">"{{ $certificate->course_title }}"</h3>

                                <div class="certificate-details">
                                    <div class="detail-row">
                                        <span>Уровень:</span>
                                        <strong>{{ $certificate->level }}</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Длительность:</span>
                                        <strong>{{ $certificate->duration }} месяцев</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Количество уроков:</span>
                                        <strong>{{ $certificate->lessons_completed }}/{{ $certificate->lessons_count }}</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Средний балл:</span>
                                        <strong>{{ $certificate->average_score }}%</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Дата выдачи:</span>
                                        <strong>{{ date('d.m.Y', strtotime($certificate->issue_date)) }}</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Срок действия:</span>
                                        <strong>{{ date('d.m.Y', strtotime($certificate->expiry_date)) }}</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Номер сертификата:</span>
                                        <strong>{{ $certificate->certificate_number }}</strong>
                                    </div>
                                    <div class="detail-row">
                                        <span>Код проверки:</span>
                                        <strong>{{ $certificate->verification_code }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="certificate-footer">
                                <div class="signature-left">
                                    <div class="signature-line"></div>
                                    <p>Директор Knowly</p>
                                </div>
                                <div class="signature-right">
                                    <div class="signature-line"></div>
                                    <p>Главный методист</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="certificate-actions">
                    <a href="{{ route('certificate.download', $certificate->certificate_number) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download"></i> Скачать PDF
                    </a>
                    <a href="{{ route('certificate.print', $certificate->certificate_number) }}" class="btn btn-outline" target="_blank">
                        <i class="fas fa-print"></i> Печать
                    </a>
                    <button onclick="shareCertificate()" class="btn btn-outline">
                        <i class="fas fa-share-alt"></i> Поделиться
                    </button>
                    <a href="{{ route('certificate.pdf', $certificate->certificate_number) }}" class="btn btn-outline" target="_blank">
                        <i class="fas fa-eye"></i> Просмотр PDF
                    </a>
                </div>

                <div class="certificate-info">
                    <h4>Информация о сертификате</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Сертификат подтверждает успешное прохождение курса</li>
                        <li><i class="fas fa-check-circle"></i> Может быть использован при трудоустройстве</li>
                        <li><i class="fas fa-check-circle"></i> Подлинность можно проверить по коду: <strong>{{ $certificate->verification_code }}</strong></li>
                        <li><i class="fas fa-check-circle"></i> Сертификат действителен в течение 2 лет</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .certificate-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .certificate-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--grenadine);
        }

        .certificate-header h1 {
            color: var(--black-beauty);
            margin-bottom: 0.5rem;
        }

        .certificate-preview {
            margin: 2rem 0;
        }

        .certificate-border {
            border: 20px solid #f5f5f5;
            background: white;
            padding: 2rem;
            position: relative;
        }

        .certificate-inner {
            border: 2px solid #ddd;
            padding: 3rem;
            position: relative;
            background: white;
        }

        .certificate-logo {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--grenadine);
            padding-bottom: 1rem;
        }

        .certificate-logo h2 {
            color: var(--grenadine);
            font-size: 2.5rem;
            margin: 0;
        }

        .certificate-logo p {
            color: var(--ultimate-gray);
            margin: 0;
        }

        .certificate-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .certificate-title h3 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .certificate-title p {
            font-size: 1.2rem;
            color: var(--ultimate-gray);
        }

        .certificate-body {
            text-align: center;
            margin-bottom: 3rem;
        }

        .awarded-to {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #555;
        }

        .student-name {
            font-size: 2.5rem;
            color: var(--grenadine);
            margin: 1rem 0;
            padding: 1rem;
            background: #fff8f8;
            border-radius: 10px;
        }

        .course-info {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .course-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 2rem;
        }

        .certificate-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
            text-align: left;
            max-width: 800px;
            margin: 2rem auto;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem;
            border-bottom: 1px solid #eee;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 2px solid #ddd;
        }

        .signature-left, .signature-right {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin: 0 auto 0.5rem;
            width: 150px;
        }

        .certificate-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .certificate-info {
            background: var(--gray-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .certificate-info h4 {
            margin-bottom: 1rem;
            color: var(--black-beauty);
        }

        .certificate-info ul {
            list-style: none;
            padding: 0;
        }

        .certificate-info li {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .certificate-info i {
            color: var(--grenadine);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--grenadine);
            color: white;
        }

        .btn-primary:hover {
            background: #df3f32;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: white;
            border: 2px solid var(--grenadine);
            color: var(--grenadine);
        }

        .btn-outline:hover {
            background: var(--grenadine);
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .certificate-details {
                grid-template-columns: 1fr;
            }

            .certificate-inner {
                padding: 1.5rem;
            }

            .certificate-actions {
                flex-direction: column;
                align-items: center;
            }

            .certificate-actions .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            .certificate-border {
                padding: 1rem;
            }

            .student-name {
                font-size: 1.8rem;
            }

            .course-title {
                font-size: 1.5rem;
            }

            .certificate-footer {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
        }
    </style>

    <script>
        function shareCertificate() {
            if (navigator.share) {
                navigator.share({
                    title: 'Мой сертификат Knowly',
                    text: 'Я успешно завершил курс на платформе Knowly!',
                    url: window.location.href,
                })
                    .then(() => console.log('Поделились успешно'))
                    .catch((error) => console.log('Ошибка при использовании share:', error));
            } else {
                // Используйте новый маршрут для проверки
                const verificationUrl = '{{ url("/certificate-check/" . $certificate->verification_code) }}';
                const shareText = `Мой сертификат Knowly\nНомер: {{ $certificate->certificate_number }}\nКурс: {{ $certificate->course_title }}\nПроверить: ${verificationUrl}`;

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(shareText)
                        .then(() => alert('Ссылка для проверки скопирована в буфер обмена!'))
                        .catch(() => alert('Скопируйте эту ссылку для проверки сертификата:\n' + verificationUrl));
                } else {
                    alert('Скопируйте эту ссылку для проверки сертификата:\n' + verificationUrl);
                }
            }
        }
    </script>
@endsection
