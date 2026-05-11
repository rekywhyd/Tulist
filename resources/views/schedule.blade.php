<x-app-layout>
    <div class="min-h-full items-center mr-8 px-12 pt-6 mt-20 ml-20 border-white shadow-md bg-white/50 rounded-[40px]">
        <h1 class="items-center mr-2 text-4xl font-bold text-center text-black font-poppins">Schedule</h1>

        <div class="mx-auto max-w-7xl">
            <!-- Header Section -->
            <div class="p-6 mt-8 mb-8 bg-white shadow-xl rounded-xl font-poppins">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
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
                    <div class="flex items-center space-x-3 text-sm">
                        <button id="toggle-view"
                            class="px-4 py-2 text-white transition-transform duration-200 bg-[##5F6E84] hover:hover:scale-110 rounded-3xl">
                            <span id="view-text">Calendar View</span>
                        </button>
                        <button id="add-task-btn"
                            class="px-4 py-2 text-white transition-transform duration-200 bg-[#0E213D] hover:hover:scale-110 rounded-3xl">
                            + New Task
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-6">

                <!-- Calendar Grid View -->
                <div id="calendar-view" class="w-[60%] p-6 bg-white shadow-xl rounded-xl">
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="py-2 font-semibold text-center text-[#1C427A]">{{ $day }}</div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
                            $endOfMonth = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
                            $startDate = $startOfMonth->copy()->startOfWeek(
                                \Carbon\Carbon::SUNDAY
                            );
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
                            <div class="min-h-[100px] border rounded-lg p-2 {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }} {{ $isToday ? 'bg-blue-100' : '' }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }} cursor-pointer hover:bg-gray-50 transition-colors date-cell"
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
                <div class="w-[37%] p-6 bg-white shadow-xl rounded-xl">
                    <div class="mb-4">
                        <h2 id="task-in-date-title" class="mb-4 text-2xl font-bold text-[#1C427A]">
                            Task in <span id="task-in-date-pill" class="px-3 py-1 border border-[#1C427A]/30 rounded-full bg-white">{{ date('d M Y') }}</span>
                        </h2>

                    </div>


                    <div id="task-list" class="space-y-3 overflow-y-auto text-[#132C51]">
                        <!-- Tasks will be loaded here via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include existing modals from view --}}
    <div id="add-task-modal"
        class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 font-poppins bg-opacity-80">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 bg-[#132C51] shadow-xl rounded-xl w-[600px]">
            <div class="mt-3">
                <h3 class="mb-8 text-2xl font-semibold text-white">New Task</h3>
                <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-5">
                        <input placeholder="Title Name" type="text" name="title"
                            class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <textarea placeholder="Add Description" name="description"
                            class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                    </div>

                    {{-- 2 in 1 --}}
                    <div class="flex flex-wrap justify-center gap-2 mb-5">

                        {{-- DATE --}}
                        <input type="date" name="due_date"
                            class="w-[49%] text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                            required value="{{ date('Y-m-d') }}">

                        {{-- PRIORITY --}}
                        <div x-data="{ open: false, selected: null }" class="relative w-[49%]">

                            <button @click="open = !open" type="button"
                                class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] border border-gray-300 rounded-lg"
                                :class="{
                                    'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
                                    'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
                                    'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
                                    'bg-green-50 border-green-300 text-green-700': selected === 'Low',
                                    'text-white border-gray-600': selected === null /* Warna teks default saat null */
                                }">

                                <svg class="w-5 h-5"
                                    :class="{
                                        'text-red-500': selected === 'Urgent',
                                        'text-yellow-500': selected === 'High',
                                        'text-blue-500': selected === 'Normal',
                                        'text-green-500': selected === 'Low',
                                        'text-gray-400': selected === null
                                    }"
                                    fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                            fill="currentColor"></path>
                                    </g>
                                </svg>

                                <span x-text="selected || 'Priority'" class="flex-1"></span>


                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute z-10 w-full p-1 mt-1 bg-[#EAF0FA] rounded-xl shadow-xl">

                                <div @click="selected = 'Urgent'; open = false"
                                    class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                    <span class="font-semibold text-black">Urgent</span>
                                </div>

                                <div @click="selected = 'High'; open = false"
                                    class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                    <span class="font-semibold text-black">High</span>
                                </div>

                                <div @click="selected = 'Normal'; open = false"
                                    class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                    <span class="font-semibold text-black">Normal</span>
                                </div>

                                <div @click="selected = 'Low'; open = false"
                                    class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                    <span class="font-semibold text-gray-800">Low</span>
                                </div>

                                <div @click="selected = null; open = false"
                                    class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                    <span class="font-semibold text-black">Clear</span>
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


                    {{-- Add File --}}
                    <div class="mb-5">
                        <label class="flex items-center justify-center w-full gap-2 px-3 py-2 text-white transition-transform duration-200 border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="file-label">Add File</span>
                            <input type="file" name="attachments[]" multiple class="hidden" id="task-file-input" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        </label>
                        <div id="file-list" class="mt-2 space-y-1 text-sm text-gray-300"></div>
                    </div>
                    <div class="flex justify-center gap-6 mt-8 font-medium">
                        <button type="button" id="close-modal"
                            class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 rounded-3xl hover:hover:scale-95">Cancel</button>
                        <button type="submit"
                            class="transition-transform duration-200 hover:hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="confirm-modal"
        class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50 font-poppins">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 mx-auto bg-[#132C51] rounded-xl shadow-xl w-[500px]">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-semibold text-white">Are you sure you want to complete this task?</h3>
                <div class="flex justify-center gap-6 mt-6 font-medium">
                    <button id="confirm-no"
                        class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:hover:scale-95 rounded-3xl">No</button>
                    <button id="confirm-yes"
                        class="transition-transform duration-200 hover:hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="rename-modal"
        class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50 font-poppins">
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 mx-auto rounded-xl shadow-xl w-96 bg-[#132C51]">
            <div class="mt-3">
                <h3 class="mb-4 text-lg font-semibold text-white">Rename Task</h3>
                <form id="rename-form">
                    @csrf
                    <input type="hidden" id="rename-task-id">
                    <div class="mb-4">
                        <input type="text" id="rename-title"
                            class="w-full px-3 py-2 text-white border border-gray-600 rounded-xl mb-2 bg-[#0C1F3B]"
                            required>
                    </div>
                    <div class="flex justify-center gap-6 mt-6 font-medium">
                        <button type="button" id="close-rename-modal"
                            class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:hover:scale-95 rounded-3xl">Cancel</button>
                        <button type="submit"
                            class="transition-transform duration-200 hover:hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Rename</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <div id="delete-confirm-modal"
        class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
        <div
            class="absolute p-5 mx-auto -translate-x-1/2 -translate-y-1/2 bg-[#132C51] shadow-xl top-1/2 left-1/2 rounded-xl w-[500px]">
            <div class="mt-3 text-center">
                <h3 class="mb-4 text-lg font-semibold text-white">Are you sure you want to delete this task?</h3>
                <div class="flex justify-center gap-6 mt-6 font-medium">
                    <button id="delete-no"
                        class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:hover:scale-95 rounded-3xl">No</button>
                    <button id="delete-yes"
                        class="transition-transform duration-200 hover:hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="task-details-modal"
        class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
        <div
            class="absolute p-5 mx-auto -translate-x-1/2 -translate-y-1/2 bg-[#132C51] shadow-xl top-1/2 left-1/2 rounded-xl w-96">
            <div class="mt-3">
                <h3 class="mb-4 text-lg font-semibold text-white">Task Details</h3>
                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Title</label>
                    <p id="details-title" class="text-gray-200"></p>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Description</label>
                    <p id="details-description" class="text-gray-200"></p>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Due Date</label>
                    <p id="details-due-date" class="text-gray-200"></p>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Priority</label>
                    <p id="details-priority" class="font-semibold text-gray-200"></p>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Status</label>
                    <p id="details-completed" class="text-gray-200"></p>
                </div>

                <div class="flex justify-center mt-6 font-medium">
                    <button type="button" id="close-details-modal"
                        class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:hover:scale-110 rounded-3xl">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentMonth = {{ $month }};
        let currentYear = {{ $year }};
        let selectedDate = null;


        // Priority colors
        const priorityColors = {
            'Urgent': '#DC2626',
            'High': '#F59E0B',
            'Normal': '#3B82F6',
            'Low': '#10B981'
        };

        // Global task objects (keyed by task ID for better mapping)
        let allTasks = @json($allTasks->keyBy('id'));
        let todayTasks = @json($todayTasks->keyBy('id'));
        let upcomingTasks = @json($upcomingTasks->keyBy('id'));
        let completedTasks = @json($completedTasks->keyBy('id'));

        // Debugging logs
        console.log('All Tasks:', allTasks);
        console.log('Today Tasks:', todayTasks);
        console.log('Upcoming Tasks:', upcomingTasks);
        console.log('Completed Tasks:', completedTasks);

        // Load tasks for selected date (tanpa filter sidebar)
        function loadTasks(date = null) {
            console.log('loadTasks called with date:', date);
            const taskList = document.getElementById('task-list');
            const targetDate = date || new Date().toISOString().slice(0, 10); // YYYY-MM-DD

            fetch(`/tasks?date=${targetDate}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fetched tasks for date', targetDate, ':', data);
                    displayTasks(data, taskList);

                        // Update title "Task in (Date)" (isi pill)
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
                    console.error('Error loading tasks for date', targetDate, ':', error);
                    taskList.innerHTML =
                        '<div class="p-3 text-[#132C51] text-center">Tidak ada tugas pada tanggal ini.</div>';

                    const pillEl = document.getElementById('task-in-date-pill');
                    if (pillEl) {
                        const pretty = new Date(targetDate).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: '2-digit'
                        });
                        pillEl.textContent = `${pretty}`;
                    }
                });

        }


        // Update task arrays when task status changes
        function updateTaskArrays(taskId, completed) {
            console.log('updateTaskArrays called with taskId:', taskId, 'completed:', completed);
            // Find the task in allTasks and update it
            if (allTasks[taskId]) {
                allTasks[taskId].completed = completed;

                // Update todayTasks
                if (todayTasks[taskId]) {
                    if (completed) {
                        delete todayTasks[taskId];
                    }
                } else if (!completed && allTasks[taskId].due_date && new Date(allTasks[taskId].due_date).toDateString() ===
                    new Date().toDateString()) {
                    todayTasks[taskId] = allTasks[taskId];
                }

                // Update upcomingTasks
                if (upcomingTasks[taskId]) {
                    if (completed) {
                        delete upcomingTasks[taskId];
                    }
                } else if (!completed && allTasks[taskId].due_date && new Date(allTasks[taskId].due_date) > new Date()) {
                    upcomingTasks[taskId] = allTasks[taskId];
                }

                // Update completedTasks
                if (completed) {
                    completedTasks[taskId] = allTasks[taskId];
                } else {
                    delete completedTasks[taskId];
                }
            }
        }

        // Function to add new task to UI
        function addTaskToUI(task) {
            // Add to allTasks
            allTasks[task.id] = task;

            // Determine category based on due_date
            const dueDate = new Date(task.due_date);
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);

            if (dueDate.toDateString() === today.toDateString()) {
                todayTasks[task.id] = task;
            } else if (dueDate.toDateString() === tomorrow.toDateString()) {
                upcomingTasks[task.id] = task;
            } else if (dueDate > today) {
                upcomingTasks[task.id] = task;
            }

            // Reload tasks to reflect changes
            loadTasks(selectedDate, currentFilter);
        }

        // Function to update category counts
        function updateCategoryCount(category) {
            const contentDiv = document.getElementById(`${category}-content`);
            const taskCount = contentDiv ? contentDiv.children.length : 0;
            const countSpan = document.querySelector(`[data-category="${category}"] .rounded-full`);
            if (countSpan) {
                countSpan.textContent = taskCount;
            }
        }

        // Function to update all category counts
        function updateAllCategoryCounts() {
            updateCategoryCount('today');
            updateCategoryCount('tomorrow');
            updateCategoryCount('upcoming');
            updateCategoryCount('completed');
        }

        function displayTasks(tasks, container) {
            console.log('displayTasks called with tasks:', tasks);
            container.innerHTML = '';

            if (tasks.length === 0) {
                container.innerHTML =
                    '<div class="p-3 text-[#132C51] text-center">Tidak ada tugas dalam kategori ini.</div>';
                return;
            }

            // Sort tasks (sesuai logika Anda)
            const today = new Date().toDateString();
            const priorityOrder = {
                'Urgent': 1,
                'High': 2,
                'Normal': 3,
                'Low': 4
            };
            tasks.sort((a, b) => {
                const aDate = a.due_date ? new Date(a.due_date).toDateString() : null;
                const bDate = b.due_date ? new Date(b.due_date).toDateString() : null;
                const aIsToday = aDate === today;
                const bIsToday = bDate === today;

                if (aIsToday && !bIsToday) return -1;
                if (!aIsToday && bIsToday) return 1;

                if (aDate && bDate) {
                    const dateDiff = new Date(a.due_date) - new Date(b.due_date);
                    if (dateDiff !== 0) return dateDiff;
                } else if (aDate) return -1;
                else if (bDate) return 1;

                return priorityOrder[a.priority] - priorityOrder[b.priority];
            });

            tasks.forEach(task => {
                const taskDiv = document.createElement('div');
                taskDiv.className =
                    'p-3 border rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md';
                taskDiv.setAttribute('data-task-id', task.id);




                taskDiv.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <input type="checkbox" class="w-5 h-5 mr-3 rounded-full accent-blue-500 task-checkbox" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                        <div class="flex-1">
                            <div class="flex items-center">
                                <div class="w-3 h-3 mr-2 rounded-full" style="background-color: ${priorityColors[task.priority]}"></div>
                                <span class="${task.completed ? 'line-through text-gray-500' : 'text-[#132C51]'} text-lg translate-y-[-2px] break-words whitespace-normal break-all">${task.title}</span>
                            </div>
                            ${task.due_date ? `<div class="text-sm text-gray-500">${new Date(task.due_date).toLocaleDateString()}</div>` : ''}
                        </div>
                    </div>
                    <div class="relative">
                        <button class="p-1 rounded task-menu-btn hover:bg-gray-200" data-task="${task.id}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>
                        <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="${task.id}">

                            <button class="flex items-center w-full gap-2 px-4 py-2 text-sm font-medium text-left text-white rounded-t-xl hover:bg-gray-600 details-btn" data-task="${task.id}">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 9C2.44772 9 2 9.44772 2 10C2 10.5523 2.44772 11 3 11H21C21.5523 11 22 10.5523 22 10C22 9.44772 21.5523 9 21 9H3Z" fill="#FFFFFF"></path>
                                    <path d="M3 13C2.44772 13 2 13.4477 2 14C2 14.5523 2.44772 15 3 15H15C15.5523 15 16 14.5523 16 14C16 13.4477 15.5523 13 15 13H3Z" fill="#FFFFFF"></path>
                                </svg>
                                Details
                            </button>

                            ${!task.completed ? `
                            <button class="flex items-center w-full gap-3 px-3 py-2 text-sm font-medium text-left text-white hover:bg-gray-600 rename-btn" data-task="${task.id}">
                                <svg class="w-6 h-6" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.75 2C11.3358 2 11 2.33579 11 2.75C11 3.16421 11.3358 3.5 11.75 3.5H13.25V24.5H11.75C11.3358 24.5 11 24.8358 11 25.25C11 25.6642 11.3358 26 11.75 26H16.25C16.6642 26 17 25.6642 17 25.25C17 24.8358 16.6642 24.5 16.25 24.5H14.75V3.5H16.25C16.6642 3.5 17 3.16421 17 2.75C17 2.33579 16.6642 2 16.25 2H11.75Z" fill="#FFFFFF"></path>
                                    <path d="M6.25 6.01958H12.25V7.51958H6.25C5.2835 7.51958 4.5 8.30308 4.5 9.26958V18.7696C4.5 19.7361 5.2835 20.5196 6.25 20.5196H12.25V22.0196H6.25C4.45507 22.0196 3 20.5645 3 18.7696V9.26958C3 7.47465 4.45507 6.01958 6.25 6.01958Z" fill="#FFFFFF"></path>
                                    <path d="M21.75 20.5196H15.75V22.0196H21.75C23.5449 22.0196 25 20.5645 25 18.7696V9.26958C25 7.47465 23.5449 6.01958 21.75 6.01958H15.75V7.51958H21.75C22.7165 7.51958 23.5 8.30308 23.5 9.26958V18.7696C23.5 19.7361 22.7165 20.5196 21.75 20.5196Z" fill="#FFFFFF"></path>
                                </svg>
                                Rename
                            </button>
                            ` : ''}

                            <button class="flex items-center w-full gap-3 px-3 py-2 text-sm font-medium text-left text-white hover:bg-gray-600 duplicate-btn" data-task="${task.id}">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" transform="matrix(-1, 0, 0, 1, 0, 0)">
                                    <path d="M18 3H4C3.44772 3 3 3.44772 3 4V18C3 18.5523 2.55228 19 2 19C1.44772 19 1 18.5523 1 18V4C1 2.34315 2.34315 1 4 1H18C18.5523 1 19 1.44772 19 2C19 2.55228 18.5523 3 18 3Z" fill="#FFFFFF"></path>
                                    <path d="M13 11C13 10.4477 13.4477 10 14 10C14.5523 10 15 10.4477 15 11V13H17C17.5523 13 18 13.4477 18 14C18 14.5523 17.5523 15 17 15H15V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V15H11C10.4477 15 10 14.5523 10 14C10 13.4477 10.4477 13 11 13H13V11Z" fill="#FFFFFF"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M20 5C21.6569 5 23 6.34315 23 8V20C23 21.6569 21.6569 23 20 23H8C6.34315 23 5 21.6569 5 20V8C5 6.34315 6.34315 5 8 5H20ZM20 7C20.5523 7 21 7.44772 21 8V20C21 20.5523 20.5523 21 20 21H8C7.44772 21 7 20.5523 7 20V8C7 7.44772 7.44772 7 8 7H20Z" fill="#FFFFFF"></path>
                                </svg>
                                Duplicate
                            </button>




                            <button class="flex items-center w-full gap-3 px-3 py-2 text-sm font-medium text-left text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="${task.id}">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 5H18M9 5V5C10.5769 3.16026 13.4231 3.16026 15 5V5M9 20H15C16.1046 20 17 19.1046 17 18V9C17 8.44772 16.5523 8 16 8H8C7.44772 8 7 8.44772 7 9V18C7 19.1046 7.89543 20 9 20Z" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>


            `;
                container.appendChild(taskDiv);
            });
        }

        // Calendar navigation
        document.getElementById('prev-month').addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            navigateToMonth(currentYear, currentMonth);
        });

        document.getElementById('next-month').addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            navigateToMonth(currentYear, currentMonth);
        });

        function navigateToMonth(year, month) {
            window.location.href = `/schedule?year=${year}&month=${month}`;
        }

        // Date selection
        document.querySelectorAll('.date-cell').forEach(cell => {
            cell.addEventListener('click', () => {
                selectedDate = cell.dataset.date;
                document.querySelectorAll('.date-cell').forEach(c => c.classList.remove('bg-blue-100','border-blue-500','ring-2','ring-blue-500'));
                cell.classList.add('bg-blue-100','border-blue-500','ring-2','ring-blue-500');
                loadTasks(selectedDate);
            });
        });


        // Tidak ada task filter (All/Today/Upcoming/Completed) di panel kanan.
        // Saat user memilih tanggal di calendar, daftar tasks akan dimuat untuk tanggal tersebut.


        // Toggle view
        document.getElementById('toggle-view').addEventListener('click', () => {
            const calendarView = document.getElementById('calendar-view');
            const listView = document.getElementById('list-view');
            const viewText = document.getElementById('view-text');

            if (calendarView.classList.contains('hidden')) {
                calendarView.classList.remove('hidden');
                listView.classList.add('hidden');
                viewText.textContent = 'Calendar View';
            } else {
                calendarView.classList.add('hidden');
                listView.classList.remove('hidden');
                viewText.textContent = 'List View';
            }
        });

        // Tombol Add Task (Quick add task)
        document.getElementById('add-task-btn').addEventListener('click', () => {
            document.getElementById('add-task-modal').classList.remove('hidden');
        });

        // Initialize
        console.log('Initializing schedule page');
        loadTasks(null);


        const addTaskModal = document.getElementById('add-task-modal');
        const closeModal = document.getElementById('close-modal');
        const confirmModal = document.getElementById('confirm-modal');
        const confirmYes = document.getElementById('confirm-yes');
        const confirmNo = document.getElementById('confirm-no');

        // 1. Perbaikan Tombol Cancel/Close Modal (Add Task)
        closeModal.addEventListener('click', () => {
            addTaskModal.classList.add('hidden');
            // Reset form saat ditutup
            document.querySelector('#add-task-modal form').reset();
            document.getElementById('file-list').innerHTML = '';
            document.getElementById('file-label').textContent = 'Add File';
            // Reset Alpine.js priority selection
            const prioritySelect = document.querySelector('#add-task-modal select[name="priority"]');
            if (prioritySelect) {
                prioritySelect.value = '';
            }
            const alpineData = document.querySelector('#add-task-modal [x-data]');
            if (alpineData && alpineData._x_dataStack) {
                alpineData._x_dataStack[0].selected = null;
            }
        });

        // File upload preview
        const fileInput = document.getElementById('task-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const fileList = document.getElementById('file-list');
                const label = document.getElementById('file-label');
                fileList.innerHTML = '';
                if (this.files.length > 0) {
                    label.textContent = this.files.length + ' file(s) selected';
                    Array.from(this.files).forEach(f => {
                        const div = document.createElement('div');
                        div.textContent = '📎 ' + f.name;
                        fileList.appendChild(div);
                    });
                } else {
                    label.textContent = 'Add File';
                }
            });
        }

        // Handle form submission for adding new task
        document.querySelector('#add-task-modal form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add the new task to the UI
                        addTaskToUI(data.task);
                        // Close the modal
                        addTaskModal.classList.add('hidden');
                        // Reset form
                        this.reset();
                        document.getElementById('file-list').innerHTML = '';
                        document.getElementById('file-label').textContent = 'Add File';
                        // Reset Alpine.js priority selection
                        const prioritySelect = document.querySelector(
                            '#add-task-modal select[name="priority"]');
                        if (prioritySelect) {
                            prioritySelect.value = '';
                        }
                        const alpineData = document.querySelector('#add-task-modal [x-data]');
                        if (alpineData && alpineData._x_dataStack) {
                            alpineData._x_dataStack[0].selected = null;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Task checkbox functionality (menggunakan delegasi untuk elemen yang dimuat secara dinamis)
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('task-checkbox')) {
                const checkbox = e.target;
                if (checkbox.checked) {
                    confirmModal.classList.remove('hidden');
                    confirmModal.dataset.taskId = checkbox.dataset.id;
                } else {
                    // Jika user mencentang lalu membatalkannya (meskipun logika server mungkin hanya menangani 'completed: true')
                    // bisa dengan menambahkan logika server untuk uncheck di sini jika didukung.
                    // Saat ini, biarkan saja (confirmNo akan meresetnya).
                }
            }
        });

        confirmYes.addEventListener('click', () => {
            const taskId = confirmModal.dataset.taskId;
            fetch(`/tasks/${taskId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    completed: true
                })
            }).then(() => {
                updateTaskArrays(taskId, true);
                // Muat ulang daftar tugas untuk mencerminkan perubahan
                loadTasks(selectedDate);
                confirmModal.classList.add('hidden');

            });
        });

        confirmNo.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
            // Uncheck checkbox yang memicu modal
            const checkbox = document.querySelector(`.task-checkbox[data-id="${confirmModal.dataset.taskId}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }
        });




        // 3. Perbaikan Task menu functionality (Titik Tiga)
        document.addEventListener('click', function(e) {
            const taskMenuBtn = e.target.closest('.task-menu-btn');
            const taskMenu = e.target.closest('.task-menu');

            // Langkah A: Tutup SEMUA menu terlebih dahulu (Reset)
            document.querySelectorAll('.task-menu').forEach(m => {
                if (m !== taskMenu) {
                    m.classList.add('hidden');
                }
            });

            // Langkah B: Toggle menu yang diklik
            if (taskMenuBtn) {
                const taskId = taskMenuBtn.dataset.task;
                const menu = document.querySelector(`.task-menu[data-task="${taskId}"]`);
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            }
            // Langkah C: Jika klik di luar menu dan bukan tombolnya, tutup menu.
            else if (!taskMenu) {
                document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
            }
        });

        // Menyembunyikan menu setelah aksi dipilih (Tambahkan ini ke setiap event handler menu)
        const hideMenus = () => {
            document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
        };

        // Rename button functionality
        document.addEventListener('click', function(e) {
            const renameBtn = e.target.closest('.rename-btn');
            if (renameBtn) {
                const taskId = renameBtn.dataset.task;
                document.getElementById('rename-task-id').value = taskId;
                document.getElementById('rename-title').value = document.querySelector(
                    `.task-checkbox[data-id="${taskId}"]`).nextElementSibling.textContent;
                document.getElementById('rename-modal').classList.remove('hidden');
                // Hide the task menu
                document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
            }
        });

        // Duplicate button functionality
        document.addEventListener('click', function(e) {
            const duplicateBtn = e.target.closest('.duplicate-btn');
            if (duplicateBtn) {
                hideMenus();
                const taskId = duplicateBtn.dataset.task;
                fetch(`/tasks/${taskId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    location.reload();
                });
            }
        });




        // Delete button functionality
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                hideMenus();
                const taskId = deleteBtn.dataset.task;
                document.getElementById('delete-confirm-modal').classList.remove('hidden');
                document.getElementById('delete-confirm-modal').dataset.taskId = taskId;
            }
        });

        // Details button functionality (Read-Only)
        document.addEventListener('click', function(e) {
            const detailsBtn = e.target.closest('.details-btn');
            if (detailsBtn) {
                hideMenus();
                const taskId = detailsBtn.dataset.task;
                fetch(`/tasks/${taskId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('details-title').textContent = data.title || 'N/A';
                        document.getElementById('details-description').textContent = data.description || 'N/A';
                        document.getElementById('details-due-date').textContent = data.due_date || 'N/A';

                        const priorityEl = document.getElementById('details-priority');
                        const priority = data.priority || 'N/A';

                        // 1. Atur teksnya
                        priorityEl.textContent = priority;

                        // 2. Logika penentuan kelas warna
                        function getPriorityClass(priority) {
                            if (priority === 'Urgent') return 'text-red-500';
                            if (priority === 'High') return 'text-yellow-500';
                            if (priority === 'Normal') return 'text-blue-500';
                            if (priority === 'Low') return 'text-green-500';
                            return 'text-gray-200'; // Warna default
                        }

                        // 3. Terapkan kelas warna (mempertahankan font-semibold)
                        priorityEl.className = 'font-semibold ' + getPriorityClass(priority);

                        document.getElementById('details-completed').textContent = data.completed ?
                            'Completed' : 'Not Completed';




                        document.getElementById('task-details-modal').classList.remove('hidden');
                    });
            }
        });

        // Close details modal
        document.getElementById('close-details-modal').addEventListener('click', () => {
            document.getElementById('task-details-modal').classList.add('hidden');
        });

        // Rename form submission
        document.getElementById('rename-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('rename-task-id').value;
            const newTitle = document.getElementById('rename-title').value;
            fetch(`/tasks/${taskId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: newTitle
                })
            }).then(() => {
                // Update UI
                const taskDiv = document.querySelector(`[data-task-id="${taskId}"]`);
                if (taskDiv) {
                    const span = taskDiv.querySelector('span');
                    if (span) span.textContent = newTitle;
                }
                document.getElementById('rename-modal').classList.add('hidden');
            });
        });

        // Close rename modal
        document.getElementById('close-rename-modal').addEventListener('click', () => {
            document.getElementById('rename-modal').classList.add('hidden');
        });




        // Delete confirmation
        document.getElementById('delete-yes').addEventListener('click', () => {
            const taskId = document.getElementById('delete-confirm-modal').dataset.taskId;
            fetch(`/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                // Remove from UI
                const taskDiv = document.querySelector(`[data-task-id="${taskId}"]`);
                if (taskDiv) {
                    taskDiv.remove();
                }
                // Update arrays
                delete allTasks[taskId];
                delete todayTasks[taskId];
                delete upcomingTasks[taskId];
                delete completedTasks[taskId];
                document.getElementById('delete-confirm-modal').classList.add('hidden');
            });
        });

    // Cancel delete
    document.getElementById('delete-no').addEventListener('click', () => {
        document.getElementById('delete-confirm-modal').classList.add('hidden');
    });

    // Search functionality
    document.getElementById('search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const taskDivs = document.querySelectorAll('#task-list > div[data-task-id]');
        taskDivs.forEach(taskDiv => {
            const taskTitle = taskDiv.querySelector('span.translate-y-\\[\\-2px\\]')?.textContent.toLowerCase() || '';
            if (taskTitle.includes(searchTerm)) {
                taskDiv.style.display = '';
            } else {
                taskDiv.style.display = 'none';
            }
        });
    });

</script>
</x-app-layout>
