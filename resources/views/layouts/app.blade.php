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
        <header class="flex font-poppins items-center fixed justify-between w-full bg-[#E8EEF9] z-50">

            <div class="flex items-center gap-8">
                <a href="{{ url('/home') }}">
                    <img class="w-[110px]" src="{{ Vite::asset('resources/images/logo-favicon.png') }}" alt="Logo">
                </a>

                <div class="flex flex-col pt-2">
                    <div class="text-3xl font-bold text-gray-900">Hi, {{ Auth::user()->name }}!</div>
                    <p class="text-xs text-gray-500">Let's take a look at your activity today</p>
                </div>
            </div>

            <div class="pt-2 pl-6 text-xl font-bold text-gray-500">{{ \Carbon\Carbon::now()->format('d M Y') }}</div>

            <div class="flex items-center gap-8 pt-4 mr-8">
                <div x-data="searchComponent()" class="relative">
                    <div class="relative transition-transform duration-200 hover:hover:scale-105">
                        <input type="text" 
                            x-model="query"
                            @input.debounce.300ms="searchTasks()"
                            @focus="if(results.length > 0) open = true"
                            @click.away="open = false"
                            @keydown.escape="open = false"
                            placeholder="Search tasks..."
                            class="w-[220px] py-2 pl-16 pr-4 border-white bg-white rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <svg class="absolute w-6 h-6 text-gray-400 -translate-y-1/2 left-3 top-1/2" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10 0.5C4.75329 0.5 0.5 4.75329 0.5 10C0.5 15.2467 4.75329 19.5 10 19.5C12.082 19.5 14.0076 18.8302 15.5731 17.6944L20.2929 22.4142C20.6834 22.8047 21.3166 22.8047 21.7071 22.4142L22.4142 21.7071C22.8047 21.3166 22.8047 20.6834 22.4142 20.2929L17.6944 15.5731C18.8302 14.0076 19.5 12.082 19.5 10C19.5 4.75329 15.2467 0.5 10 0.5ZM3.5 10C3.5 6.41015 6.41015 3.5 10 3.5C13.5899 3.5 16.5 6.41015 16.5 10C16.5 13.5899 13.5899 16.5 10 16.5C6.41015 16.5 3.5 13.5899 3.5 10Z"
                                    fill="#0C1F3B"></path>
                            </g>
                        </svg>
                    </div>

                    <!-- Search Results Dropdown -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-0 right-0 z-[100] mt-3 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden border border-white/50 min-w-[320px]"
                         style="display: none;">
                        
                        <div>
                            <template x-for="task in results" :key="task.id">
                                <div @click="goToTask(task)" 
                                     class="flex items-center gap-4 px-5 py-4 transition-all border-b cursor-pointer hover:bg-blue-50/80 border-gray-50 last:border-0 group">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm text-[#132C51] group-hover:text-blue-600 transition-colors flex flex-wrap items-center gap-1.5 break-all whitespace-normal" 
                                             :class="task.completed ? 'line-through text-gray-400' : ''">
                                             <span x-text="task.title"></span>
                                             <svg :class="{
                                                'text-red-500 shadow-red-200': task.priority === 'Urgent',
                                                'text-yellow-500 shadow-yellow-200': task.priority === 'High',
                                                'text-blue-500 shadow-blue-200': task.priority === 'Normal',
                                                'text-green-500 shadow-green-200': task.priority === 'Low',
                                                'text-gray-300': !task.priority
                                             }" class="flex-shrink-0 w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
                                             </svg>
                                             <template x-for="ws in task.workspaces" :key="ws.id">
                                                <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] shadow-sm border border-[#1C427A]/10" :title="ws.name" x-text="ws.name.substring(0, 2).toUpperCase()"></div>
                                             </template>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-1 flex items-center gap-1.5" x-show="task.start_time || task.end_time">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span x-text="(task.start_time ? task.start_time.substring(0, 5) : '-') + ' - ' + (task.end_time ? task.end_time.substring(0, 5) : '-')"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end flex-shrink-0 gap-1">
                                        <div class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full" x-text="formatDate(task.due_date)"></div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="results.length === 0 && query.length > 1 && !loading">
                                <div class="px-6 py-10 text-center">
                                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-gray-50">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No tasks found for "<span class="text-[#132C51]" x-text="query"></span>"</p>
                                </div>
                            </template>

                            <template x-if="loading">
                                <div class="px-6 py-10 text-center">
                                    <div class="inline-block w-6 h-6 border-blue-500 rounded-full border-3 border-t-transparent animate-spin"></div>
                                    <p class="mt-2 text-xs font-medium text-gray-400">Searching...</p>
                                </div>
                            </template>
                        </div>
                        
                        <div x-show="results.length > 0" class="px-5 py-2 text-[10px] text-center text-gray-400 bg-gray-50/50 border-t border-gray-50">
                            Press <kbd class="px-1.5 py-0.5 bg-white border rounded shadow-sm font-sans">Esc</kbd> to close
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" title="Profile">
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

                <main class="flex-1 pt-3 pl-6 pr-6">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </div>
</body>

</html>

<script>
    function searchComponent() {
        return {
            query: '',
            results: [],
            open: false,
            loading: false,

            async searchTasks() {
                if (this.query.length < 2) {
                    this.results = [];
                    this.open = false;
                    return;
                }

                this.loading = true;
                this.open = true;

                try {
                    const response = await fetch(`/tasks/search?query=${encodeURIComponent(this.query)}`);
                    this.results = await response.json();
                } catch (error) {
                    console.error('Search error:', error);
                } finally {
                    this.loading = false;
                }
            },

            formatDate(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', { day: '2-digit', month: 'short' });
            },

            goToTask(task) {
                this.open = false;
                this.query = '';
                
                // Dispatch event for pages that have the modal (home/schedule)
                window.dispatchEvent(new CustomEvent('open-task-details', { 
                    detail: { taskId: task.id }
                }));

                // Fallback: if no modal on page, redirect to home
                setTimeout(() => {
                    const modal = document.getElementById('task-details-modal');
                    if (!modal || modal.classList.contains('hidden')) {
                        // Check if the event was handled by checking modal state
                        // If it's still hidden after 100ms, probably no one handled it or we are on wrong page
                        if (!modal) {
                            window.location.href = `/home?open_task=${task.id}`;
                        }
                    }
                }, 150);
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Keep the old filtering logic as fallback or for local filtering
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                
                // Filter view.blade.php tasks
                document.querySelectorAll('.space-y-4.text-lg.text-\\[\\#132C51\\] > div.mb-2').forEach(container => {
                    const taskTitle = container.querySelector('span.ml-4.flex-1')?.textContent.toLowerCase() || '';
                    container.style.display = taskTitle.includes(query) || query === '' ? '' : 'none';
                });

                // Filter schedule.blade.php tasks
                document.querySelectorAll('#task-list > div[data-task-id]').forEach(taskDiv => {
                    const taskTitle = taskDiv.querySelector('span.translate-y-\\[\\-2px\\]')?.textContent.toLowerCase() || '';
                    taskDiv.style.display = taskTitle.includes(query) || query === '' ? '' : 'none';
                });
            });
        }
    });
</script>
