<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#123d32">

    <title>ورود مدیران | پرچم سرخ</title>

    <style>
        @font-face {
            font-family: Vazirmatn;
            src: url('/assets/fonts/Vazirmatn-Regular.ttf') format('truetype');
            font-weight: 400;
            font-display: swap;
        }

        @font-face {
            font-family: Vazirmatn;
            src: url('/assets/fonts/Vazirmatn-Medium.ttf') format('truetype');
            font-weight: 500;
            font-display: swap;
        }

        @font-face {
            font-family: Vazirmatn;
            src: url('/assets/fonts/Vazirmatn-Bold.ttf') format('truetype');
            font-weight: 700;
            font-display: swap;
        }

        :root {
            font-family: Vazirmatn, sans-serif;
            color: #24342e;
            background: #f3f6f4;
            font-synthesis: none;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(circle at 10% 10%, #dcebe4 0, transparent 35%),
                radial-gradient(circle at 90% 90%, #e8eeea 0, transparent 35%),
                #f3f6f4;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 42px;
            background: #fff;
            border: 1px solid #e7ece8;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(20, 55, 43, .09);
        }

        .brand {
            text-align: center;
            margin-bottom: 34px;
        }

        .brand-mark {
            width: 62px;
            height: 62px;
            margin: 0 auto 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: #123d32;
            color: #fff;
            font-size: 30px;
            font-weight: 700;
            position: relative;
        }

        .brand-mark::after {
            content: "";
            position: absolute;
            width: 10px;
            height: 10px;
            background: #c8323e;
            border: 3px solid #fff;
            border-radius: 50%;
            bottom: -3px;
            left: -3px;
        }

        .brand h1 {
            margin: 0;
            color: #123d32;
            font-size: 23px;
            font-weight: 700;
        }

        .brand p {
            margin: 8px 0 0;
            color: #829088;
            font-size: 13px;
        }

        .section-title {
            margin-bottom: 25px;
        }

        .section-title h2 {
            margin: 0 0 7px;
            font-size: 19px;
            font-weight: 700;
        }

        .section-title p {
            margin: 0;
            color: #849087;
            font-size: 13px;
        }

        .field {
            margin-bottom: 19px;
        }

        .field label {
            display: block;
            margin-bottom: 9px;
            color: #34443b;
            font-size: 13px;
            font-weight: 500;
        }

        .field input {
            width: 100%;
            height: 49px;
            padding: 0 15px;
            border: 1px solid #dfe7e1;
            border-radius: 11px;
            outline: none;
            background: #fbfcfb;
            color: #24342e;
            font: inherit;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
        }

        .field input:focus {
            border-color: #286b54;
            box-shadow: 0 0 0 3px rgba(40, 107, 84, .10);
            background: #fff;
        }

        .field input::placeholder {
            color: #aab5ae;
        }

        .submit-button {
            width: 100%;
            height: 51px;
            margin-top: 7px;
            border: 0;
            border-radius: 11px;
            background: #164c3c;
            color: #fff;
            font: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .submit-button:hover {
            background: #103a2e;
        }

        .submit-button:active {
            transform: scale(.99);
        }

        .alert {
            margin-bottom: 20px;
            padding: 13px 15px;
            border: 1px solid #f0c8cb;
            border-radius: 10px;
            background: #fff4f4;
            color: #a52b35;
            font-size: 13px;
            line-height: 1.9;
        }

        .footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf0ed;
            color: #98a29c;
            text-align: center;
            font-size: 11px;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px;
            }

            .login-card {
                padding: 30px 23px;
                border-radius: 18px;
            }
        }
    </style>
</head>

<body>
    <main class="login-card">
        <header class="brand">
            <div class="brand-mark" aria-hidden="true">پ</div>

            <h1>پرچم سرخ</h1>

            <p>سامانه مدیریت و همراهی داوطلبانه</p>
        </header>

        <section class="section-title">
            <h2>ورود به پنل مدیریت</h2>
            <p>برای ادامه، اطلاعات حساب مدیریتی خود را وارد کنید.</p>
        </section>

        @if ($errors->any())
            <div class="alert" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="field">
                <label for="mobile">شماره موبایل</label>

                <input
                    id="mobile"
                    name="mobile"
                    type="tel"
                    inputmode="numeric"
                    autocomplete="username"
                    placeholder="مثلاً 09123456789"
                    value="{{ old('mobile') }}"
                    maxlength="11"
                    required
                    autofocus
                >
            </div>

            <div class="field">
                <label for="password">رمز عبور</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="رمز عبور خود را وارد کنید"
                    required
                >
            </div>

            <button type="submit" class="submit-button">
                ورود به سامانه
            </button>
        </form>

        <footer class="footer">
            دسترسی به این بخش فقط برای مدیران تأییدشده مجاز است.
        </footer>
    </main>
</body>
</html>
