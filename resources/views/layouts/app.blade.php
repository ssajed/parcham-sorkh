<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#123c32">
    <meta name="description" content="@yield('meta_description', 'پرچم سرخ؛ بستری برای همیاری داوطلبانه و همراهی انسانی.')">

    <title>@yield('title', 'پرچم سرخ') | با هم، برای هم</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <a class="skip-link" href="#main-content">
        رفتن به محتوای اصلی
    </a>

    @yield('content')
</body>
</html>