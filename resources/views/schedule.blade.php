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
                    <div class="flex items-center space-x-3 text-sm">
                        <button id="toggle-view"
                            class="px-4 py-2 text-white transition-transform duration-200 bg-[##5F6E84] hover:hover:scale-110 rounded-3xl">
                            <span id="view-text">Calendar View</span>
                        </button>
                        <button type="button" id="add-task-btn"
                            class="add-task-btn flex items-center gap-2 px-6 py-2 text-sm font-bold font-poppins text-white bg-[#0E213D] shadow-md rounded-3xl focus:outline-none transition-transform duration-200 hover:scale-110">
                            <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 h-5"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#ffffff" fill-rule="evenodd" d="M9 17a1 1 0 102 0v-6h6a1 1 0 100-2h-6V3a1 1 0 10-2 0v6H3a1 1 0 000 2h6v6z"></path> </g></svg>
                            <span>New Task</span>
                        </button>
                    </div>
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
                <div class="flex-1 min-w-0 p-8 bg-white shadow-xl rounded-3xl">
                    <div class="mb-4">
                        <h2 id="task-in-date-title" class="mb-4 text-xl font-bold text-[#1C427A]">
                            Task in <span id="task-in-date-pill" class="px-3 py-1 text-white border border-[#1C427A] rounded-full bg-[#1C427A]">{{ date('d M Y') }}</span>
                        </h2>

                    </div>


                    <div id="task-list" class="space-y-3 overflow-y-auto max-h-[600px] text-[#132C51]">
                        <!-- Tasks will be loaded here via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="add-task-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 shadow-xl rounded-xl w-[850px] bg-[#132C51]">
            <div>
                <h3 class="mb-3 text-2xl font-semibold text-white">New Task</h3>
                <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-y-2 gap-x-6">
                    @csrf
                    
                    <!-- Row 1: Title -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <label class="font-semibold text-gray-100">Title</label>
                        </div>
                        <input placeholder="Title Name" type="text" name="title"
                            class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg"
                            required>
                    </div>

                    <!-- Row 2: Description -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            <label class="font-semibold text-gray-100">Description</label>
                        </div>
                        <textarea placeholder="Add Description" name="description"
                            class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                    </div>

                    <!-- Row 3: Due Date, Times, Priority -->
                    <div class="grid grid-cols-1 col-span-12 gap-6 md:grid-cols-4">
                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <label class="font-semibold text-gray-100">Due Date</label>
                            </div>
                            <input type="date" name="due_date"
                                class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                                required value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <label class="font-semibold text-gray-100">Start Time</label>
                            </div>
                            <input type="time" name="start_time"
                                class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <label class="font-semibold text-gray-100">End Time</label>
                            </div>
                            <input type="time" name="end_time"
                                class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                        </div>

                        <!-- Priority Selection -->
                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 01-2 2zm9-13.5V9"></path></svg>
                                <label class="font-semibold text-gray-100">Priority</label>
                            </div>
                            <div x-data="{ open: false, selected: null }" 
                                 @reset-new-task.window="selected = null"
                                 class="relative w-full">
                                <button @click="open = !open" type="button"
                                    class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] border border-gray-300 rounded-lg"
                                        :class="{
                                            'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
                                            'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
                                            'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
                                            'bg-green-50 border-green-300 text-green-700': selected === 'Low',
                                            'text-white border-gray-600': !selected
                                        }">
                                        <svg class="w-5 h-5"
                                            :class="{
                                                'text-red-500': selected === 'Urgent',
                                                'text-yellow-500': selected === 'High',
                                                'text-blue-500': selected === 'Normal',
                                                'text-green-500': selected === 'Low',
                                                'text-gray-400': !selected
                                            }"
                                        fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
                                    </svg>
                                    <span x-text="selected || 'Priority'" class="flex-1"></span>
                                </button>

                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute z-10 w-full p-1 mt-1 bg-[#EAF0FA] rounded-xl shadow-xl">
                                    <div @click="selected = 'Urgent'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                        <span class="font-semibold text-black">Urgent</span>
                                    </div>
                                    <div @click="selected = 'High'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                        <span class="font-semibold text-black">High</span>
                                    </div>
                                    <div @click="selected = 'Normal'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                        <span class="font-semibold text-black">Normal</span>
                                    </div>
                                    <div @click="selected = 'Low'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
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

                    <!-- Row 4: Attachments -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mt-2 mb-1">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <label class="font-semibold text-gray-100">Attachments</label>
                        </div>
                        <label class="flex items-center justify-center w-full gap-2 px-3 py-1 text-white transition-transform duration-200 border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:scale-[1.01] hover:bg-[#1A365D]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="file-label">Add File</span>
                            <input type="file" name="attachments[]" multiple class="hidden" id="task-file-input" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        </label>
                        <div id="file-list" class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-300"></div>
                    </div>

                    <!-- Row 5: Buttons -->
                    <div class="flex justify-center col-span-12 gap-6 mt-4 font-medium">
                        <button type="submit" class="transition-transform duration-200 hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Save</button>
                        <button type="button" id="close-modal" class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 rounded-3xl hover:scale-95">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<!-- Edit Task Modal -->
    <div id="edit-task-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 shadow-xl rounded-xl w-[850px] bg-[#132C51]">
            <div>
                <h3 class="mb-3 text-2xl font-semibold text-white">Edit Task</h3>

                <form id="edit-task-form" method="POST" action="" enctype="multipart/form-data" class="grid grid-cols-12 gap-y-2 gap-x-6">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="task_id" id="edit-task-id">

                    <!-- Row 1: Title -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <label class="font-semibold text-gray-100">Title</label>
                        </div>
                        <input id="edit-title" placeholder="Title Name" type="text" name="title"
                            class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg"
                            required>
                    </div>

                    <!-- Row 2: Description -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            <label class="font-semibold text-gray-100">Description</label>
                        </div>
                        <textarea id="edit-description" placeholder="Add Description" name="description"
                            class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                    </div>

                    <!-- Row 3: Due Date, Times, Priority -->
                    <div class="grid grid-cols-1 col-span-12 gap-6 md:grid-cols-4">
                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <label class="font-semibold text-gray-100">Due Date</label>
                            </div>
                            <input id="edit-due-date" type="date" name="due_date"
                                class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                                required>
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <label class="font-semibold text-gray-100">Start Time</label>
                            </div>
                            <input id="edit-start-time" type="time" name="start_time"
                                class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <label class="font-semibold text-gray-100">End Time</label>
                            </div>
                            <input id="edit-end-time" type="time" name="end_time"
                                class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 01-2 2zm9-13.5V9"></path></svg>
                                <label class="font-semibold text-gray-100">Priority</label>
                            </div>
                            <div x-data="{ open: false, selected: null }" 
                                 @set-edit-priority.window="selected = $event.detail.priority"
                                 class="relative w-full">
                                <button @click="open = !open" type="button"
                                    class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] border border-gray-300 rounded-lg"
                                        :class="{
                                            'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
                                            'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
                                            'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
                                            'bg-green-50 border-green-300 text-green-700': selected === 'Low',
                                            'text-white border-gray-600': !selected
                                        }">
                                        <svg class="w-5 h-5"
                                            :class="{
                                                'text-red-500': selected === 'Urgent',
                                                'text-yellow-500': selected === 'High',
                                                'text-blue-500': selected === 'Normal',
                                                'text-green-500': selected === 'Low',
                                                'text-gray-400': !selected
                                            }"
                                        fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
                                    </svg>
                                    <span id="edit-priority-label" x-text="selected || 'Priority'" class="flex-1"></span>
                                </button>

                                <div x-show="open" @click.outside="open = false" x-transition
                                    class="absolute z-10 w-full p-1 mt-1 bg-[#EAF0FA] rounded-xl shadow-xl">
                                    <div @click="selected = 'Urgent'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                        <span class="font-semibold text-black">Urgent</span>
                                    </div>
                                    <div @click="selected = 'High'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                        <span class="font-semibold text-black">High</span>
                                    </div>
                                    <div @click="selected = 'Normal'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                        <span class="font-semibold text-black">Normal</span>
                                    </div>
                                    <div @click="selected = 'Low'; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
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

                    <!-- Row 5: Attachments -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mt-2 mb-1">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <label class="font-semibold text-gray-100">Attachments</label>
                        </div>
                        <div id="edit-existing-attachments-wrap" class="hidden mb-4">
                            <div id="edit-existing-attachments" class="grid grid-cols-2 gap-4"></div>
                        </div>

                        <label class="flex items-center justify-center w-full gap-2 px-3 py-1 text-white transition-transform duration-200 border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:scale-[1.01] hover:bg-[#1A365D]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="edit-file-label">Add New File</span>
                            <input type="file" name="attachments[]" multiple class="hidden" id="edit-task-file-input" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        </label>
                        <div id="edit-file-list" class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-300"></div>
                    </div>

                    <!-- Row 6: Buttons -->
                    <div class="flex justify-center col-span-12 gap-6 mt-4 font-medium">
                        <button type="submit" class="transition-transform duration-200 hover:hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Save</button>
                        <button type="button" id="close-edit-modal" class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 rounded-3xl hover:hover:scale-95">Cancel</button>
                    </div>
                </form>

                {{-- Prevent full-page navigation and ignore JSON response when saving edit task --}}
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const form = document.getElementById('edit-task-form');
                        if (!form) return;

                            form.addEventListener('submit', (e) => {
                                // Let normal submit happen, but avoid displaying JSON response in browser.
                                // If server returns JSON, redirect back to /home.
                                const action = form.getAttribute('action') || '';
                                if (!action || !action.startsWith('/tasks/')) return;

                                e.preventDefault();

                                const formData = new FormData(form);
                                fetch(action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                                    },
                                    body: formData
                                })
                                .then(async (res) => {
                                    // Always go back to home after save to avoid rendering JSON object on page
                                    window.location.href = '/home';
                                })
                                .catch(() => {
                                    window.location.href = '/home';
                                });
                            });
                        });
                    </script>

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

    <!-- Task Details Modal (view only) -->
    <div id="task-details-modal" class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 bg-[#132C51] shadow-xl rounded-xl w-[850px]">
            <div>
                <h3 class="mb-3 text-2xl font-semibold text-white">Task Details</h3>

                <div class="grid grid-cols-12 gap-y-2 gap-x-6">
                    <!-- Row 1: Title -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <label class="font-semibold text-gray-100">Title</label>
                        </div>
                        <p id="details-title" class="text-gray-200 break-words"></p>
                    </div>

                    <!-- Row 2: Description -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            <label class="font-semibold text-gray-100">Description</label>
                        </div>
                        <p id="details-description" class="text-gray-200 break-words"></p>
                    </div>

                    <div class="col-span-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <label class="font-semibold text-gray-100">Due Date</label>
                        </div>
                        <p id="details-due-date" class="text-gray-200"></p>
                    </div>
                    <div class="col-span-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-indigo-400 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <label class="font-semibold text-gray-100">Created Date</label>
                        </div>
                        <p id="details-created-date" class="text-gray-200"></p>
                    </div>
                    <div class="col-span-4">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-purple-400 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <label class="font-semibold text-gray-100">Completed Date</label>
                        </div>
                        <p id="details-completed-at" class="text-gray-200"></p>
                    </div>

                    <!-- Row 3.5: Start Time, End Time -->
                    <div class="col-span-6">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <label class="font-semibold text-gray-100">Start Time</label>
                        </div>
                        <p id="details-start-time" class="text-gray-200"></p>
                    </div>
                    <div class="col-span-6">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <label class="font-semibold text-gray-100">End Time</label>
                        </div>
                        <p id="details-end-time" class="text-gray-200"></p>
                    </div>

                    <!-- Row 4: Priority, Status -->
                    <div class="col-span-6">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 01-2 2zm9-13.5V9"></path></svg>
                            <label class="font-semibold text-gray-100">Priority</label>
                        </div>
                        <p id="details-priority" class="font-semibold text-gray-200"></p>
                    </div>
                    <div class="col-span-6">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <label class="font-semibold text-gray-100">Status</label>
                        </div>
                        <p id="details-completed" class="text-gray-200"></p>
                    </div>

                    <!-- Row 5: Attachments -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <label class="font-semibold text-gray-100">Attachments</label>
                        </div>
                        <div id="details-attachments" class="grid grid-cols-2 gap-4 text-sm text-gray-200">
                            <p class="col-span-2 text-gray-400">No attachments</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-center col-span-12 gap-4 mt-6 font-medium">
                        <button type="button" id="edit-details-btn" class="px-5 py-1 text-white transition-transform duration-200 bg-[#1C427A] hover:hover:scale-110 rounded-3xl">
                            Edit
                        </button>
                        <button type="button" id="close-details-modal" class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:hover:scale-110 rounded-3xl">
                            Close
                        </button>
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
                        '<div class="p-3 text-[#132C51] text-center">No tasks on this date!</div>';

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
                    '<div class="p-3 text-[#132C51] text-center">No tasks on this date!</div>';
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
                    <div class="flex items-center flex-1 ml-3 mr-8">
                        <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="${task.id}" ${task.completed ? 'checked' : ''}>
                        <div class="flex-1 min-w-0 ml-4 cursor-pointer" data-task-title="${task.id}">
                            <div class="flex items-center">
                                <span class="${task.completed ? 'line-through text-gray-500' : 'text-[#132C51]'} text-lg translate-y-[-2px] break-words whitespace-normal break-all">
                                    <div class="flex flex-col">
                                        <div class="flex items-center">
                                            ${task.priority ? `
                                                <svg class="flex-shrink-0 inline-block w-6 h-6 mr-2 ${task.priority === 'Urgent' ? 'text-red-500' : (task.priority === 'High' ? 'text-yellow-500' : (task.priority === 'Normal' ? 'text-blue-500' : 'text-green-500'))}" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <title>${task.priority}</title>
                                                    <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
                                                </svg>
                                            ` : ''}
                                            ${task.title}
                                        </div>
                                        ${task.start_time || task.end_time ? `
                                            <div class="text-xs text-gray-400 mt-0.5 no-line-through">
                                                ${task.start_time ? task.start_time.substring(0, 5) : ''}
                                                ${task.start_time && task.end_time ? '-' : ''}
                                                ${task.end_time ? task.end_time.substring(0, 5) : ''}
                                            </div>
                                        ` : ''}
                                    </div>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="relative">
                        <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="${task.id}">⋯</button>
                        <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="${task.id}">
                            ${!task.completed ? `
                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="${task.id}">Edit</button>
                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="${task.id}">Duplicate</button>
                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="${task.id}">Delete</button>
                            ` : `
                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-red-500 rounded-xl hover:bg-gray-600 delete-btn" data-task="${task.id}">Delete</button>
                            `}
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
            const modal = document.getElementById('add-task-modal');
            const form = modal.querySelector('form');
            form.reset();

            // Reset File List
            document.getElementById('file-list').innerHTML = '';
            window.newTaskFiles = new DataTransfer();
            document.getElementById('task-file-input').files = window.newTaskFiles.files;
            document.getElementById('file-label').textContent = 'Add File';

            // Reset Alpine Priority
            window.dispatchEvent(new CustomEvent('reset-new-task'));

            modal.classList.remove('hidden');
        });

        // Initialize
        console.log('Initializing schedule page');
        loadTasks(null);


        const addTaskModal = document.getElementById('add-task-modal');
        const closeModal = document.getElementById('close-modal');


        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            const editModal = document.getElementById('edit-task-modal');
            const detailsModal = document.getElementById('task-details-modal');
            const deleteModal = document.getElementById('delete-confirm-modal');

            if (e.target === addTaskModal) {
                addTaskModal.classList.add('hidden');
                document.querySelector('#add-task-modal form').reset();
                window.newTaskFiles = new DataTransfer();
                document.getElementById('file-list').innerHTML = '';
                document.getElementById('file-label').textContent = 'Add File';
                const alpineRoot = document.querySelector('#add-task-modal [x-data]');
                if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data) {
                    alpineRoot.__x.$data.selected = null;
                }
            } else if (e.target === editModal) {
                editModal.classList.add('hidden');
                window.editTaskFiles = new DataTransfer();
            } else if (e.target === detailsModal) {
                detailsModal.classList.add('hidden');
            } else if (e.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });

        // 1. Perbaikan Tombol Cancel/Close Modal (Add Task)
        closeModal.addEventListener('click', () => {
            addTaskModal.classList.add('hidden');
            // Reset form saat ditutup
            document.querySelector('#add-task-modal form').reset();
            window.newTaskFiles = new DataTransfer();
            document.getElementById('file-list').innerHTML = '';
            document.getElementById('file-label').textContent = 'Add File';
            // Reset Alpine.js priority selection
            const prioritySelect = document.querySelector('#add-task-modal select[name="priority"]');
            if (prioritySelect) {
                prioritySelect.value = '';
            }
            const alpineRoot = document.querySelector('#add-task-modal [x-data]');
            if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data) {
                alpineRoot.__x.$data.selected = null;
            }
        });

        // File upload preview
        const fileInput = document.getElementById('task-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const fileList = document.getElementById('file-list');
                const label = document.getElementById('file-label');
                
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
                        removeBtn.className = 'text-red-400 hover:text-red-600 font-bold ml-2 text-lg leading-none';
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
                        window.newTaskFiles = new DataTransfer();
                        document.getElementById('file-list').innerHTML = '';
                        document.getElementById('file-label').textContent = 'Add File';
                        // Reset Alpine.js priority selection
                        const prioritySelect = document.querySelector(
                            '#add-task-modal select[name="priority"]');
                        if (prioritySelect) {
                            prioritySelect.value = '';
                        }
                        const alpineRoot = document.querySelector('#add-task-modal [x-data]');
                        if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data) {
                            alpineRoot.__x.$data.selected = null;
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
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTaskArrays(taskId, isCompleted);
                        loadTasks(selectedDate);
                    } else {
                        alert('Failed to update task.');
                        checkbox.checked = !isCompleted;
                    }
                }).catch(() => {
                    alert('Error updating task.');
                    checkbox.checked = !isCompleted;
                });
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

        // Edit button functionality
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-btn');
            if (editBtn) {
                const taskId = editBtn.dataset.task;
                fetch(`/tasks/${taskId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit-task-id').value = data.id;
                        document.getElementById('edit-title').value = data.title || '';
                        document.getElementById('edit-description').value = data.description || '';
                        document.getElementById('edit-due-date').value = data.due_date || '';
                        document.getElementById('edit-start-time').value = data.start_time ? data.start_time.substring(0, 5) : '';
                        document.getElementById('edit-end-time').value = data.end_time ? data.end_time.substring(0, 5) : '';
                        
                        // Priority
                        const pr = data.priority || '';
                        const prSelect = document.getElementById('edit-priority');
                        const prLabel = document.getElementById('edit-priority-label');
                        
                        window.dispatchEvent(new CustomEvent('set-edit-priority', { 
                            detail: { priority: pr } 
                        }));

                        if (prSelect) prSelect.value = pr;
                        if (prLabel) prLabel.textContent = pr || 'Priority';

                        // Attachments
                        window.editTaskFiles = new DataTransfer();
                        const editFileInput = document.getElementById('edit-task-file-input');
                        const editFileList = document.getElementById('edit-file-list');
                        const editFileLabel = document.getElementById('edit-file-label');
                        if (editFileInput) editFileInput.value = '';
                        if (editFileList) editFileList.innerHTML = '';
                        if (editFileLabel) editFileLabel.textContent = 'Add New File';

                        const existingWrap = document.getElementById('edit-existing-attachments-wrap');
                        const existingContainer = document.getElementById('edit-existing-attachments');
                        if (existingWrap && existingContainer) {
                            const attachments = data.attachments || [];
                            if (attachments.length) {
                                existingWrap.classList.remove('hidden');
                                existingContainer.innerHTML = attachments.map(att => {
                                    const originalName = att.original_name || att.filename || 'Attachment';
                                    const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                    const isImage = att.mime_type && att.mime_type.startsWith('image/') && att.storage_path;
                                    const imgHtml = isImage ? `<img src="${path}" class="flex-shrink-0 object-cover w-12 h-12 rounded-md">` : '';
                                    return `
                                        <div class="flex items-center gap-4 p-3 bg-[#1A365D] border border-gray-600 rounded-xl shadow-sm">
                                            <a href="${path}" target="_blank" class="flex items-center flex-1 min-w-0 gap-4 transition-opacity hover:opacity-80">
                                                ${imgHtml}
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-medium text-gray-200 truncate" title="${originalName}">${originalName}</div>
                                                    <div class="mt-1 text-xs text-gray-400">${att.mime_type || ''}</div>
                                                </div>
                                            </a>
                                            <button type="button" class="flex-shrink-0 ml-2 text-xl font-bold text-red-400 hover:text-red-600" onclick="
                                                const div = this.closest('.bg-[#1A365D]');
                                                div.style.display = 'none';
                                                const input = document.createElement('input');
                                                input.type = 'hidden';
                                                input.name = 'remove_attachments[]';
                                                input.value = '${att.id}';
                                                div.parentNode.appendChild(input);
                                            ">&times;</button>
                                        </div>
                                    `;
                                }).join('');
                            } else {
                                existingWrap.classList.add('hidden');
                            }
                        }

                        document.getElementById('edit-task-modal').classList.remove('hidden');
                    });
                // Hide the task menu
                document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
            }
        });

        // Edit File upload preview
        const editFileInputEl = document.getElementById('edit-task-file-input');
        if (editFileInputEl) {
            editFileInputEl.addEventListener('change', function() {
                const fileList = document.getElementById('edit-file-list');
                const label = document.getElementById('edit-file-label');
                
                Array.from(this.files).forEach(file => {
                    window.editTaskFiles.items.add(file);
                });
                this.files = window.editTaskFiles.files;
                
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
                        removeBtn.className = 'text-red-400 hover:text-red-600 font-bold ml-2 text-lg leading-none';
                        removeBtn.onclick = (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            const dt = new DataTransfer();
                            Array.from(window.editTaskFiles.files).forEach((file, i) => {
                                if (i !== index) dt.items.add(file);
                            });
                            window.editTaskFiles = dt;
                            editFileInputEl.files = window.editTaskFiles.files;
                            editFileInputEl.dispatchEvent(new Event('change'));
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

        // Details button functionality (Read-Only) - and Title click
        document.addEventListener('click', function(e) {
            const detailsBtn = e.target.closest('.details-btn') || e.target.closest('[data-task-title]');
            if (detailsBtn) {
                hideMenus();
                const taskId = detailsBtn.dataset.task || detailsBtn.dataset.taskTitle;
                if (!taskId) return;
                fetch(`/tasks/${taskId}`)
                    .then(response => response.json())
                    .then(task => {
                        const detailsTitle = document.getElementById('details-title');
                        const detailsDescription = document.getElementById('details-description');
                        const detailsDueDate = document.getElementById('details-due-date');
                        const detailsCreatedDate = document.getElementById('details-created-date');
                        const detailsCompletedAt = document.getElementById('details-completed-at');
                        const detailsPriority = document.getElementById('details-priority');
                        const detailsCompleted = document.getElementById('details-completed');
                        const detailsStartTime = document.getElementById('details-start-time');
                        const detailsEndTime = document.getElementById('details-end-time');
                        const editDetailsBtn = document.getElementById('edit-details-btn');

                        if (detailsTitle) detailsTitle.textContent = task.title ?? '';
                        if (detailsDescription) detailsDescription.textContent = task.description ?? 'No description provided';
                        if (detailsDueDate) detailsDueDate.textContent = task.due_date ?? 'N/A';
                        if (detailsCreatedDate) detailsCreatedDate.textContent = formatDateTime(task.created_at);
                        if (detailsStartTime) detailsStartTime.textContent = task.start_time ? task.start_time.substring(0, 5) : '-';
                        if (detailsEndTime) detailsEndTime.textContent = task.end_time ? task.end_time.substring(0, 5) : '-';
                        
                        if (detailsCompletedAt) {
                            let completedDate = task.completed_at || task.complated_at || '';
                            detailsCompletedAt.textContent = completedDate ? formatDateTime(completedDate) : '-';
                        }

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
                        if (detailsCompleted) {
                            if (task.completed) {
                                detailsCompleted.innerHTML = '<span class="text-green-500">Completed</span>';
                            } else {
                                detailsCompleted.innerHTML = '<span class="text-red-500">Not Completed</span>';
                            }
                        }

                        // Attachments
                        const detailsAttachments = document.getElementById('details-attachments');
                        if (detailsAttachments) {
                            const attachments = Array.isArray(task.attachments) ? task.attachments : [];
                            if (!attachments.length) {
                                detailsAttachments.innerHTML = '<p class="col-span-2 text-gray-400">No attachments</p>';
                            } else {
                                detailsAttachments.innerHTML = attachments.map(att => {
                                    const originalName = att.original_name || att.filename || 'Attachment';
                                    const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                    const isImage = att.mime_type && att.mime_type.startsWith('image/') && att.storage_path;
                                    const imgHtml = isImage ? `<img src="${path}" class="w-12 h-12 object-cover rounded-md flex-shrink-0">` : '';
                                    const typeText = att.type ? `Type: ${att.type}` : (att.mime_type ? att.mime_type : '');
                                    
                                    return `
                                        <a href="${path}" target="_blank" class="flex items-center gap-4 p-3 bg-[#1A365D] rounded-xl border border-gray-600 shadow-sm hover:bg-[#254A7A] transition-colors group">
                                            ${imgHtml}
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-200 truncate group-hover:text-blue-400" title="${originalName}">${originalName}</div>
                                                <div class="text-gray-400 text-xs mt-1">${typeText}</div>
                                            </div>
                                        </a>
                                    `;
                                }).join('');
                            }
                        }

                        document.getElementById('task-details-modal').classList.remove('hidden');
                        if (editDetailsBtn) {
                            editDetailsBtn.dataset.task = taskId;
                            if (task.completed) {
                                editDetailsBtn.classList.add('hidden');
                            } else {
                                editDetailsBtn.classList.remove('hidden');
                            }
                        }
                    });
            }
        });

        // Edit Details button functionality (from details modal)
        document.getElementById('edit-details-btn').addEventListener('click', function() {
            const taskId = this.dataset.task;
            if (!taskId) return;
            
            // Trigger edit logic
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-task-id').value = data.id;
                    document.getElementById('edit-title').value = data.title || '';
                    document.getElementById('edit-description').value = data.description || '';
                    document.getElementById('edit-due-date').value = data.due_date || '';
                    document.getElementById('edit-start-time').value = data.start_time ? data.start_time.substring(0, 5) : '';
                    document.getElementById('edit-end-time').value = data.end_time ? data.end_time.substring(0, 5) : '';
                    
                    // Priority
                    const pr = data.priority || '';
                    const prSelect = document.getElementById('edit-priority');
                    const prLabel = document.getElementById('edit-priority-label');
                    
                    window.dispatchEvent(new CustomEvent('set-edit-priority', { 
                        detail: { priority: pr } 
                    }));

                    if (prSelect) prSelect.value = pr;
                    if (prLabel) prLabel.textContent = pr || 'Priority';

                    // Attachments
                    window.editTaskFiles = new DataTransfer();
                    const editFileInput = document.getElementById('edit-task-file-input');
                    const editFileList = document.getElementById('edit-file-list');
                    const editFileLabel = document.getElementById('edit-file-label');
                    if (editFileInput) editFileInput.value = '';
                    if (editFileList) editFileList.innerHTML = '';
                    if (editFileLabel) editFileLabel.textContent = 'Add New File';

                    const existingWrap = document.getElementById('edit-existing-attachments-wrap');
                    const existingContainer = document.getElementById('edit-existing-attachments');
                    if (existingWrap && existingContainer) {
                        const attachments = data.attachments || [];
                        if (attachments.length) {
                            existingWrap.classList.remove('hidden');
                            existingContainer.innerHTML = attachments.map(att => {
                                const originalName = att.original_name || att.filename || 'Attachment';
                                const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                const isImage = att.mime_type && att.mime_type.startsWith('image/') && att.storage_path;
                                const imgHtml = isImage ? `<img src="${path}" class="flex-shrink-0 object-cover w-12 h-12 rounded-md">` : '';
                                return `
                                    <div class="flex items-center gap-4 p-3 bg-[#1A365D] border border-gray-600 rounded-xl shadow-sm">
                                        <a href="${path}" target="_blank" class="flex items-center flex-1 min-w-0 gap-4 transition-opacity hover:opacity-80">
                                            ${imgHtml}
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-200 truncate" title="${originalName}">${originalName}</div>
                                                <div class="mt-1 text-xs text-gray-400">${att.mime_type || ''}</div>
                                            </div>
                                        </a>
                                        <button type="button" class="flex-shrink-0 ml-2 text-xl font-bold text-red-400 hover:text-red-600" onclick="
                                            const div = this.closest('.bg-[#1A365D]');
                                            div.style.display = 'none';
                                            const input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = 'remove_attachments[]';
                                            input.value = '${att.id}';
                                            div.parentNode.appendChild(input);
                                        ">&times;</button>
                                    </div>
                                `;
                            }).join('');
                        } else {
                            existingWrap.classList.add('hidden');
                        }
                    }

                    document.getElementById('task-details-modal').classList.add('hidden');
                    document.getElementById('edit-task-modal').classList.remove('hidden');
                });
        });

        // Close details modal
        document.getElementById('close-details-modal').addEventListener('click', () => {
            document.getElementById('task-details-modal').classList.add('hidden');
        });

        // Edit form submission
        document.getElementById('edit-task-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const taskId = document.getElementById('edit-task-id').value;
            const formData = new FormData(this);

            fetch(`/tasks/${taskId}`, {
                method: 'POST', // Use POST with _method=PATCH in formData or via headers if supported
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PATCH'
                },
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    updateTaskArrays(taskId, data.task.completed);
                    loadTasks(selectedDate);
                    document.getElementById('edit-task-modal').classList.add('hidden');
                    window.editTaskFiles = new DataTransfer();
                } else {
                    alert('Failed to update task');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Error updating task');
            });
        });

        // Close edit modal
        document.getElementById('close-edit-modal').addEventListener('click', () => {
            document.getElementById('edit-task-modal').classList.add('hidden');
            window.editTaskFiles = new DataTransfer();
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
