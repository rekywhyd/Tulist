{{-- layout untuk welcome, about, dan contact --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tulist</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[radial-gradient(#FFFFFF_18%,_#EAF0FA_44%)] mt-14">

    <header class="sticky top-0 z-50 w-3/4 mx-auto">
        <nav
            class="container flex items-center justify-between py-0 mx-auto border rounded-full px-9 font-poppins backdrop-blur-lg bg-white/50 border-white/70">
            <a href="{{ url('/') }}" class="w-[170px]">
                <img src="{{ Vite::asset('resources/images/logo-navbar.png') }}" alt="Logo">
            </a>

            <div class="flex gap-12 text-xl font-medium text-black">
                <a href="{{ route('about') }}" class="transition-transform duration-200 hover:hover:scale-110">About</a>
                <a href="{{ route('contact') }}" class="transition-transform duration-200 hover:hover:scale-110">Contact
                    Us</a>
            </div>

            <div>
                @if (Route::has('login'))
                    <div class="flex items-center gap-6 text-lg font-medium">
                        @auth
                            <a href="{{ url('/home') }}"
                                class="px-9 py-2 text-white border bg-[#1616b0] hover:bg-[#5e5ec5] rounded-full transition-colors duration-200 ease-out">
                                Home
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="py-2 text-black transition-colors duration-200 ease-out bg-white border border-black rounded-full px-9 hover:bg-gray-200">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-8 py-2 text-white border bg-[#1616b0] hover:bg-[#5e5ec5] rounded-full transition-colors duration-200 ease-out">
                                    Sign Up
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>
    </header>


    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container max-w-6xl p-6 mx-auto mt-12 text-center text-gray-400 font-poppins">
            <p>IF Untan @2025 credit</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS
        AOS.init({
            // Optional: Atur durasi dan efek global
            duration: 1200,
            once: true, // Animasi hanya berjalan sekali saat di-scroll ke
        });
    </script>
</body>

</html>
