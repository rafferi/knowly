<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Печать сертификата - {{ $certificate->certificate_number }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            @page {
                margin: 0;
                size: A4 portrait;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        .certificate {
            width: 100%;
            height: 100%;
            border: 20px solid #8B4513;
            padding: 15px;
            position: relative;
            background: white;
        }

        .border {
            width: 100%;
            height: 100%;
            border: 10px solid #D4AF37;
            padding: 20px;
            position: relative;
        }

        .content {
            width: 100%;
            height: 100%;
            border: 2px solid #8B0000;
            padding: 30px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header {
            margin-bottom: 20px;
        }

        .logo {
            font-size: 28px;
            color: #8B0000;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .title {
            margin: 20px 0;
        }

        .title h1 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .title p {
            font-size: 18px;
            color: #666;
        }

        .body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .awarded-to {
            font-size: 16px;
            margin-bottom: 15px;
            color: #555;
        }

        .student-name {
            font-size: 32px;
            color: #8B0000;
            margin: 20px 0;
            padding: 15px;
            border: 2px solid #D4AF37;
            background: #fffaf0;
            max-width: 80%;
            word-break: break-word;
        }

        .course-info {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        .course-title {
            font-size: 24px;
            color: #2c3e50;
            margin: 20px 0;
            max-width: 90%;
            word-break: break-word;
        }

        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 30px auto;
            max-width: 500px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #000;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            text-align: center;
            width: 150px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin: 10px auto 5px;
            width: 120px;
        }

        .certificate-number {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 10px;
            color: #666;
        }

        .verification {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 10px;
            color: #666;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.05;
            font-size: 80px;
            color: #000;
            z-index: 0;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .print-controls button {
            margin: 5px;
            padding: 10px 20px;
            background: #DF3F32;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-controls button:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
<div class="print-controls no-print">
    <button onclick="window.print()">
        <i class="fas fa-print"></i> Печать
    </button>
    <button onclick="window.close()">
        <i class="fas fa-times"></i> Закрыть
    </button>
</div>

<div class="certificate">
    <div class="border">
        <div class="content">
            <div class="watermark">KNOWLY</div>

            <div class="header">
                <div class="logo">Knowly</div>
                <div class="subtitle">Онлайн-школа английского языка</div>
            </div>

            <div class="title">
                <h1>СЕРТИФИКАТ</h1>
                <p>Об окончании курса</p>
            </div>

            <div class="body">
                <p class="awarded-to">Настоящим сертификатом удостоверяется, что</p>
                <div class="student-name">{{ $certificate->user->name }}</div>
                <p class="course-info">успешно завершил(а) курс</p>
                <div class="course-title">"{{ $certificate->course_title }}"</div>

                <div class="details">
                    <div class="detail-row">
                        <span>Уровень:</span>
                        <strong>{{ $certificate->level }}</strong>
                    </div>
                    <div class="detail-row">
                        <span>Длительность:</span>
                        <strong>{{ $certificate->duration }} месяцев</strong>
                    </div>
                    <div class="detail-row">
                        <span>Уроки:</span>
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
                </div>
            </div>

            <div class="footer">
                <div class="signature">
                    <div class="signature-line"></div>
                    <p>Директор Knowly</p>
                </div>

                <div class="signature">
                    <div class="signature-line"></div>
                    <p>Главный методист</p>
                </div>
            </div>

            <div class="certificate-number">
                Номер: {{ $certificate->certificate_number }}
            </div>

            <div class="verification">
                Код проверки: {{ $certificate->verification_code }}
            </div>
        </div>
    </div>
</div>
</body>
</html>
