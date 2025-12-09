@extends('app')

@section('title', 'Оформление заказа - Knowly')

@section('content')
    <div class="checkout-container">
        <div class="checkout-card">
            <div class="checkout-header">
                <h1>Оформление заказа</h1>
                <p>Завершите оформление записи на курс</p>
            </div>

            <div class="checkout-content">
                <div class="course-summary">
                    <div class="course-summary-header">
                        <h3>{{ $course->title }}</h3>
                        <div class="course-level">{{ $course->level }}</div>
                    </div>

                    <div class="course-summary-details">
                        <div class="summary-item">
                            <span>Длительность:</span>
                            <strong>{{ $course->duration }} месяцев</strong>
                        </div>
                        <div class="summary-item">
                            <span>Уроков:</span>
                            <strong>{{ $course->lessons_count }}</strong>
                        </div>
                        <div class="summary-item">
                            <span>Формат:</span>
                            <strong>{{ $course->group_size ?? 'Индивидуально' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="price-summary">
                    <div class="price-item">
                        <span>Стоимость курса</span>
                        <span>{{ number_format($course->price, 0, '', ' ') }} ₽</span>
                    </div>
                    <div class="price-item total">
                        <span>Итого к оплате</span>
                        <span class="total-price">{{ number_format($course->price, 0, '', ' ') }} ₽</span>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('enrollment.process-payment', $course->slug) }}" method="POST" class="payment-form" id="payment-form">
                    @csrf

                    <div class="payment-methods">
                        <h3>Способ оплаты</h3>
                        <div class="payment-options">
                            <label class="payment-option active">
                                <input type="radio" name="payment_method" value="card" checked>
                                <div class="payment-option-content">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Банковская карта</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="card-form">
                        <h4>Данные карты</h4>

                        <div class="form-group">
                            <label for="card_number" class="form-label">Номер карты</label>
                            <div class="card-input-wrapper">
                                <input type="text" id="card_number" name="card_number" class="form-input card-number"
                                       placeholder="0000 0000 0000 0000" maxlength="19" required>
                                <div class="card-icons">
                                    <i class="fab fa-cc-visa"></i>
                                    <i class="fab fa-cc-mastercard"></i>
                                    <i class="fab fa-cc-mir"></i>
                                </div>
                            </div>
                            <div class="error-message" id="card_number_error"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="card_expiry" class="form-label">Срок действия</label>
                                <input type="text" id="card_expiry" name="card_expiry" class="form-input card-expiry"
                                       placeholder="ММ/ГГ" maxlength="5" required>
                                <div class="error-message" id="card_expiry_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="card_cvc" class="form-label">CVC/CVV</label>
                                <input type="password" id="card_cvc" name="card_cvc" class="form-input card-cvc"
                                       placeholder="000" maxlength="3" required>
                                <div class="error-message" id="card_cvc_error"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="card_holder" class="form-label">Имя держателя карты</label>
                            <input type="text" id="card_holder" name="card_holder" class="form-input"
                                   placeholder="IVAN IVANOV" required>
                            <div class="error-message" id="card_holder_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email для квитанции</label>
                            <input type="email" id="email" name="email" class="form-input" value="{{ Auth::user()->email }}" required>
                        </div>

                        <div class="agreement">
                            <label class="checkbox-label">
                                <input type="checkbox" name="agreement" id="agreement" required>
                                <span>Я соглашаюсь с <a href="/terms">условиями оказания услуг</a> и <a href="/privacy">политикой конфиденциальности</a></span>
                            </label>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <button type="submit" class="btn btn-primary btn-pay" id="pay-button" disabled>
                            <i class="fas fa-lock"></i> Оплатить {{ number_format($course->price, 0, '', ' ') }} ₽
                        </button>
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Вернуться к курсу
                        </a>
                    </div>
                </form>

                <div class="security-notice">
                    <i class="fas fa-shield-alt"></i>
                    <span>Безопасная оплата. Ваши данные защищены</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .checkout-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .checkout-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .checkout-header {
            background: linear-gradient(135deg, var(--grenadine), #ff7a5a);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .checkout-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
        }

        .checkout-content {
            padding: 2rem;
        }

        .course-summary {
            background: var(--gray-light);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .course-summary-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .course-summary-header h3 {
            margin: 0;
            flex: 1;
        }

        .course-level {
            background: var(--grenadine);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        .course-summary-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .price-summary {
            background: white;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--nimbus-cloud);
        }

        .price-item.total {
            border-bottom: none;
            font-size: 1.2rem;
            font-weight: bold;
            padding-top: 1.5rem;
        }

        .total-price {
            color: var(--grenadine);
            font-size: 1.4rem;
        }

        .payment-methods {
            margin-bottom: 2rem;
        }

        .payment-methods h3 {
            margin-bottom: 1rem;
        }

        .payment-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-option {
            border: 2px solid var(--nimbus-cloud);
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-option.active {
            border-color: var(--grenadine);
            background: rgba(223, 63, 50, 0.05);
        }

        .payment-option:hover {
            border-color: var(--grenadine);
            background: rgba(223, 63, 50, 0.05);
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .payment-option-content i {
            font-size: 1.5rem;
        }

        .card-form {
            margin: 2rem 0;
            padding: 1.5rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 15px;
        }

        .card-form h4 {
            margin-bottom: 1.5rem;
            color: var(--black-beauty);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--black-beauty);
        }

        .form-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--nimbus-cloud);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: var(--grenadine);
            outline: none;
            box-shadow: 0 0 0 3px rgba(223, 63, 50, 0.1);
        }

        .form-input.error {
            border-color: #dc3545;
        }

        .card-input-wrapper {
            position: relative;
        }

        .card-icons {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 0.5rem;
        }

        .card-icons i {
            color: var(--ultimate-gray);
            font-size: 1.2rem;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            min-height: 1.25rem;
        }

        .agreement {
            margin: 1.5rem 0;
            padding: 1rem;
            background: var(--gray-light);
            border-radius: 10px;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .checkbox-label input[type="checkbox"] {
            margin-top: 0.3rem;
        }

        .checkbox-label a {
            color: var(--grenadine);
            text-decoration: none;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        .checkout-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 2rem 0;
        }

        .btn-pay {
            padding: 1rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-pay:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .security-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: var(--ultimate-gray);
            padding: 1rem;
            border-top: 1px solid var(--nimbus-cloud);
            margin-top: 2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (min-width: 768px) {
            .checkout-actions {
                flex-direction: row;
            }

            .checkout-actions .btn {
                flex: 1;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Форматирование номера карты
            const cardNumberInput = document.getElementById('card_number');
            const cardExpiryInput = document.getElementById('card_expiry');
            const cardCvcInput = document.getElementById('card_cvc');
            const cardHolderInput = document.getElementById('card_holder');
            const agreementCheckbox = document.getElementById('agreement');
            const payButton = document.getElementById('pay-button');
            const paymentForm = document.getElementById('payment-form');

            // Функция форматирования номера карты (группы по 4 цифры)
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{4})/g, '$1 ').trim();
                e.target.value = value.substring(0, 19);
                validateCardNumber();
                updatePayButton();
            });

            // Функция форматирования срока действия (MM/YY)
            cardExpiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2);
                }
                e.target.value = value.substring(0, 5);
                validateCardExpiry();
                updatePayButton();
            });

            // Функция валидации номера карты
            function validateCardNumber() {
                const value = cardNumberInput.value.replace(/\s/g, '');
                const errorElement = document.getElementById('card_number_error');

                if (value.length < 16) {
                    cardNumberInput.classList.add('error');
                    errorElement.textContent = 'Номер карты должен содержать 16 цифр';
                    return false;
                }

                // Простая проверка алгоритмом Луна
                if (!luhnCheck(value)) {
                    cardNumberInput.classList.add('error');
                    errorElement.textContent = 'Неверный номер карты';
                    return false;
                }

                cardNumberInput.classList.remove('error');
                errorElement.textContent = '';
                return true;
            }

            // Функция валидации срока действия
            function validateCardExpiry() {
                const value = cardExpiryInput.value;
                const errorElement = document.getElementById('card_expiry_error');

                if (!value.match(/^\d{2}\/\d{2}$/)) {
                    cardExpiryInput.classList.add('error');
                    errorElement.textContent = 'Формат: ММ/ГГ';
                    return false;
                }

                const [month, year] = value.split('/').map(Number);
                const now = new Date();
                const currentYear = now.getFullYear() % 100;
                const currentMonth = now.getMonth() + 1;

                if (month < 1 || month > 12) {
                    cardExpiryInput.classList.add('error');
                    errorElement.textContent = 'Неверный месяц';
                    return false;
                }

                if (year < currentYear || (year === currentYear && month < currentMonth)) {
                    cardExpiryInput.classList.add('error');
                    errorElement.textContent = 'Карта просрочена';
                    return false;
                }

                cardExpiryInput.classList.remove('error');
                errorElement.textContent = '';
                return true;
            }

            // Функция валидации CVC
            function validateCardCvc() {
                const value = cardCvcInput.value;
                const errorElement = document.getElementById('card_cvc_error');

                if (value.length < 3 || !/^\d{3}$/.test(value)) {
                    cardCvcInput.classList.add('error');
                    errorElement.textContent = 'Введите 3 цифры CVC';
                    return false;
                }

                cardCvcInput.classList.remove('error');
                errorElement.textContent = '';
                return true;
            }

            // Функция валидации имени держателя
            function validateCardHolder() {
                const value = cardHolderInput.value.trim();
                const errorElement = document.getElementById('card_holder_error');

                if (value.length < 2) {
                    cardHolderInput.classList.add('error');
                    errorElement.textContent = 'Введите имя держателя карты';
                    return false;
                }

                cardHolderInput.classList.remove('error');
                errorElement.textContent = '';
                return true;
            }

            // Алгоритм Луна для проверки номера карты
            function luhnCheck(cardNumber) {
                let sum = 0;
                let isEven = false;

                for (let i = cardNumber.length - 1; i >= 0; i--) {
                    let digit = parseInt(cardNumber.charAt(i));

                    if (isEven) {
                        digit *= 2;
                        if (digit > 9) {
                            digit -= 9;
                        }
                    }

                    sum += digit;
                    isEven = !isEven;
                }

                return sum % 10 === 0;
            }

            // Обновление состояния кнопки оплаты
            function updatePayButton() {
                const isFormValid = validateCardNumber() &&
                    validateCardExpiry() &&
                    validateCardCvc() &&
                    validateCardHolder() &&
                    agreementCheckbox.checked;

                payButton.disabled = !isFormValid;
            }

            // Обработчики событий для валидации
            cardCvcInput.addEventListener('input', function() {
                validateCardCvc();
                updatePayButton();
            });

            cardHolderInput.addEventListener('input', function() {
                validateCardHolder();
                updatePayButton();
            });

            agreementCheckbox.addEventListener('change', updatePayButton);

            // Обработчик отправки формы
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Проверяем все поля перед отправкой
                if (!validateCardNumber() || !validateCardExpiry() || !validateCardCvc() || !validateCardHolder()) {
                    alert('Пожалуйста, проверьте правильность введенных данных карты');
                    return;
                }

                if (!agreementCheckbox.checked) {
                    alert('Пожалуйста, согласитесь с условиями оказания услуг');
                    return;
                }

                // Показываем индикатор загрузки
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Обработка платежа...';

                // Отправляем форму
                this.submit();
            });

            // Инициализация валидации
            validateCardNumber();
            validateCardExpiry();
            validateCardCvc();
            validateCardHolder();
            updatePayButton();
        });
    </script>
@endsection
