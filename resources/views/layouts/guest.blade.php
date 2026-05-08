<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="antialiased text-black bg-[#EAF0FA] font-poppins bg-[url('../images/bg-auth.png')] bg-cover bg-center bg-no-repeat">
    <div
        class="mx-4 sm:mx-16 my-8 sm:my-12 mpb-10 rounded-[40px] bg-white/60 backdrop-blur-lg">
        <a href="{{ url('/') }}" class="flex justify-center">
            <img
                class="w-[140px] sm:w-[175px] pt-6 sm:pt-[50px] pb-2"
                src="{{ Vite::asset('resources/images/logo-navbar.png') }}"
                alt="Logo">
        </a>

        <div class="flex flex-col items-center justify-center gap-6 pr-0 sm:flex-row sm:gap-12 min-h-[60vh]">
            <div
                class="w-full sm:w-[500px] px-6 sm:px-[50px] py-8 mt-2 sm:mt-2 mb-16 sm:mb-24 bg-white shadow-2xl rounded-[50px]">
                {{ $slot }}
            </div>

            <img
                class="w-full sm:w-[480px] hidden sm:block mb-6 sm:mb-[50px] px-6 sm:px-0"
                src="{{ Vite::asset('resources/images/login-girl.png') }}"
                alt="Login girl">


        </div>
    </div>

</body>

</html>
