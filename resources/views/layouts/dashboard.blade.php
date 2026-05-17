{{-- layout khusus dashboard dengan sticky navbar responsif --}}
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

<body class="bg-[radial-gradient(#FFFFFF_18%,_#EAF0FA_44%)] pt-10">

    {{-- Sticky Navbar --}}
    <header class="sticky top-0 z-50">
        <nav
            class="w-full border-b border-white/40 bg-white/40 backdrop-blur-lg supports-[backdrop-filter]:bg-white/30 rounded-full mx-4 md:mx-auto md:w-3/4">

            <div class="flex items-center justify-between px-4 py-3 md:py-0 md:px-9">


                <a href="{{ url('/') }}" class="w-[150px] md:w-[170px]">
                    <img src="{{ Vite::asset('resources/images/logo-navbar.png') }}" alt="Logo" class="w-full h-auto">
                </a>

                {{-- Desktop menu --}}
                <div class="items-center hidden gap-10 text-lg font-medium text-black md:flex md:text-xl">
                    <a href="{{ route('about') }}" class="transition-transform duration-200 hover:scale-110">About</a>
                    <a href="{{ route('contact') }}" class="transition-transform duration-200 hover:scale-110">Contact Us</a>
                </div>

                {{-- Mobile actions --}}
                <div class="flex items-center gap-3">
                    {{-- Mobile hamburger (no server-side menu; just toggles local links) --}}
                    <button type="button" id="nav-toggle"
                        class="inline-flex items-center justify-center p-2 transition-colors border md:hidden rounded-xl border-black/20 bg-white/50 hover:bg-white">

                        <svg id="nav-toggle-open" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="nav-toggle-close" class="hidden w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        <div class="items-center hidden gap-4 text-base font-medium md:flex md:text-lg">
                            @auth
                                <a href="{{ url('/home') }}"
                                    class="px-5 md:px-8 shadow-xl py-2 text-white border bg-blue-800 rounded-full ease-out hover:scale-105 transition-all duration-200 border-blue-600">
                                    Home
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-black bg-white border border-gray-100 rounded-full px-5 md:px-8 shadow-lg py-2 ease-out hover:scale-105 transition-all duration-200">
                                    Login
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="px-5 md:px-8 shadow-xl py-2 text-white border bg-blue-800 rounded-full ease-out hover:scale-105 transition-all duration-200 border-blue-600">
                                        Sign Up
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

            {{-- Mobile dropdown menu --}}
            <div id="nav-mobile-menu" class="hidden px-4 pb-4">

                <div class="flex flex-col gap-3">
                    <a href="{{ route('about') }}"
                        class="px-4 py-2 transition-colors border rounded-2xl bg-white/60 border-white/60 hover:bg-white">About</a>
                    <a href="{{ route('contact') }}"
                        class="px-4 py-2 transition-colors border rounded-2xl bg-white/60 border-white/60 hover:bg-white">Contact Us</a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}"
                                class="px-4 py-2 rounded-2xl text-white bg-[#1616b0] hover:bg-[#5e5ec5] transition-colors">Home</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 transition-colors border rounded-2xl bg-white/60 border-black/20 hover:bg-white">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 rounded-2xl text-white bg-[#1616b0] hover:bg-[#5e5ec5] transition-colors">Sign Up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="pt-0 md:pt-0">
            @yield('content')
        </div>
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
            duration: 1200,
            once: true,
        });

        // Toggle mobile navbar
        const toggleBtn = document.getElementById('nav-toggle');
        const mobileMenu = document.getElementById('nav-mobile-menu');
        const iconOpen = document.getElementById('nav-toggle-open');
        const iconClose = document.getElementById('nav-toggle-close');

        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', () => {
                const willOpen = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                if (willOpen) {
                    iconOpen && iconOpen.classList.add('hidden');
                    iconClose && iconClose.classList.remove('hidden');
                } else {
                    iconClose && iconClose.classList.add('hidden');
                    iconOpen && iconOpen.classList.remove('hidden');
                }
            });
        }

        // Close on click outside (simple)
        document.addEventListener('click', (e) => {
            if (!mobileMenu || !toggleBtn) return;
            const clickedInside = mobileMenu.contains(e.target) || toggleBtn.contains(e.target);
            if (!clickedInside && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                iconClose && iconClose.classList.add('hidden');
                iconOpen && iconOpen.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>

