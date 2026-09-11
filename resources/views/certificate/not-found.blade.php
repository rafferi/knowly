<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сертификат не найден - Knowly</title>
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

        .not-found-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 50px 30px;
        }

        .not-found-icon {
            font-size: 80px;
            color: #e74c3c;
            margin-bottom: 20px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        p {
            color: #7f8c8d;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .verification-form {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn {
            padding: 12px 30px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #95a5a6;
            margin-top: 15px;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        @media (min-width: 768px) {
            .actions {
                flex-direction: row;
                justify-content: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="not-found-container">
    <div class="not-found-icon">
        <i class="fas fa-times-circle"></i>
    </div>

    <h1>Сертификат не найден</h1>

    <p>{{ $message ?? 'Проверьте правильность кода проверки. Убедитесь, что код введён полностью и без ошибок.' }}</p>

    <div class="verification-form">
        <h3 style="margin-bottom: 15px; color: #2c3e50;">Проверить другой сертификат</h3>
        <form action="{{ route('certificate.check', '') }}" method="GET" onsubmit="return checkForm()">
            <div class="form-group">
                <input type="text" id="verificationCode" name="code"
                       placeholder="Введите код проверки (например: DB706041E759)"
                       required autofocus>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-search"></i> Проверить
            </button>
        </form>
    </div>

    <div class="actions">
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <i class="fas fa-home"></i> На главную
        </a>
        <a href="{{ route('courses') }}" class="btn btn-secondary">
            <i class="fas fa-graduation-cap"></i> Наши курсы
        </a>
        <a href="mailto:support@knowly.ru" class="btn btn-secondary">
            <i class="fas fa-envelope"></i> Техподдержка
        </a>
    </div>
</div>

<script>
    function checkForm() {
        const code = document.getElementById('verificationCode').value.trim();
        if (!code) {
            alert('Пожалуйста, введите код проверки');
            return false;
        }

        // Обновляем action формы с введённым кодом
        const form = document.querySelector('form');
        form.action = "{{ route('certificate.check', '') }}/" + encodeURIComponent(code);
        return true;
    }

    // Автоматически предлагаем ввести код
    setTimeout(() => {
        document.getElementById('verificationCode')?.focus();
    }, 500);
</script>
</body>
</html>
