<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чек об оплате - {{ $receipt->receipt_number }}</title>
    <style>
        @page {
            margin: 20mm;
            font-family: Arial, sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #DF3F32;
        }

        .header h1 {
            color: #DF3F32;
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 12px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #f5f5f5;
            padding: 8px 12px;
            font-weight: bold;
            border-left: 4px solid #DF3F32;
            margin: 0 0 10px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .info-table td:first-child {
            width: 40%;
            font-weight: bold;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .price-table th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .price-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .total-row {
            font-weight: bold;
            background: #fff8f8;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            padding-top: 5px;
            text-align: center;
            font-size: 12px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.1;
            font-size: 80px;
            color: #DF3F32;
            z-index: -1;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo h2 {
            color: #DF3F32;
            margin: 0;
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="watermark">KNOWLY</div>

<div class="container">
    <div class="logo">
        <h2>Knowly</h2>
        <div class="company-info">
            Онлайн-школа английского языка<br>
            ИНН: 7701234567 • ОГРН: 1187746543210<br>
            Телефон: +7 (800) 123-45-67 • Email: info@knowly.ru
        </div>
    </div>

    <div class="header">
        <h1>ЧЕК ОБ ОПЛАТЕ</h1>
        <p>№ {{ $receipt->receipt_number }} от {{ $receipt->paid_at->format('d.m.Y') }}</p>
    </div>

    <div class="section">
        <h3 class="section-title">Информация о платеже</h3>
        <table class="info-table">
            <tr>
                <td>Дата и время оплаты:</td>
                <td>{{ $receipt->paid_at->format('d.m.Y H:i') }}</td>
            </tr>
            <tr>
                <td>Статус платежа:</td>
                <td><strong>Оплачено</strong></td>
            </tr>
            <tr>
                <td>Способ оплата:</td>
                <td>Банковская карта</td>
            </tr>
            @if(isset($receipt->metadata['card_last_four']))
                <tr>
                    <td>Карта:</td>
                    <td>**** **** **** {{ $receipt->metadata['card_last_four'] }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h3 class="section-title">Информация о курсе</h3>
        <table class="info-table">
            <tr>
                <td>Название курса:</td>
                <td>{{ $receipt->course->title }}</td>
            </tr>
            <tr>
                <td>Уровень:</td>
                <td>{{ $receipt->course->level }}</td>
            </tr>
            <tr>
                <td>Длительность:</td>
                <td>{{ $receipt->course->duration }} месяцев</td>
            </tr>
            <tr>
                <td>Количество уроков:</td>
                <td>{{ $receipt->course->lessons_count }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3 class="section-title">Финансовые детали</h3>
        <table class="price-table">
            <thead>
            <tr>
                <th>Описание</th>
                <th>Количество</th>
                <th>Стоимость</th>
                <th>Сумма</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Курс "{{ $receipt->course->title }}"</td>
                <td>1</td>
                <td>{{ number_format($receipt->amount, 0, '', ' ') }} ₽</td>
                <td>{{ number_format($receipt->amount, 0, '', ' ') }} ₽</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>Итого к оплате:</strong></td>
                <td><strong>{{ number_format($receipt->amount, 0, '', ' ') }} ₽</strong></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3 class="section-title">Информация о плательщике</h3>
        <table class="info-table">
            <tr>
                <td>ФИО:</td>
                <td>{{ $receipt->user->name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $receipt->user->email }}</td>
            </tr>
            <tr>
                <td>Дата заказа:</td>
                <td>{{ $receipt->created_at->format('d.m.Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div class="signature-line">
            Плательщик<br>
            ___________________
        </div>
        <div class="signature-line">
            Исполнитель<br>
            ___________________
        </div>
    </div>

    <div class="footer">
        <p><strong>Примечание:</strong> Настоящий документ является подтверждением оплаты услуг онлайн-школы английского языка Knowly.</p>
        <p>Данный чек сформирован автоматически и не требует дополнительной подписи и печати.</p>
        <p>Для проверки чека обращайтесь по телефону: +7 (800) 123-45-67 или на email: info@knowly.ru</p>
        <p>Дата печати: {{ date('d.m.Y H:i:s') }}</p>
    </div>
</div>
</body>
</html>
