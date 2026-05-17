<aside class="fixed z-10 flex flex-col items-center h-screen pt-24 pb-2 pl-6 w-fit">

    <nav class="flex flex-col items-center justify-between h-full">

        {{-- KOLOM 1 --}}
        <div class="flex flex-col gap-2 border border-white w-fit bg-white/30 backdrop-blur-3xl rounded-3xl">
            {{-- ROUTE HOME --}}
            <a href="{{ route('home') }}" title="Home"
                class="p-3 transition-colors rounded-full nav-button duration-200 hover:hover:scale-110
          {{ request()->routeIs('home')
              ? 'bg-[#0E213D] text-[#D5E2F5]'
              : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">

                <svg class="w-7 h-7" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" fill="currentColor">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g id="页面-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Building" transform="translate(-96.000000, -48.000000)" fill-rule="nonzero">
                                <g id="home_3_fill" transform="translate(96.000000, 48.000000)">
                                    <path
                                        d="M13.2279,2.68814 C12.5057,2.12641 11.4944,2.12641 10.7722,2.68814 L2.38841,9.20884 C1.63605,9.79401 2.04989,11 3.00297,11 L4.00005,11 L4.00005,19 C4.00005,20.1046 4.89548,21 6.00005,21 L9.99999915,21 L9.99999915,15 C9.99999915,13.8954 10.8954,13 11.9999991,13 C13.1046,13 13.9999991,13.8954 13.9999991,15 L13.9999991,21 L18.0001,21 C19.1046,21 20.0001,20.1046 20.0001,19 L20.0001,11 L20.9971,11 C21.9492,11 22.3648,9.79463 21.6117,9.20884 L13.2279,2.68814 Z"
                                        id="路径" fill="currentColor"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>

            {{-- ROUTE SCHEDULE --}}
            <a href="{{ route('schedule') }}" title="Schedule"
                class="p-3 transition-colors rounded-full nav-button duration-200 hover:hover:scale-110
          {{ request()->routeIs('schedule')
              ? 'bg-[#0E213D] text-[#D5E2F5]'
              : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">

                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V7A3,3,0,0,0,19,4Zm1,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H20Zm0-9H4V7A1,1,0,0,1,5,6H7V7A1,1,0,0,0,9,7V6h6V7a1,1,0,0,0,2,0V6h2a1,1,0,0,1,1,1Z">
                        </path>
                    </g>
                </svg>
            </a>
        </div>

        {{-- KOLOM 2 --}}
        <div class="flex flex-col gap-2 border border-white w-fit bg-white/30 backdrop-blur-3xl rounded-3xl">
            {{-- ROUTE WORKSPACE --}}
            <a href="{{ route('workspace') }}" title="Workspace"
                class="relative p-3 transition-colors rounded-full nav-button duration-200 hover:hover:scale-110
        {{ request()->routeIs('workspace')
            ? 'bg-[#0E213D] text-[#D5E2F5]'
            : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">

                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2" stroke-linecap="round"></rect> <rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2" stroke-linecap="round"></rect> <rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2" stroke-linecap="round"></rect> <rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2" stroke-linecap="round"></rect> </g></svg>
                
                @php
                    $unreadWorkspaceTasks = auth()->user()->unreadWorkspaceTasksCount();
                @endphp
                @if ($unreadWorkspaceTasks > 0)
                    <span id="sidebar-workspace-badge" class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                        {{ $unreadWorkspaceTasks > 99 ? '99+' : $unreadWorkspaceTasks }}
                    </span>
                @endif
            </a>
            
            {{-- NOTIF --}}
            <div class="relative">
                <button id="notifications-btn" title="Notifications"
                    class="p-3 transition-colors rounded-full duration-200 hover:hover:scale-110 text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"></path>
                        </g>
                    </svg>
                </button>
                @include('layouts.notification-panel')
            </div>
        </div>

        {{-- KOLOM 3 --}}
        <div class="flex flex-col gap-2 border border-white bg-white/30 backdrop-blur-3xl w-fit rounded-3xl">
            {{-- PRIVACY --}}
            <a href="{{ route('privacy') }}" title="Privacy"
                class="p-3 transition-colors rounded-full duration-200 hover:hover:scale-110 {{ request()->routeIs('privacy')
                    ? 'bg-[#0E213D] text-[#D5E2F5]'
                    : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">
                
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M21.406,5.086l-9-4a1,1,0,0,0-.812,0l-9,4A1,1,0,0,0,2,6v.7a18.507,18.507,0,0,0,9.515,16.17,1,1,0,0,0,.97,0A18.507,18.507,0,0,0,22,6.7V6A1,1,0,0,0,21.406,5.086ZM20,6.7a16.507,16.507,0,0,1-8,14.141A16.507,16.507,0,0,1,4,6.7V6.65l8-3.556L20,6.65ZM11,10h2v8H11Zm0-4h2V8H11Z"></path></g></svg>
            </a>

            {{-- HELP --}}
            <a href="{{ route('help') }}" title="Help"
                class="p-3 transition-colors rounded-full duration-200 hover:hover:scale-110 {{ request()->routeIs('help')
                    ? 'bg-[#0E213D] text-[#D5E2F5]'
                    : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">
                <svg class="w-7 h-7" fill="currentColor" version="1.1"
                    id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 512 512" xml:space="preserve">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <g>
                            <g>
                                <g>
                                    <path
                                        d="M255.996,384.004c-11.776,0-21.333,9.557-21.333,21.333s9.557,21.333,21.333,21.333c11.776,0,21.333-9.557,21.333-21.333 S267.772,384.004,255.996,384.004z">
                                    </path>
                                    <path
                                        d="M437.016,74.984c-99.979-99.979-262.075-99.979-362.033,0.002c-99.978,99.978-99.978,262.073,0.004,362.031 c99.954,99.978,262.05,99.978,362.029-0.002C536.995,337.059,536.995,174.964,437.016,74.984z M406.848,406.844 c-83.318,83.318-218.396,83.318-301.691,0.004c-83.318-83.299-83.318-218.377-0.002-301.693 c83.297-83.317,218.375-83.317,301.691,0S490.162,323.549,406.848,406.844z">
                                    </path>
                                    <path
                                        d="M271.295,86.684c-53.025-9.308-100.632,31.063-100.632,83.987c0,11.782,9.551,21.333,21.333,21.333 s21.333-9.551,21.333-21.333c0-26.507,23.776-46.67,50.584-41.964c16.882,2.968,31.079,17.165,34.048,34.052 c3.299,18.783-5.487,36.533-21.417,45.315c-26.377,14.544-41.882,43.645-41.882,74.746v37.184 c0,11.782,9.551,21.333,21.333,21.333c11.782,0,21.333-9.551,21.333-21.333V282.82c0-16.217,7.725-30.716,19.816-37.382 c31.705-17.479,49.333-53.091,42.839-90.063C333.906,120.803,305.864,92.761,271.295,86.684z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" title="Log Out"
                    onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) this.closest('form').submit();"
                    class="block p-3 transition-colors rounded-full duration-200 hover:hover:scale-110 {{ request()->routeIs('logout') ? 'bg-red-600 text-white' : 'text-[#717C8F] hover:bg-red-600 hover:text-white' }}">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2 6.5C2 4.01472 4.01472 2 6.5 2H12C14.2091 2 16 3.79086 16 6V7C16 7.55228 15.5523 8 15 8C14.4477 8 14 7.55228 14 7V6C14 4.89543 13.1046 4 12 4H6.5C5.11929 4 4 5.11929 4 6.5V17.5C4 18.8807 5.11929 20 6.5 20H12C13.1046 20 14 19.1046 14 18V17C14 16.4477 14.4477 16 15 16C15.5523 16 16 16.4477 16 17V18C16 20.2091 14.2091 22 12 22H6.5C4.01472 22 2 19.9853 2 17.5V6.5ZM18.2929 8.29289C18.6834 7.90237 19.3166 7.90237 19.7071 8.29289L22.7071 11.2929C23.0976 11.6834 23.0976 12.3166 22.7071 12.7071L19.7071 15.7071C19.3166 16.0976 18.6834 16.0976 18.2929 15.7071C17.9024 15.3166 17.9024 14.6834 18.2929 14.2929L19.5858 13L11 13C10.4477 13 10 12.5523 10 12C10 11.4477 10.4477 11 11 11L19.5858 11L18.2929 9.70711C17.9024 9.31658 17.9024 8.68342 18.2929 8.29289Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                </a>
            </form>
        </div>
    </nav>
</aside>
