<x-app-layout>
    <div class="items-center px-10 py-6 mt-20 ml-20 border-white shadow-md bg-white/50 rounded-[40px]">
        <h1 class="items-center mr-2 text-4xl font-bold text-center text-black font-poppins">Schedule</h1>

        <div class="mx-auto max-w-7xl">
            <!-- Header Section -->
            <div class="px-8 py-4 my-4 bg-white shadow-xl rounded-xl font-poppins">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <h1 class="text-2xl font-bold text-[#1C427A]">
                            {{ strtoupper(\Carbon\Carbon::create($year, $month)->format('F Y')) }}</h1>
                        <div class="flex space-x-2">
                            <button id="prev-month"
                                class="p-2 transition-transform duration-200 bg-gray-100 rounded-xl hover:hover:scale-110 hover:bg-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button id="next-month"
                                class="p-2 transition-transform duration-200 bg-gray-100 rounded-xl hover:hover:scale-110 hover:bg-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="add-task-btn"
                        class="add-task-btn flex items-center gap-2 px-6 py-2 text-sm font-bold font-poppins text-white bg-[#0E213D] shadow-md rounded-3xl focus:outline-none transition-transform duration-200 hover:scale-110">
                        <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 h-5">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill="#ffffff" fill-rule="evenodd"
                                    d="M9 17a1 1 0 102 0v-6h6a1 1 0 100-2h-6V3a1 1 0 10-2 0v6H3a1 1 0 000 2h6v6z">
                                </path>
                            </g>
                        </svg>
                        <span>New Task</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-8">
                <!-- Calendar Grid View -->
                <div id="calendar-view" class="flex-[2] min-w-0 p-8 bg-white shadow-xl rounded-3xl">
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="py-2 font-semibold text-center text-[#1C427A]">{{ $day }}</div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
                            $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
                            $startDate = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $endDate = $endOfMonth->copy()->endOfWeek();
                        @endphp

                        @for ($date = $startDate; $date->lte($endDate); $date->addDay())
                            @php
                                $dateKey = $date->format('Y-m-d');
                                $isCurrentMonth = $date->month == $month;
                                $isToday = $date->isToday();
                                $tasksOnDate = $tasksByDate->get($dateKey, collect());
                                $incompleteTasksOnDate = $tasksOnDate->where('completed', false);
                                $urgentCount = $incompleteTasksOnDate->where('priority', 'Urgent')->count();
                                $highCount = $incompleteTasksOnDate->where('priority', 'High')->count();
                                $normalCount = $incompleteTasksOnDate->where('priority', 'Normal')->count();
                                $lowCount = $incompleteTasksOnDate->where('priority', 'Low')->count();
                                $totalTasks = $incompleteTasksOnDate->count();
                            @endphp
                            <div class="min-h-[100px] border rounded-lg p-2 {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }} {{ $isToday ? 'bg-blue-100 ring-2 ring-blue-500' : '' }} cursor-pointer hover:bg-gray-50 transition-colors date-cell"
                                data-date="{{ $dateKey }}">
                                <div
                                    class="text-sm font-medium {{ $isToday ? 'text-blue-600 text-lg' : ($date->isSunday() ? 'text-red-500' : ($isCurrentMonth ? 'text-gray-900' : 'text-gray-400')) }}">
                                    {{ $date->day }}
                                </div>
                                @if ($totalTasks > 0)
                                    <div class="mt-1 space-y-1">
                                        @if ($urgentCount > 0)
                                            <div class="inline-block w-2 h-2 bg-red-500 rounded-full"></div>
                                        @endif
                                        @if ($highCount > 0)
                                            <div class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></div>
                                        @endif
                                        @if ($normalCount > 0)
                                            <div class="inline-block w-2 h-2 bg-blue-500 rounded-full"></div>
                                        @endif
                                        @if ($lowCount > 0)
                                            <div class="inline-block w-2 h-2 bg-green-500 rounded-full"></div>
                                        @endif
                                        <div class="mt-1 text-xs text-gray-500">{{ $totalTasks }}</div>
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Task List Panel -->
                <div class="flex-1 min-w-0 p-8 bg-white shadow-xl rounded-3xl">
                    <div class="mb-4">
                        <h2 id="task-in-date-title" class="mb-4 text-xl font-bold text-[#1C427A]">
                            Task in <span id="task-in-date-pill"
                                class="px-3 py-1 text-white border border-[#0E213D] rounded-full bg-[#0E213D]">{{ date('d M Y') }}</span>
                        </h2>
                    </div>
                    <div id="task-list" class="space-y-3 overflow-y-auto max-h-[600px] text-[#132C51]">
                        <!-- Tasks loaded via JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="add-task-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-600 bg-opacity-80 font-poppins">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 shadow-xl rounded-xl w-[850px] bg-[#132C51] max-w-full my-8">
                <div>
                    <h3 class="mb-3 text-2xl font-semibold text-white">New Task</h3>
                    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data"
                        class="grid grid-cols-12 gap-y-2 gap-x-6">
                        @csrf

                        <!-- Row 1: Title -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Title</label>
                            </div>
                            <input placeholder="Title Name" type="text" name="title"
                                class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg"
                                required>
                        </div>

                        <!-- Row 2: Description -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Description</label>
                            </div>
                            <textarea placeholder="Add Description" name="description"
                                class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                        </div>

                        <!-- Row 3: Due Date, Times, Priority -->
                        <div class="grid grid-cols-1 col-span-12 gap-6 md:grid-cols-4">
                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">Due Date</label>
                                </div>
                                <input type="date" name="due_date"
                                    class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                                    required value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">Start Time</label>
                                </div>
                                <input type="time" name="start_time"
                                    class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                            </div>

                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">End Time</label>
                                </div>
                                <input type="time" name="end_time"
                                    class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                            </div>

                            <!-- Priority Selection -->
                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 01-2 2zm9-13.5V9">
                                        </path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">Priority</label>
                                </div>
                                <div x-data="{ open: false, selected: null }" @reset-new-task.window="selected = null"
                                    class="relative w-full">
                                    <button @click="open = !open" type="button"
    class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] rounded-lg transition-colors duration-150 border border-gray-300"
    :class="{
        'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
        'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
        'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
        'bg-green-50 border-green-300 text-green-700': selected === 'Low',
        'text-white border-gray-600': selected === null || selected === ''
    }">
    
    <svg class="w-5 h-5"
        :class="{
            'text-red-500': selected === 'Urgent',
            'text-yellow-500': selected === 'High',
            'text-blue-500': selected === 'Normal',
            'text-green-500': selected === 'Low',
            'text-gray-400': selected === null || selected === ''
        }"
        fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
    </svg>
    <span x-text="selected || 'Priority'" class="flex-1 text-current"></span>
