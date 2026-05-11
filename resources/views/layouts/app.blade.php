{{-- layout untuk home, view, schedule, notifications, setting, dan help --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-[#E8EEF9]">

    <div class="flex flex-col min-h-screen">
        <header class="flex font-poppins items-center fixed justify-between w-full bg-[#E8EEF9] pb-3 z-50">

            <div class="flex items-center gap-8 pt-2">
                <a href="{{ url('/home') }}">
                    <img class="w-[110px]" src="{{ Vite::asset('resources/images/logo-favicon.png') }}" alt="Logo">
                </a>

                <div class="flex flex-col pt-2">
                    <div class="text-3xl font-bold text-gray-900">Hi, {{ Auth::user()->name }}!</div>
                    <p class="text-xs text-gray-500">Let's take a look at your activity today</p>
                </div>
            </div>

            <div class="pt-4 text-xl font-bold text-gray-500 pr-7">{{ \Carbon\Carbon::now()->format('d M Y') }}</div>

            <div class="flex items-center gap-8 pt-4 mr-8">
                <div class="relative transition-transform duration-200 hover:hover:scale-105">
                    <input type="text" id="search-input" placeholder="Search"
                        class="w-[180px] py-2 pl-16 pr-4 border-white bg-white rounded-3xl focus:outline-none focus:ring-2 focus:ring-[#0E213D]">
                    <svg class="absolute w-6 h-6 text-gray-400 -translate-y-1/2 left-3 top-1/2" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10 0.5C4.75329 0.5 0.5 4.75329 0.5 10C0.5 15.2467 4.75329 19.5 10 19.5C12.082 19.5 14.0076 18.8302 15.5731 17.6944L20.2929 22.4142C20.6834 22.8047 21.3166 22.8047 21.7071 22.4142L22.4142 21.7071C22.8047 21.3166 22.8047 20.6834 22.4142 20.2929L17.6944 15.5731C18.8302 14.0076 19.5 12.082 19.5 10C19.5 4.75329 15.2467 0.5 10 0.5ZM3.5 10C3.5 6.41015 6.41015 3.5 10 3.5C13.5899 3.5 16.5 6.41015 16.5 10C16.5 13.5899 13.5899 16.5 10 16.5C6.41015 16.5 3.5 13.5899 3.5 10Z"
                                fill="#000000"></path>
                        </g>
                    </svg>
                </div>

                <a href="{{ route('profile.setting') }}" title="Profile">
                    @if (Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo"
                            class="object-cover w-12 h-12 transition-all duration-200 rounded-full cursor-pointer hover:hover:scale-110">
                    @else
                        <svg class="w-12 h-12 transition-all duration-200 cursor-pointer hover:hover:scale-110"
                            viewBox="3 3 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z"
                                    fill="#C0C0C0" fill-opacity="0.24"></path>
                                <circle cx="12" cy="10" r="4" fill="#A9A9A9"></circle>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M18.2209 18.2462C18.2791 18.3426 18.2613 18.466 18.1795 18.5432C16.5674 20.0662 14.3928 21 12 21C9.60728 21 7.43264 20.0663 5.82057 18.5433C5.73877 18.466 5.72101 18.3427 5.77918 18.2463C6.94337 16.318 9.29215 15 12.0001 15C14.7079 15 17.0567 16.3179 18.2209 18.2462Z"
                                    fill="#A9A9A9"></path>
                            </g>
                        </svg>
                    @endif
                </a>


            </div>
        </header>

        {{-- AREA KONTEN UTAMA --}}
        <div class="flex flex-1">

            {{-- SIDEBAR --}}
            @include('layouts.sidebar')

            {{-- AREA KONTEN KANAN (YANG BISA SCROLL) --}}
            <div class="flex flex-col flex-1">

                @isset($header)
                    <header class="bg-black shadow">
                        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 pt-6 pl-6 pr-6">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </div>
</body>

</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                // For view.blade.php - filter tasks in columns
                const taskContainers = document.querySelectorAll(
                    '.space-y-4.text-lg.text-\\[\\#132C51\\] > div.mb-2');
                taskContainers.forEach(container => {
                    const taskTitle = container.querySelector('span.ml-4.flex-1')?.textContent
                        .toLowerCase() || '';
                    const subtaskTitles = [];
                    const matches = taskTitle.includes(query) || subtaskTitles.some(title =>
                        title.includes(query));
                    container.style.display = matches || query === '' ? '' : 'none';
                });

                // For schedule.blade.php - filter tasks in task list
                const scheduleTasks = document.querySelectorAll('#task-list > div[data-task-id]');
                scheduleTasks.forEach(taskDiv => {
                    const taskTitle = taskDiv.querySelector('span.translate-y-\\[\\-2px\\]')
                        ?.textContent.toLowerCase() || '';
                    const subtaskTitles = [];
                    const matches = taskTitle.includes(query) || subtaskTitles.some(title =>
                        title.includes(query));
                    taskDiv.style.display = matches || query === '' ? '' : 'none';
                });
            });
        }
    });
</script>
