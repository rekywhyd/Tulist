<aside class="fixed z-10 flex flex-col items-center h-screen pb-2 pl-6 pt-28 w-fit">

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

            {{-- ROUTE EMPLOYEES --}}
            @if(Auth::user()->isAdmin())
            <a href="{{ route('employees') }}" title="Employees"
                class="p-3 transition-colors rounded-full nav-button duration-200 hover:hover:scale-110
        {{ request()->routeIs('employees')
            ? 'bg-[#0E213D] text-[#D5E2F5]'
            : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">

                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 256 256" id="Flat" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M164,56a12,12,0,0,1,12-12h12V32a12,12,0,0,1,24,0V44h12a12,12,0,0,1,0,24H212V80a12,12,0,0,1-24,0V68H176A12,12,0,0,1,164,56Zm70.66455,54.97949A109.05456,109.05456,0,0,1,236,128a107.73386,107.73386,0,0,1-35.01038,79.52783,11.88547,11.88547,0,0,1-1.6156,1.45166,107.77691,107.77691,0,0,1-142.74328.0044,11.89856,11.89856,0,0,1-1.6261-1.46143A107.94825,107.94825,0,0,1,128,20a109.15124,109.15124,0,0,1,17.02051,1.335Skip a bit...
A12.00039,12.00039,0,0,1,141.26465,45.04,84.959,84.959,0,0,0,128,44,83.93054,83.93054,0,0,0,62.05481,179.9375a83.49358,83.49358,0,0,1,28.969-23.41992,52.00008,52.00008,0,1,1,73.95227,0A83.49788,83.49788,0,0,1,193.945,179.938,83.56278,83.56278,0,0,0,212,128a84.98237,84.98237,0,0,0-1.03955-13.26367,12,12,0,0,1,23.7041-3.75684ZM128,148a28,28,0,1,0-28-28A28.03146,28.03146,0,0,0,128,148Zm0,64a83.51225,83.51225,0,0,0,48.434-15.43359,60.02884,60.02884,0,0,0-96.86816-.00049A83.50931,83.50931,0,0,0,128,212Z"></path> </g></svg>
            </a>
            @endif

        </div>

        {{-- KOLOM 2 --}}
        <div class="flex flex-col gap-2 border border-white w-fit bg-white/30 backdrop-blur-3xl rounded-3xl">
            {{-- NOTIF --}}
            <button id="notifications-btn" title="Notifications"
                class="p-3 transition-colors rounded-full duration-200 hover:hover:scale-110 {{ request()->routeIs('notifications')
                    ? 'bg-[#0E213D] text-[#D5E2F5]'
                    : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z">
                        </path>
                    </g>
                </svg>
            </button>

            {{-- SETTING --}}
            <a href="{{ route('profile.edit') }}"
                class="p-3 transition-colors rounded-full duration-200 hover:hover:scale-110 {{ request()->routeIs('profile.edit')
                    ? 'bg-[#0E213D] text-[#D5E2F5]'
                    : 'text-[#717C8F] hover:bg-[#0E213D] hover:text-[#D5E2F5]' }}"
                title="Profile">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    data-name="Layer 1">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z">
                        </path>
                    </g>
                </svg>
            </a>
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
                    onclick="event.preventDefault(); this.closest('form').submit();"
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

    {{-- Notifications Popup --}}
    <div id="notifications-popup" class="absolute hidden p-4 ml-4 overflow-y-auto bg-white rounded-lg shadow-lg left-full top-32 w-80 max-h-96">
        <h3 class="mb-2 text-lg font-bold">Notifications</h3>
        <div id="notifications-list" class="space-y-2">
            <!-- Notifications will be populated here -->
        </div>
    </div>
</aside>

<script>
    document.getElementById('notifications-btn').addEventListener('click', function() {
        const popup = document.getElementById('notifications-popup');
        const list = document.getElementById('notifications-list');
        popup.classList.toggle('hidden');

        // Populate notifications
        const userId = {{ Auth::id() }};
        const notifications = JSON.parse(localStorage.getItem('notifications_' + userId) || '[]');
        list.innerHTML = '';
        if (notifications.length === 0) {
            list.innerHTML = '<p class="text-gray-500">No notifications</p>';
        } else {
            // Sort notifications: newest first
            notifications.sort((a, b) => new Date(b.date) - new Date(a.date));
            notifications.forEach(notification => {
                const item = document.createElement('div');
                item.className = 'p-2 bg-gray-100 rounded';
                item.innerHTML = `<p>${notification.message}</p><small class="text-gray-500">${new Date(notification.date).toLocaleString()}</small>`;
                list.appendChild(item);
            });
        }

        // Also fetch from server and merge
        fetch('/notifications')
            .then(response => response.json())
            .then(serverNotifications => {
                // Merge with localStorage notifications
                const allNotifications = [...notifications, ...serverNotifications.map(n => ({
                    message: n.message,
                    date: n.created_at
                }))];
                // Remove duplicates and sort
                const uniqueNotifications = allNotifications.filter((n, index, self) => self.findIndex(t => t.message === n.message && t.date === n.date) === index);
                uniqueNotifications.sort((a, b) => new Date(b.date) - new Date(a.date));
                list.innerHTML = '';
                if (uniqueNotifications.length === 0) {
                    list.innerHTML = '<p class="text-gray-500">No notifications</p>';
                } else {
                    uniqueNotifications.forEach(notification => {
                        const item = document.createElement('div');
                        item.className = 'p-2 bg-gray-100 rounded';
                        item.innerHTML = `<p>${notification.message}</p><small class="text-gray-500">${new Date(notification.date).toLocaleString()}</small>`;
                        list.appendChild(item);
                    });
                }
            })
            .catch(console.error);
    });

    // Close popup when clicking outside
    document.addEventListener('click', function(event) {
        const popup = document.getElementById('notifications-popup');
        const btn = document.getElementById('notifications-btn');
        if (!popup.contains(event.target) && !btn.contains(event.target)) {
            popup.classList.add('hidden');
        }
    });
</script>
