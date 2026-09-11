<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сертификат №{{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 portrait;
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
            margin: 0;
            padding: 0;
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
            overflow: hidden;
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
            position: relative;
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
            margin: 30px 0;
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
            margin-left: auto;
            margin-right: auto;
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
            margin-left: auto;
            margin-right: auto;
        }

        .details {
            margin: 30px auto;
            max-width: 500px;
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
            position: absolute;
            bottom: 30px;
            left: 30px;
            right: 30px;
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
            pointer-events: none;
        }

        /* Инструкция для пользователя - справа */
        .print-instruction {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            max-width: 280px;
            border: 1px solid #DF3F32;
            font-size: 12px;
            display: none;
        }

        .print-instruction h3 {
            margin: 0 0 10px 0;
            color: #DF3F32;
            font-size: 14px;
            text-align: center;
        }

        .print-instruction ol {
            margin: 0 0 10px 0;
            padding-left: 20px;
        }

        .print-instruction li {
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .print-btn {
            margin-top: 10px;
            padding: 8px 15px;
            background: #DF3F32;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 12px;
            font-weight: bold;
        }

        .print-btn:hover {
            background: #c82333;
        }

        /* Ссылка для проверки */
        .verification-note {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            color: #666;
            text-align: center;
            width: 100%;
        }

        /* Кнопка закрытия инструкции */
        .close-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #DF3F32;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        @media screen {
            .print-instruction {
                display: block;
            }
        }

        @media print {
            .print-instruction {
                display: none !important;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm;
                height: 297mm;
            }

            .certificate {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
            }

            .verification-note {
                display: block !important;
            }
        }
    </style>
</head>
<body>
<!-- Инструкция для пользователя - теперь справа -->
<div class="print-instruction" id="printInstruction">
    <button class="close-btn" onclick="closeInstruction()">×</button>
    <h3>Как сохранить в PDF:</h3>
    <ol>
        <li>Нажмите Ctrl+P (Windows) или Cmd+P (Mac)</li>
        <li>Выберите "Сохранить как PDF"</li>
        <li>Установите поля: "Минимум"</li>
        <li>Снимите галочку "Верхние и нижние колонтитулы"</li>
        <li>Нажмите "Сохранить"</li>
    </ol>
    <button onclick="window.print()" class="print-btn">
        Открыть окно печати
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

            <!-- Добавляем ссылку для проверки -->
            <div class="verification-note">
                Проверить подлинность: {{ url("/certificate-check/{$certificate->verification_code}") }}
            </div>
        </div>
    </div>
</div>

<script>
    // Функция закрытия инструкции
    function closeInstruction() {
        document.getElementById('printInstruction').style.display = 'none';
    }

    // Через 1 секунду после загрузки страницы показываем подсказку
    setTimeout(() => {
        console.log('Страница PDF загружена. Нажмите Ctrl+P для сохранения в PDF.');

        // Автоматически открываем диалог печати - закомментировано, так как раздражает
        // window.print();
    }, 1000);

    // Закрытие инструкции при клике вне ее
    document.addEventListener('click', function(event) {
        var instruction = document.getElementById('printInstruction');
        if (instruction && !instruction.contains(event.target)) {
            // Можно добавить функционал закрытия при клике вне инструкции
            // instruction.style.display = 'none';
        }
    });

    // Автофокус на инструкции при загрузке
    window.addEventListener('load', function() {
        var instruction = document.getElementById('printInstruction');
        if (instruction) {
            instruction.style.display = 'block';
        }
    });
</script>
</body>
</html>
