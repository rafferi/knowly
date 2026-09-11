<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Проверка сертификата - Knowly</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verification-container {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .verification-header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .verification-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .verification-status {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
            font-size: 14px;
        }

        .status-valid {
            background: #2ecc71;
            color: white;
        }

        .status-expired {
            background: #e74c3c;
            color: white;
        }

        .verification-content {
            padding: 30px;
        }

        .certificate-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
        }

        .info-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            flex: 0 0 200px;
            font-weight: 600;
            color: #2c3e50;
        }

        .info-value {
            flex: 1;
            color: #34495e;
        }

        .verification-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #eee;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: white;
            border: 2px solid #3498db;
            color: #3498db;
        }

        .btn-outline:hover {
            background: #3498db;
            color: white;
        }

        .verification-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            border-top: 1px solid #eee;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .qr-code img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .verification-url {
            background: #f1f2f6;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 14px;
            word-break: break-all;
        }

        @media (max-width: 768px) {
            .verification-container {
                margin: 10px;
            }

            .verification-header {
                padding: 20px;
            }

            .verification-content {
                padding: 20px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                flex: none;
                margin-bottom: 5px;
            }

            .verification-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="verification-container">
    <div class="verification-header">
        <h1><i class="fas fa-shield-check"></i> Проверка сертификата</h1>
        <p>Система проверки подлинности сертификатов Knowly</p>

        @if($status === 'valid')
            <div class="verification-status status-valid">
                <i class="fas fa-check-circle"></i> Сертификат действителен
            </div>
        @else
            <div class="verification-status status-expired">
                <i class="fas fa-exclamation-triangle"></i> Срок действия сертификата истёк
            </div>
        @endif
    </div>

    <div class="verification-content">
        <div class="certificate-info">
            <h3 style="margin-bottom: 20px; color: #2c3e50;">
                <i class="fas fa-certificate"></i> Информация о сертификате
            </h3>

            <div class="info-row">
                <div class="info-label">Номер сертификата:</div>
                <div class="info-value">{{ $certificate->certificate_number }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Имя студента:</div>
                <div class="info-value">{{ $certificate->user->name }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Название курса:</div>
                <div class="info-value">{{ $certificate->course_title }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Уровень:</div>
                <div class="info-value">{{ $certificate->level }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Дата выдачи:</div>
                <div class="info-value">{{ date('d.m.Y', strtotime($certificate->issue_date)) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Срок действия:</div>
                <div class="info-value">{{ date('d.m.Y', strtotime($certificate->expiry_date)) }}</div>
            </div>

            <div class="info-row">
                <div class="info-label">Код проверки:</div>
                <div class="info-value">
                    <strong style="font-family: monospace;">{{ $certificate->verification_code }}</strong>
                </div>
            </div>
        </div>

        <div class="verification-url">
            <i class="fas fa-link"></i> Ссылка для проверки:
            {{ url("/certificate-check/{$certificate->verification_code}") }}
        </div>

        <div class="verification-actions">
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> На главную
            </a>

            @if($certificate->user)
                <a href="{{ route('certificate.download', $certificate->certificate_number) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Скачать сертификат
                </a>
            @endif

            <button onclick="window.print()" class="btn btn-outline">
                <i class="fas fa-print"></i> Распечатать эту страницу
            </button>

            <button onclick="shareVerification()" class="btn btn-outline">
                <i class="fas fa-share-alt"></i> Поделиться результатом
            </button>
        </div>
    </div>

    <div class="verification-footer">
        <p><i class="fas fa-info-circle"></i> Эта страница подтверждает подлинность сертификата, выданного онлайн-школой Knowly.</p>
        <p style="margin-top: 5px; font-size: 12px;">Проверено: {{ now()->format('d.m.Y H:i') }}</p>
    </div>
</div>

<script>
    function shareVerification() {
        const verificationData = `
Сертификат №: {{ $certificate->certificate_number }}
        Студент: {{ $certificate->user->name }}
        Курс: {{ $certificate->course_title }}
        Статус: {{ $status === 'valid' ? 'Действителен' : 'Истёк' }}
        Проверка: {{ url("/certificate-check/{$certificate->verification_code}") }}
        `.trim();

        if (navigator.share) {
            navigator.share({
                title: 'Результат проверки сертификата Knowly',
                text: verificationData,
                url: window.location.href,
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(verificationData)
                .then(() => alert('Данные скопированы в буфер обмена!'))
                .catch(() => {
                    const textArea = document.createElement('textarea');
                    textArea.value = verificationData;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Данные скопированы в буфер обмена!');
                });
        }
    }

    // Автоматически предлагаем поделиться при загрузке
    setTimeout(() => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('share') === 'true') {
            shareVerification();
        }
    }, 1000);
</script>
</body>
</html>