</button>

                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="absolute z-10 w-full p-1 mt-1 bg-[#EAF0FA] rounded-xl shadow-xl">
                                        <div @click="selected = 'Urgent'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-black">Urgent</span>
                                        </div>
                                        <div @click="selected = 'High'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-black">High</span>
                                        </div>
                                        <div @click="selected = 'Normal'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-black">Normal</span>
                                        </div>
                                        <div @click="selected = 'Low'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-gray-800">Low</span>
                                        </div>
                                    </div>

                                    <select name="priority" x-model="selected" class="hidden">
                                        <option value="">Priority</option>
                                        <option value="Urgent">Urgent</option>
                                        <option value="High">High</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Row 4: Workspace Selection (Multiple) -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Workspaces</label>
                            </div>
                            <div class="grid grid-cols-2 gap-2 p-3 border border-gray-600 rounded-lg bg-[#0C1F3B]">
                                @foreach ($workspaces as $workspace)
                                    <label class="flex items-start gap-2 cursor-pointer group">
                                        <input type="checkbox" name="workspace_ids[]" value="{{ $workspace->id }}"
                                            class="w-4 h-4 mt-0.5 rounded text-[#1C427A] focus:ring-[#1C427A] bg-gray-700 border-gray-600">
                                        <span
                                            class="text-sm text-gray-300 break-all whitespace-normal group-hover:text-white">{{ $workspace->name }}</span>
                                    </label>
                                @endforeach
                                @if ($workspaces->isEmpty())
                                    <p class="col-span-2 text-xs italic text-gray-500">No workspaces available. Create
                                        one first.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Row 5: Attachments -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mt-2 mb-1">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Attachments</label>
                            </div>
                            <label
                                class="flex items-center justify-center w-full gap-2 px-3 py-1 text-white transition-transform duration-200 border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:scale-[1.01] hover:bg-[#1A365D]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <span id="file-label">Add File</span>
                                <input type="file" name="attachments[]" multiple class="hidden"
                                    id="task-file-input"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            </label>
                            <div id="file-list" class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-300"></div>
                        </div>

                        {{-- Initial Comment Section --}}
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mt-2 mb-1">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Initial Comments (Optional)</label>
                            </div>
                            <div class="relative">
                                <input type="text" name="initial_comment" id="new-task-comment-input"
                                    placeholder="Write the initial comment... Use @ to mention members"
                                    class="w-full px-4 py-2.5 text-sm text-white bg-[#0C1F3B] border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-400/50 transition-all placeholder-gray-500"
                                    autocomplete="off">
                                <div id="new-task-mention-dropdown"
                                    class="absolute bottom-full left-0 z-50 hidden w-full mb-1 overflow-y-auto bg-white shadow-2xl rounded-xl max-h-40 border border-gray-200">
                                </div>
                            </div>
                        </div>

                        <!-- Row 5: Buttons -->
                        <div class="flex justify-center col-span-12 gap-6 mt-4 font-medium">
                            <button type="submit"
                                class="transition-transform duration-200 hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Save</button>
                            <button type="button" id="close-modal"
                                class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 rounded-3xl hover:scale-95">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="edit-task-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-600 bg-opacity-80 font-poppins">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 shadow-xl rounded-xl w-[850px] bg-[#132C51] max-w-full my-8">
                <div>
                    <h3 class="mb-3 text-2xl font-semibold text-white">Edit Task</h3>

                    <form id="edit-task-form" method="POST" action="" enctype="multipart/form-data"
                        class="grid grid-cols-12 gap-y-2 gap-x-6">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="task_id" id="edit-task-id">

                        <!-- Row 1: Title -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Title</label>
                            </div>
                            <input id="edit-title" placeholder="Title Name" type="text" name="title"
                                class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg"
                                required>
                        </div>

                        <!-- Row 2: Description -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Description</label>
                            </div>
                            <textarea id="edit-description" placeholder="Add Description" name="description"
                                class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                        </div>

                        <!-- Row 3: Due Date, Times, Priority -->
                        <div class="grid grid-cols-1 col-span-12 gap-6 md:grid-cols-4">
                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">Due Date</label>
                                </div>
                                <input id="edit-due-date" type="date" name="due_date"
                                    class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                                    required>
                            </div>

                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">Start Time</label>
                                </div>
                                <input id="edit-start-time" type="time" name="start_time"
                                    class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                            </div>

                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">End Time</label>
                                </div>
                                <input id="edit-end-time" type="time" name="end_time"
                                    class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                            </div>

                            <div class="flex flex-col justify-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 01-2 2zm9-13.5V9">
                                        </path>
                                    </svg>
                                    <label class="font-semibold text-gray-100">Priority</label>
                                </div>
                                <div x-data="{ open: false, selected: null }"
                                    @set-edit-priority.window="selected = $event.detail.priority"
                                    class="relative w-full">
                                    <button @click="open = !open" type="button"
    class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] rounded-lg transition-colors duration-150 border border-gray-300"
    :class="{
        'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
        'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
        'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
        'bg-green-50 border-green-300 text-green-700': selected === 'Low',
        'text-white border-gray-600': selected === null || selected === ''
    }">
    
    <svg class="w-5 h-5"
        :class="{
            'text-red-500': selected === 'Urgent',
            'text-yellow-500': selected === 'High',
            'text-blue-500': selected === 'Normal',
            'text-green-500': selected === 'Low',
            'text-gray-400': selected === null || selected === ''
        }"
        fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
    </svg>
    <span x-text="selected || 'Priority'" class="flex-1 text-current"></span>
</button>

                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="absolute z-10 w-full p-1 mt-1 bg-[#EAF0FA] rounded-xl shadow-xl">
                                        <div @click="selected = 'Urgent'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-black">Urgent</span>
                                        </div>
                                        <div @click="selected = 'High'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-black">High</span>
                                        </div>
                                        <div @click="selected = 'Normal'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-black">Normal</span>
                                        </div>
                                        <div @click="selected = 'Low'; open = false"
                                            class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <span class="font-semibold text-gray-800">Low</span>
                                        </div>
                                    </div>

                                    <select id="edit-priority" name="priority" x-model="selected" class="hidden">
                                        <option value="">Priority</option>
                                        <option value="Urgent">Urgent</option>
                                        <option value="High">High</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Workspace Selection (Multiple) -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Workspaces</label>
                            </div>
                            <div id="edit-workspaces-container"
                                class="grid grid-cols-2 gap-2 p-3 border border-gray-600 rounded-lg bg-[#0C1F3B]">
                                @foreach ($workspaces as $workspace)
                                    <label class="flex items-start gap-2 cursor-pointer group">
                                        <input type="checkbox" name="workspace_ids[]" value="{{ $workspace->id }}"
                                            class="w-4 h-4 mt-0.5 rounded text-[#1C427A] focus:ring-[#1C427A] bg-gray-700 border-gray-600 edit-workspace-checkbox">
                                        <span
                                            class="text-sm text-gray-300 break-all whitespace-normal group-hover:text-white">{{ $workspace->name }}</span>
                                    </label>
                                @endforeach
                                @if ($workspaces->isEmpty())
                                    <p class="col-span-2 text-xs italic text-gray-500">No workspaces available. Create
                                        one first.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Row 5: Attachments -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mt-2 mb-1">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Attachments</label>
                            </div>
                            <div id="edit-existing-attachments-wrap" class="hidden mb-4">
                                <div id="edit-existing-attachments" class="grid grid-cols-2 gap-4"></div>
                            </div>

                            <label
                                class="flex items-center justify-center w-full gap-2 px-3 py-1 text-white transition-transform duration-200 border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:scale-[1.01] hover:bg-[#1A365D]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <span id="edit-file-label">Add New File</span>
                                <input type="file" name="attachments[]" multiple class="hidden"
                                    id="edit-task-file-input"
                                    accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            </label>
                            <div id="edit-file-list" class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-300"></div>
                        </div>

                        {{-- Comments Section in Edit Modal --}}
                        <div class="col-span-12 mt-2">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Comment</label>
                                <span id="edit-comment-count"
                                    class="px-2 py-0.5 text-[10px] font-bold bg-cyan-500/20 text-cyan-300 rounded-full"></span>
                            </div>
                            <div id="edit-comments-list" class="space-y-3 overflow-y-auto max-h-[200px] pr-1 mb-3">
                                <p class="text-sm text-gray-500 italic">No comments yet</p>
                            </div>
                            <div class="relative">
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" id="edit-comment-input"
                                            placeholder="Write an initial comment... Use @ to mention members"
                                            class="w-full px-4 py-2.5 text-sm text-white bg-[#0C1F3B] border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-400/50 transition-all placeholder-gray-500"
                                            autocomplete="off">
                                        <div id="edit-mention-dropdown"
                                            class="absolute bottom-full left-0 z-50 hidden w-full mb-1 overflow-y-auto bg-white shadow-2xl rounded-xl max-h-40 border border-gray-200">
                                        </div>
                                    </div>
                                    <button type="button" id="edit-comment-send-btn"
                                        class="px-4 py-2 text-sm font-semibold text-white transition-all bg-cyan-600 rounded-xl hover:bg-cyan-700 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Row 6: Buttons -->
                        <div class="flex justify-center col-span-12 gap-6 mt-4 font-medium">
                            <button type="submit"
                                class="transition-transform duration-200 hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Save</button>
                            <button type="button" id="close-edit-modal"
                                class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 rounded-3xl hover:scale-95">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <!-- Task Details Modal -->
    <div id="task-details-modal"
        class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-600 bg-opacity-80 font-poppins">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 bg-[#132C51] shadow-xl rounded-xl w-[850px] max-w-full my-8">
                <div>
                    <h3 class="mb-3 text-2xl font-semibold text-white">Task Details</h3>

                    <div class="grid grid-cols-12 gap-y-2 gap-x-6">
                        <!-- Row 1: Title -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Title</label>
                            </div>
                            <p id="details-title" class="text-gray-200 break-words"></p>
                        </div>

                        <!-- Row 2: Description -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Description</label>
                            </div>
                            <p id="details-description" class="text-gray-200 break-words"></p>
                        </div>

                        <!-- Row 3: Due Date, Created Date, Completed Date -->
                        <div class="col-span-4">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Due Date</label>
                            </div>
                            <p id="details-due-date" class="text-gray-200"></p>
                        </div>
                        <div class="col-span-4">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-indigo-400 opacity-70" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Created Date</label>
                            </div>
                            <p id="details-created-date" class="text-gray-200"></p>
                        </div>
                        <div class="col-span-4">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-purple-400 opacity-70" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Completed Date</label>
                            </div>
                            <p id="details-completed-at" class="text-gray-200"></p>
                        </div>

                        <!-- Row 3.5: Start Time, End Time -->
                        <div class="col-span-6">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Start Time</label>
                            </div>
                            <p id="details-start-time" class="text-gray-200"></p>
                        </div>
                        <div class="col-span-6">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">End Time</label>
                            </div>
                            <p id="details-end-time" class="text-gray-200"></p>
                        </div>

                        <!-- Row 4: Priority, Status -->
                        <div class="col-span-6">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 01-2 2zm9-13.5V9">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Priority</label>
                            </div>
                            <p id="details-priority" class="font-semibold text-gray-200"></p>
                        </div>
                        <div class="col-span-6">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <label class="font-semibold text-gray-100">Status</label>
                            </div>
                            <p id="details-completed" class="text-gray-200"></p>
                        </div>

                        <!-- Row 5: Workspaces -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Workspaces</label>
                            </div>
                            <div id="details-workspaces" class="flex flex-wrap gap-2 text-sm text-gray-200">
                                <p class="text-gray-400">No workspaces assigned</p>
                            </div>
                        </div>

                        <!-- Row 6: Attachments -->
                        <div class="col-span-12">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Attachments</label>
                            </div>
                            <div id="details-attachments" class="grid grid-cols-2 gap-4 text-sm text-gray-200">
                                <p class="col-span-2 text-gray-400">No attachments</p>
                            </div>
                        </div>

                        {{-- Comments Section --}}
                        <div class="col-span-12 mt-2">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                <label class="font-semibold text-gray-100">Comment</label>
                                <span id="details-comment-count"
                                    class="px-2 py-0.5 text-[10px] font-bold bg-cyan-500/20 text-cyan-300 rounded-full"></span>
                            </div>
                            <div id="details-comments-list" class="space-y-3 overflow-y-auto max-h-[200px] pr-1 mb-3">
                                <p class="text-sm text-gray-500 italic">No comments yet</p>
                            </div>
                            <div class="relative">
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" id="details-comment-input"
                                            placeholder="Write an initial comment... Use @ to mention members"
                                            class="w-full px-4 py-2.5 text-sm text-white bg-[#0C1F3B] border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-400/50 transition-all placeholder-gray-500"
                                            autocomplete="off">
                                        <div id="details-mention-dropdown"
                                            class="absolute bottom-full left-0 z-50 hidden w-full mb-1 overflow-y-auto bg-white shadow-2xl rounded-xl max-h-40 border border-gray-200">
                                        </div>
                                    </div>
                                    <button type="button" id="details-comment-send-btn"
                                        class="px-4 py-2 text-sm font-semibold text-white transition-all bg-cyan-600 rounded-xl hover:bg-cyan-700 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-center col-span-12 gap-4 mt-6 font-medium">
                            <button type="button" id="edit-details-btn"
                                class="px-5 py-1 text-white transition-transform duration-200 bg-[#1C427A] hover:hover:scale-110 rounded-3xl">
                                Edit
                            </button>
                            <button type="button" id="close-details-modal"
                                class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:hover:scale-110 rounded-3xl">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const formatDateTime = (dateStr) => {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            const hh = String(date.getHours()).padStart(2, '0');
            const min = String(date.getMinutes()).padStart(2, '0');
            const ss = String(date.getSeconds()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}, ${hh}:${min}:${ss}`;
        };

        document.addEventListener('DOMContentLoaded', () => {
            window.newTaskFiles = new DataTransfer();
            window.editTaskFiles = new DataTransfer();
        });

        let currentMonth = {{ $month }};
        let currentYear = {{ $year }};
        let selectedDate = null;
        let currentTaskIdForComments = null;

        const priorityColors = {
            'Urgent': '#DC2626',
            'High': '#F59E0B',
            'Normal': '#3B82F6',
            'Low': '#10B981'
        };

        let allTasks = @json($allTasks->keyBy('id'));
        let todayTasks = @json($todayTasks->keyBy('id'));
        let upcomingTasks = @json($upcomingTasks->keyBy('id'));
        let completedTasks = @json($completedTasks->keyBy('id'));

        function loadTasks(date = null) {
            const taskList = document.getElementById('task-list');
            const targetDate = date || new Date().toISOString().slice(0, 10);

            fetch(`/tasks?date=${targetDate}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    displayTasks(data, taskList);
                    const pillEl = document.getElementById('task-in-date-pill');
                    if (pillEl) {
                        const pretty = new Date(targetDate).toLocaleDateString('en-US', {
                            weekday: 'short',
                            year: 'numeric',
                            month: 'short',
                            day: '2-digit'
                        });
                        pillEl.textContent = `${pretty}`;
                    }
                })
                .catch(error => {
                    console.error('Error loading tasks:', error);
                    taskList.innerHTML = '<div class="p-3 text-[#132C51] text-center">No tasks on this date!</div>';
                });
        }

        function updateTaskArrays(taskId, completed) {
            if (allTasks[taskId]) {
                allTasks[taskId].completed = completed;
                if (completed) {
                    delete todayTasks[taskId];
                    delete upcomingTasks[taskId];
                    completedTasks[taskId] = allTasks[taskId];
                } else {
                    delete completedTasks[taskId];
                    const dueDate = allTasks[taskId].due_date;
                    if (dueDate && new Date(dueDate).toDateString() === new Date().toDateString()) {
                        todayTasks[taskId] = allTasks[taskId];
                    }
                }
            }
        }

        function addTaskToUI(task) {
            allTasks[task.id] = task;
            loadTasks(selectedDate);
        }

        function displayTasks(tasks, container) {
            container.innerHTML = '';
            if (tasks.length === 0) {
                container.innerHTML = '<div class="p-3 text-[#132C51] text-center">No tasks on this date!</div>';
                return;
            }

            const priorityOrder = {
                'Urgent': 1,
                'High': 2,
                'Normal': 3,
                'Low': 4
            };
            tasks.sort((a, b) => (priorityOrder[a.priority] || 99) - (priorityOrder[b.priority] || 99));

            tasks.forEach(task => {
                const taskDiv = document.createElement('div');
                taskDiv.className =
                    'p-4 bg-white transition-all duration-200 border shadow-sm rounded-2xl border-gray-100 group hover:border-blue-200';
                taskDiv.setAttribute('data-task-id', task.id);

                let isOverdue = false;
                if (!task.completed && task.due_date) {
                    const dueDate = new Date(task.due_date);
                    const now = new Date();
                    if (dueDate.toDateString() === now.toDateString() && task.end_time) {
                        const [h, m, s] = task.end_time.split(':');
                        const taskTime = new Date();
                        taskTime.setHours(parseInt(h), parseInt(m), parseInt(s || 0), 0);
                        if (now > taskTime) isOverdue = true;
                    } else if (dueDate < new Date(now.toDateString())) {
                        isOverdue = true;
                    }
                }

                taskDiv.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1 min-w-0 mr-4">
                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                            <div class="flex-1 min-w-0 ml-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-lg font-medium break-all whitespace-normal ${task.completed ? 'line-through text-gray-500 opacity-50' : (isOverdue ? 'text-red-600' : 'text-[#132C51]')} cursor-pointer hover:text-blue-600 transition-colors title-click-trigger" data-task="${task.id}">
                                        ${task.title}
                                    </span>
                                    ${task.priority ? `
                                            <svg class="flex-shrink-0 w-5 h-5 ${task.priority === 'Urgent' ? 'text-red-500' : (task.priority === 'High' ? 'text-yellow-500' : (task.priority === 'Normal' ? 'text-blue-500' : 'text-green-500'))} ${task.completed ? 'opacity-50' : ''}" fill="currentColor" viewBox="0 0 24 24">
                                                <title>${task.priority}</title>
                                                <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
                                            </svg>
                                        ` : ''}
                                    ${task.workspaces && task.workspaces.length > 0 ? 
                                        task.workspaces.map(ws => `
                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] shadow-sm border border-[#1C427A]/10 ${task.completed ? 'opacity-50' : ''}" title="${ws.name}">
                                                ${ws.name.substring(0, 2).toUpperCase()}
                                            </div>
                                        `).join('') : ''
                                    }
                                </div>
                                ${task.start_time || task.end_time ? `
                                        <div class="flex items-center gap-1.5 text-[11px] text-gray-400 mt-0.5 ${task.completed ? 'line-through opacity-50' : ''}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>${task.start_time ? task.start_time.substring(0,5) : ''} ${task.end_time ? '-' + task.end_time.substring(0,5) : ''}</span>
                                        </div>
                                    ` : ''}
                            </div>
                        </div>
                        <div class="relative ml-2">
                            <button class="text-gray-500 task-menu-btn hover:text-gray-700 p-1" data-task="${task.id}">⋯</button>
                            <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="${task.id}">
                                <button type="button" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 menu-details-trigger" data-task="${task.id}">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Details
                                </button>
                                ${task.can_modify ? `
                                        <button type="button" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 menu-edit-trigger" data-task="${task.id}">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </button>
                                        <button type="button" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 menu-duplicate-trigger" data-task="${task.id}">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                            Duplicate
                                        </button>
                                        <button type="button" class="flex items-center w-full gap-3 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 menu-delete-trigger" data-task="${task.id}">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Delete
                                        </button>
                                    ` : ''}
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(taskDiv);
            });
        }

        // =====================================================
        // CENTRALIZED EVENT DELEGATION
        // =====================================================

        // 1. Handle Klik di seluruh container Task List panel dinamis
        document.addEventListener('click', function(e) {
            // Dropdown triggers (Titik tiga)
            const taskMenuBtn = e.target.closest('.task-menu-btn');
            if (taskMenuBtn) {
                e.preventDefault();
                e.stopPropagation();
                const taskId = taskMenuBtn.getAttribute('data-task');
                const targetMenu = document.querySelector(`.task-menu[data-task="${taskId}"]`);
                document.querySelectorAll('.task-menu').forEach(m => {
                    if (m !== targetMenu) m.classList.add('hidden');
                });
                if (targetMenu) targetMenu.classList.toggle('hidden');
                return;
            }

            // Menu Details & Title click trigger
            const titleTrigger = e.target.closest('.title-click-trigger');
            const menuDetailsTrigger = e.target.closest('.menu-details-trigger');
            if (titleTrigger || menuDetailsTrigger) {
                e.preventDefault();
                e.stopPropagation();
                const taskId = (titleTrigger || menuDetailsTrigger).getAttribute('data-task');
                if (taskId) window.showTaskDetails(taskId);
                return;
            }

            // Menu Edit Trigger
            const editTrigger = e.target.closest('.menu-edit-trigger');
            if (editTrigger) {
                e.preventDefault();
                e.stopPropagation();
                const taskId = editTrigger.getAttribute('data-task');
                if (taskId) triggerEditModalFlow(taskId);
                return;
            }

            // Menu Duplicate Trigger
            const duplicateTrigger = e.target.closest('.menu-duplicate-trigger');
            if (duplicateTrigger) {
                e.preventDefault();
                e.stopPropagation();
                const taskId = duplicateTrigger.getAttribute('data-task');
                if (taskId) {
                    hideMenus();
                    fetch(`/tasks/${taskId}/duplicate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload());
                }
                return;
            }

            // Menu Delete Trigger (Buka konfirmasi modal)
            const deleteTrigger = e.target.closest('.menu-delete-trigger');
            if (deleteTrigger) {
                e.preventDefault();
                e.stopPropagation();
                const taskId = deleteTrigger.getAttribute('data-task');
                if (taskId && confirm('Are you sure you want to delete this task?')) {
                    hideMenus();
                    fetch(`/tasks/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        const taskDiv = document.querySelector(`[data-task-id="${taskId}"]`);
                        if (taskDiv) taskDiv.remove();
                        delete allTasks[taskId];
                    });
                }
                return;
            }

            // Task Completion (Checkbox)
            if (e.target.classList.contains('task-checkbox')) {
                const checkbox = e.target;
                const taskId = checkbox.dataset.id;
                const isCompleted = checkbox.checked;
                const actionText = isCompleted ? 'complete' : 'uncomplete';

                if (!confirm(`Are you sure you want to mark this task as ${actionText}?`)) {
                    checkbox.checked = !isCompleted; // revert
                    return;
                }

                fetch(`/tasks/${taskId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            completed: isCompleted
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateTaskArrays(taskId, isCompleted);
                            loadTasks(selectedDate);
                        } else {
                            alert('You cannot update this task.');
                            checkbox.checked = !isCompleted; // revert
                        }
                    }).catch(err => {
                        console.error(err);
                        checkbox.checked = !isCompleted; // revert
                    });
                return;
            }

            // Tutup task-menu dropdown jika klik sembarang di luar menu dropdown
            if (!e.target.closest('.task-menu')) hideMenus();
        });

        // 2. Logic Pengisian Form Edit Data
        function triggerEditModalFlow(taskId) {
            hideMenus();
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    currentTaskIdForComments = taskId;
                    document.getElementById('edit-comment-input').value = '';
                    loadComments(taskId, 'edit-comments-list', 'edit-comment-count');

                    document.getElementById('edit-task-id').value = data.id;
                    document.getElementById('edit-title').value = data.title || '';
                    document.getElementById('edit-description').value = data.description || '';
                    document.getElementById('edit-due-date').value = data.due_date || '';
                    document.getElementById('edit-start-time').value = data.start_time ? data.start_time.substring(0,
                        5) : '';
                    document.getElementById('edit-end-time').value = data.end_time ? data.end_time.substring(0, 5) : '';

                    const prSelect = document.getElementById('edit-priority');
                    if (prSelect) prSelect.value = data.priority || 'Normal';

                    window.dispatchEvent(new CustomEvent('set-edit-priority', {
                        detail: {
                            priority: data.priority || 'Normal'
                        }
                    }));

                    window.editTaskFiles = new DataTransfer();
                    document.getElementById('edit-file-list').innerHTML = '';
                    document.getElementById('edit-file-label').textContent = 'Add New File';

                    const existingWrap = document.getElementById('edit-existing-attachments-wrap');
                    const existingContainer = document.getElementById('edit-existing-attachments');
                    if (existingWrap && existingContainer) {
                        const attachments = data.attachments || [];
                        if (attachments.length) {
                            existingWrap.classList.remove('hidden');
                            existingContainer.innerHTML = attachments.map(att => `
                                <div class="flex items-center justify-between p-2 bg-[#1A365D] border border-gray-600 rounded-xl">
                                    <span class="text-gray-200 text-xs truncate max-w-[180px]">📎 ${att.original_name || att.filename}</span>
                                    <button type="button" class="text-red-400 font-bold px-1 hover:text-red-500" onclick="
                                        this.closest('.flex').style.display = 'none';
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = 'remove_attachments[]';
                                        input.value = '${att.id}';
                                        this.parentNode.appendChild(input);
                                    ">&times;</button>
                                </div>
                            `).join('');
                        } else existingWrap.classList.add('hidden');
                    }

                    const workspaceCheckboxes = document.querySelectorAll('.edit-workspace-checkbox');
                    workspaceCheckboxes.forEach(cb => cb.checked = false);
                    if (data.workspaces && Array.isArray(data.workspaces)) {
                        data.workspaces.forEach(ws => {
                            const cb = document.querySelector(`.edit-workspace-checkbox[value="${ws.id}"]`);
                            if (cb) cb.checked = true;
                        });
                    }

                    document.getElementById('edit-task-modal').classList.remove('hidden');
                }).catch(err => console.error(err));
        }

        // 3. Logic Detail Task View
        const viewedTasksThisSession = new Set();
        window.showTaskDetails = function(taskId) {
            if (!taskId) return;
            hideMenus();
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    if (!viewedTasksThisSession.has(taskId)) {
                        viewedTasksThisSession.add(taskId);
                        const sidebarBadge = document.getElementById('sidebar-workspace-badge');
                        if (sidebarBadge) {
                            let count = parseInt(sidebarBadge.textContent);
                            if (!isNaN(count) && count > 0) {
                                count--;
                                if (count === 0) {
                                    sidebarBadge.remove();
                                } else {
                                    sidebarBadge.textContent = count > 99 ? '99+' : count;
                                }
                            }
                        }
                    }

                    currentTaskIdForComments = taskId;
                    document.getElementById('details-comment-input').value = '';
                    loadComments(taskId, 'details-comments-list', 'details-comment-count');

                    document.getElementById('details-title').textContent = task.title ?? '';
                    document.getElementById('details-description').textContent = task.description ??
                        'No description provided';
                    document.getElementById('details-due-date').textContent = task.due_date ?? 'N/A';
                    document.getElementById('details-created-date').textContent = formatDateTime(task.created_at);
                    document.getElementById('details-start-time').textContent = task.start_time ? task.start_time
                        .substring(0, 5) : '-';
                    document.getElementById('details-end-time').textContent = task.end_time ? task.end_time
                        .substring(0, 5) : '-';

                    let compDate = task.completed_at || task.complated_at || '';
                    document.getElementById('details-completed-at').textContent = compDate ? formatDateTime(
                        compDate) : '-';
                    const detailsPriority = document.getElementById('details-priority');
                    if (detailsPriority) {
                        const pr = task.priority ?? '';
                        let textClass = 'text-gray-400';
                        if (pr === 'Urgent') textClass = 'text-red-500';
                        else if (pr === 'High') textClass = 'text-yellow-500';
                        else if (pr === 'Normal') textClass = 'text-blue-500';
                        else if (pr === 'Low') textClass = 'text-green-500';

                        if (pr) {
                            detailsPriority.innerHTML = `
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-5 h-5 ${textClass}" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                    <span class="${textClass} font-semibold">${pr}</span>
                                </div>
                            `;
                        } else {
                            detailsPriority.textContent = '-';
                        }
                    }

                    document.getElementById('details-completed').innerHTML = task.completed ?
                        '<span class="text-green-500 font-bold">Completed</span>' :
                        '<span class="text-red-500 font-bold">Not Completed</span>';

                    // Workspaces display
                    const detailsWorkspaces = document.getElementById('details-workspaces');
                    if (detailsWorkspaces) {
                        if (task.workspaces && task.workspaces.length > 0) {
                            detailsWorkspaces.innerHTML = task.workspaces.map(ws =>
                                `<span class="px-2 py-0.5 bg-teal-900/50 text-teal-300 border border-teal-500/30 rounded-full text-xs break-all whitespace-normal">${ws.name}</span>`
                            ).join('');
                        } else {
                            detailsWorkspaces.innerHTML = '<p class="text-gray-400">No workspaces assigned</p>';
                        }
                    }

                    // Attachments display
                    const detailsAttachments = document.getElementById('details-attachments');
                    if (detailsAttachments) {
                        const attachments = Array.isArray(task.attachments) ? task.attachments : [];
                        if (!attachments.length) {
                            detailsAttachments.innerHTML = '<p class="col-span-2 text-gray-400">No attachments</p>';
                        } else {
                            detailsAttachments.innerHTML = attachments.map(att => {
                                const originalName = att.original_name || att.filename || 'Attachment';
                                const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                const isImage = att.mime_type && att.mime_type.startsWith('image/') && att
                                    .storage_path;
                                const imgHtml = isImage ?
                                    `<img src="${path}" class="flex-shrink-0 object-cover w-12 h-12 rounded-md">` :
                                    '';
                                return `
                                    <a href="${path}" target="_blank" class="flex items-center gap-4 p-3 bg-[#1A365D] rounded-xl border border-gray-600 shadow-sm hover:bg-[#254A7A] transition-colors group">
                                        ${imgHtml}
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium text-gray-200 truncate group-hover:text-blue-400" title="${originalName}">${originalName}</div>
                                            <div class="mt-1 text-xs text-gray-400">${att.mime_type || att.type || ''}</div>
                                        </div>
                                    </a>
                                `;
                            }).join('');
                        }
                    }

                    const editDetailsBtn = document.getElementById('edit-details-btn');
                    if (editDetailsBtn) {
                        editDetailsBtn.dataset.taskId = String(taskId);
                        if ((task.can_modify === true || task.can_modify === 1) && !task.completed) {
                            editDetailsBtn.classList.remove('hidden');
                        } else editDetailsBtn.classList.add('hidden');
                    }
                    document.getElementById('task-details-modal').classList.remove('hidden');
                });
        };

        // Global listener for search details
        window.addEventListener('open-task-details', (e) => {
            if (typeof window.showTaskDetails === 'function') {
                window.showTaskDetails(e.detail.taskId);
            }
        });

        // Handle URL param open_task
        const urlParams = new URLSearchParams(window.location.search);
        const openTaskId = urlParams.get('open_task');
        if (openTaskId) {
            window.showTaskDetails(openTaskId);
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // Modals Action Open/Close Bindings
        document.getElementById('add-task-btn').addEventListener('click', () => {
            const modal = document.getElementById('add-task-modal');
            modal.querySelector('form').reset();
            document.getElementById('file-list').innerHTML = '';
            window.newTaskFiles = new DataTransfer();
            window.dispatchEvent(new CustomEvent('reset-new-task'));
            modal.classList.remove('hidden');
        });

        document.getElementById('close-modal').addEventListener('click', () => document.getElementById('add-task-modal')
            .classList.add('hidden'));
        document.getElementById('close-edit-modal').addEventListener('click', () => document.getElementById(
            'edit-task-modal').classList.add('hidden'));
        document.getElementById('close-details-modal').addEventListener('click', () => document.getElementById(
            'task-details-modal').classList.add('hidden'));


        // Form Submit Edit
        document.getElementById('edit-task-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('edit-task-id').value;
            fetch(`/tasks/${taskId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': 'PATCH'
                    },
                    body: new FormData(this)
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTaskArrays(taskId, data.task.completed);
                        loadTasks(selectedDate);
                        document.getElementById('edit-task-modal').classList.add('hidden');
                    }
                });
        });

        // Modal Edit details-btn redirect router
        document.getElementById('edit-details-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const taskId = this.dataset.taskId;
            if (!taskId) return;
            document.getElementById('task-details-modal').classList.add('hidden');
            triggerEditModalFlow(taskId);
        });

        // Window klik overlay terluar untuk menutup modal
        window.addEventListener('click', (e) => {
            const modals = ['add-task-modal', 'edit-task-modal', 'task-details-modal'];
            modals.forEach(id => {
                const modal = document.getElementById(id);
                if (e.target === modal) modal.classList.add('hidden');
            });
        });

        function hideMenus() {
            document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
        }

        // Calendar Grid Engine
        document.querySelectorAll('.date-cell').forEach(cell => {
            cell.addEventListener('click', () => {
                selectedDate = cell.dataset.date;
                document.querySelectorAll('.date-cell').forEach(c => c.classList.remove('bg-blue-100',
                    'border-blue-500', 'ring-2', 'ring-blue-500'));
                cell.classList.add('bg-blue-100', 'border-blue-500', 'ring-2', 'ring-blue-500');
                loadTasks(selectedDate);
            });
        });

        // =====================================================
        // COMMENTS SYSTEM ASYNC CALLS ENGINE
        // =====================================================
        async function loadComments(taskId, listElId, countElId) {
            const listEl = document.getElementById(listElId);
            const countEl = document.getElementById(countElId);
            if (!listEl) return;
            try {
                const res = await fetch(`/tasks/${taskId}/comments`);
                const comments = await res.json();
                if (comments.length === 0) {
                    listEl.innerHTML = '<p class="text-sm italic text-gray-500">No comments yet</p>';
                    if (countEl) countEl.textContent = '0';
                } else {
                    listEl.innerHTML = comments.map(renderCommentHtml).join('');
                    if (countEl) countEl.textContent = comments.length;
                    listEl.scrollTop = listEl.scrollHeight;
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderCommentHtml(comment) {
            const photoPath = comment.user.profile_photo_path ? `/storage/${comment.user.profile_photo_path}` : null;
            const avatar = photoPath ? `<img src="${photoPath}" class="w-8 h-8 rounded-full object-cover">` :
                `<div class="w-8 h-8 rounded-full bg-[#1C427A] flex items-center justify-center text-xs font-bold text-white">${comment.user.name.charAt(0).toUpperCase()}</div>`;
            return `
                <div class="flex items-start gap-3 py-1">
                    ${avatar}
                    <div class="flex-1 min-w-0">
                        <div class="text-xs text-gray-400 flex justify-between">
                            <span class="font-semibold text-white">${comment.user.name}</span>
                            <span>${comment.created_at}</span>
                        </div>
                        <p class="text-sm text-gray-300 mt-0.5 break-words">${comment.body}</p>
                    </div>
                </div>
            `;
        }

        function setupCommentSend(sendBtnId, inputId, listElId, countElId) {
            const sendBtn = document.getElementById(sendBtnId);
            const input = document.getElementById(inputId);
            if (!sendBtn || !input) return;

            input.addEventListener('input', function() {
                sendBtn.disabled = !this.value.trim();
            });

            sendBtn.addEventListener('click', async function() {
                const body = input.value.trim();
                if (!body || !currentTaskIdForComments) return;
                try {
                    const res = await fetch(`/tasks/${currentTaskIdForComments}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            body: body
                        })
                    });
                    if (res.ok) {
                        input.value = '';
                        loadComments(currentTaskIdForComments, 'details-comments-list',
                            'details-comment-count');
                        loadComments(currentTaskIdForComments, 'edit-comments-list', 'edit-comment-count');
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        }

        setupCommentSend('details-comment-send-btn', 'details-comment-input', 'details-comments-list',
            'details-comment-count');
        setupCommentSend('edit-comment-send-btn', 'edit-comment-input', 'edit-comments-list', 'edit-comment-count');

        // File upload preview handling (Synchronized with home.blade.php)
        const fileInput = document.getElementById('task-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const fileList = document.getElementById('file-list');
                const label = document.getElementById('file-label');

                if (!window.newTaskFiles) window.newTaskFiles = new DataTransfer();

                Array.from(this.files).forEach(file => {
                    window.newTaskFiles.items.add(file);
                });
                this.files = window.newTaskFiles.files;

                fileList.innerHTML = '';
                if (this.files.length > 0) {
                    label.textContent = this.files.length + ' file(s) selected';
                    Array.from(this.files).forEach((f, index) => {
                        const div = document.createElement('div');
                        div.className = 'flex justify-between items-center text-sm mt-1';

                        const nameSpan = document.createElement('span');
                        nameSpan.textContent = '📎 ' + f.name;

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.className =
                            'text-red-400 hover:text-red-600 font-bold ml-2 text-lg leading-none';
                        removeBtn.onclick = (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            const dt = new DataTransfer();
                            Array.from(window.newTaskFiles.files).forEach((file, i) => {
                                if (i !== index) dt.items.add(file);
                            });
                            window.newTaskFiles = dt;
                            fileInput.files = window.newTaskFiles.files;
                            fileInput.dispatchEvent(new Event('change'));
                        };

                        div.appendChild(nameSpan);
                        div.appendChild(removeBtn);
                        fileList.appendChild(div);
                    });
                } else {
                    label.textContent = 'Add File';
                }
            });
        }

        // Setup Global Mention Input for all modals
        async function setupGlobalMentionInput(inputId, dropdownId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            if (!input || !dropdown) return;

            let globalSuggestions = [];
            try {
                const res = await fetch('/global-mention-suggestions');
                globalSuggestions = await res.json();
            } catch (err) {
                console.error('Error loading global mention suggestions:', err);
            }

            input.addEventListener('input', function() {
                const cursorPos = this.selectionStart;
                const textBeforeCursor = this.value.substring(0, cursorPos);
                const atIndex = textBeforeCursor.lastIndexOf('@');

                if (atIndex !== -1 && (atIndex === 0 || textBeforeCursor[atIndex - 1] === ' ')) {
                    const query = textBeforeCursor.substring(atIndex + 1).toLowerCase();
                    const filtered = globalSuggestions.filter(m => m.name.toLowerCase().includes(query));

                    if (filtered.length > 0) {
                        dropdown.innerHTML = filtered.map(m => {
                            const photo = m.profile_photo_path ? `/storage/${m.profile_photo_path}` :
                                null;
                            const avatar = photo ?
                                `<img src="${photo}" class="flex-shrink-0 object-cover w-7 h-7 rounded-full">` :
                                `<div class="flex items-center justify-center flex-shrink-0 w-7 h-7 text-xs font-bold rounded-full bg-[#E8EEF9] text-[#1C427A]">${m.name.charAt(0).toUpperCase()}</div>`;
                            return `<div class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-blue-50 transition-colors mention-option" data-name="${m.name}">
                                ${avatar}
                                <span class="text-sm font-medium text-[#132C51]">${m.name}</span>
                            </div>`;
                        }).join('');
                        dropdown.classList.remove('hidden');
                    } else dropdown.classList.add('hidden');
                } else dropdown.classList.add('hidden');
            });

            dropdown.addEventListener('click', function(e) {
                const option = e.target.closest('.mention-option');
                if (!option) return;
                const name = option.dataset.name;
                const cursorPos = input.selectionStart;
                const textBeforeCursor = input.value.substring(0, cursorPos);
                const atIndex = textBeforeCursor.lastIndexOf('@');
                const textAfterCursor = input.value.substring(cursorPos);
                input.value = textBeforeCursor.substring(0, atIndex) + '@' + name + ' ' + textAfterCursor;
                dropdown.classList.add('hidden');
                input.focus();
                const newPos = atIndex + name.length + 2;
                input.setSelectionRange(newPos, newPos);
                input.dispatchEvent(new Event('input')); // Trigger input event to update Send button state
            });
        }

        setupGlobalMentionInput('new-task-comment-input', 'new-task-mention-dropdown');
        setupGlobalMentionInput('details-comment-input', 'details-mention-dropdown');
        setupGlobalMentionInput('edit-comment-input', 'edit-mention-dropdown');

        // File upload preview handling for Edit Modal
        const editFileInput = document.getElementById('edit-task-file-input');
        if (editFileInput) {
            editFileInput.addEventListener('change', function() {
                const fileList = document.getElementById('edit-file-list');
                const label = document.getElementById('edit-file-label');

                if (!window.editTaskFiles) window.editTaskFiles = new DataTransfer();

                Array.from(this.files).forEach(file => {
                    window.editTaskFiles.items.add(file);
                });
                this.files = window.editTaskFiles.files;

                fileList.innerHTML = '';
                if (this.files.length > 0) {
                    label.textContent = this.files.length + ' file(s) selected';
                    Array.from(this.files).forEach((f, index) => {
                        const div = document.createElement('div');
                        div.className =
                            'flex justify-between items-center text-sm mt-1 bg-[#1A365D] p-2 rounded-lg border border-gray-600';

                        const nameSpan = document.createElement('span');
                        nameSpan.className = 'truncate max-w-[180px]';
                        nameSpan.textContent = '📎 ' + f.name;

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.className =
                            'text-red-400 hover:text-red-600 font-bold ml-2 text-lg leading-none';
                        removeBtn.onclick = (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            const dt = new DataTransfer();
                            Array.from(window.editTaskFiles.files).forEach((file, i) => {
                                if (i !== index) dt.items.add(file);
                            });
                            window.editTaskFiles = dt;
                            editFileInput.files = window.editTaskFiles.files;
                            editFileInput.dispatchEvent(new Event('change'));
                        };

                        div.appendChild(nameSpan);
                        div.appendChild(removeBtn);
                        fileList.appendChild(div);
                    });
                } else {
                    label.textContent = 'Add New File';
                }
            });
        }

        loadTasks(null);
    </script>
</x-app-layout>
