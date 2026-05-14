<x-app-layout>
    <div class="flex-1 min-w-0 items-center py-6 ml-20 border-white shadow-md bg-white/50 rounded-[40px] pt-6 mt-20">

        <h1 class="items-center mr-2 text-4xl font-bold text-center text-black font-poppins">Home</h1>

        <div class="pt-4 pl-10 pr-10 mx-auto max-w-7xl font-poppins">
            <div class="flex justify-center gap-8">

                <!-- Left Side: All My Task -->
                <div class="flex-1 min-w-0 p-8 bg-white shadow-xl rounded-3xl">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-3xl text-[#1C427A] font-bold">All My Task</h3>
                        <button type="button" id="add-task-btn"
                            class="add-task-btn flex items-center gap-2 px-6 py-2 text-sm font-bold font-poppins text-white bg-[#0E213D] shadow-md rounded-3xl focus:outline-none transition-transform duration-200 hover:scale-110">
                            <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="none" class="w-5 h-5"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill="#ffffff" fill-rule="evenodd" d="M9 17a1 1 0 102 0v-6h6a1 1 0 100-2h-6V3a1 1 0 10-2 0v6H3a1 1 0 000 2h6v6z"></path> </g></svg>
                            <span>New Task</span>
                        </button>
                    </div>

                    <!-- Today Section -->
                    <div class="mb-6">
                        <button class="flex items-center justify-between w-full mb-2 text-[#2F6ECB] font-bold text-xl category-toggle" data-category="today">
                            <span>Today</span>
                            <span class="flex items-center justify-center w-6 h-6 text-sm font-bold text-white bg-[#132C51] rounded-full">
                                {{ $todayCount }}
                            </span>
                        </button>
                        <div class="category-content" id="today-content">
                            <div class="space-y-4 text-lg text-[#132C51]">
                                @foreach ($todayTasks as $task)
                                    <div class="mb-2" data-original-due-date="{{ optional($task->due_date)->format('Y-m-d') }}">
                                        <div class="flex items-center ml-3 mr-8">
                                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="{{ $task->id }}" {{ $task->completed ? 'checked' : '' }}>
                                            <span data-task-title="{{ $task->id }}" class="{{ $task->completed ? 'line-through text-gray-500' : '' }} ml-4 flex-1 min-w-0 break-all cursor-pointer">
                                                <div class="flex flex-col">
                                                    <div class="flex flex-wrap items-center">
                                                        {{ $task->title }}
                                                        @if($task->priority == 'Urgent')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Urgent</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'High')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>High</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Normal')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Normal</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Low')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Low</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @endif
                                                        @foreach($task->workspaces as $ws)
                                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] ml-1.5 shadow-sm border border-[#1C427A]/10" title="{{ $ws->name }}">
                                                                {{ strtoupper(substr($ws->name, 0, 2)) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($task->start_time || $task->end_time)
                                                        <div class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <span>
                                                                {{ $task->start_time ? $task->start_time->format('H:i') : '' }}
                                                                {{ ($task->start_time && $task->end_time) ? '-' : '' }}
                                                                {{ $task->end_time ? $task->end_time->format('H:i') : '' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </span>
                                            <div class="relative ml-2">
                                                <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="{{ $task->id }}">⋯</button>
                                                <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                                        Duplicate
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tomorrow Section -->
                    <div class="mb-6">
                        <button class="flex items-center justify-between w-full mb-2 text-[#2F6ECB] font-bold text-xl category-toggle" data-category="tomorrow">
                            <span>Tomorrow</span>
                            <span class="flex items-center justify-center w-6 h-6 text-sm font-bold text-white bg-[#132C51] rounded-full">{{ $tomorrowCount }}</span>
                        </button>
                        <div class="category-content" id="tomorrow-content">
                            <div class="space-y-4 text-lg text-[#132C51]">
                                @foreach ($tomorrowTasks as $task)
                                    <div class="mb-2">
                                        <div class="flex items-center ml-3 mr-8">
                                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="{{ $task->id }}">
                                            <span data-task-title="{{ $task->id }}" class="flex-1 min-w-0 ml-4 break-all cursor-pointer">
                                                <div class="flex flex-col">
                                                    <div class="flex flex-wrap items-center">
                                                        {{ $task->title }}
                                                        @if($task->priority == 'Urgent')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Urgent</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'High')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>High</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Normal')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Normal</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Low')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Low</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @endif
                                                        @foreach($task->workspaces as $ws)
                                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] ml-1.5 shadow-sm border border-[#1C427A]/10" title="{{ $ws->name }}">
                                                                {{ strtoupper(substr($ws->name, 0, 2)) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($task->start_time || $task->end_time)
                                                        <div class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <span>
                                                                {{ $task->start_time ? $task->start_time->format('H:i') : '' }}
                                                                {{ ($task->start_time && $task->end_time) ? '-' : '' }}
                                                                {{ $task->end_time ? $task->end_time->format('H:i') : '' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </span>
                                            <div class="relative ml-2">
                                                <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="{{ $task->id }}">⋯</button>
                                                <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                                        Duplicate
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Section -->
                    <div class="mb-6">
                        <button class="flex items-center justify-between w-full mb-2 text-[#2F6ECB] font-bold text-xl category-toggle" data-category="upcoming">
                            <span>Upcoming</span>
                            <span class="flex items-center justify-center w-6 h-6 text-sm font-bold text-white bg-[#132C51] rounded-full">{{ $upcomingCount }}</span>
                        </button>
                        <div class="category-content" id="upcoming-content">
                            <div class="space-y-4 text-lg text-[#132C51]">
                                @foreach ($upcomingTasks as $task)
                                    <div class="mb-2">
                                        <div class="flex items-center ml-3 mr-8">
                                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="{{ $task->id }}">
                                            <span data-task-title="{{ $task->id }}" class="flex-1 min-w-0 ml-4 break-all cursor-pointer">
                                                <div class="flex flex-col">
                                                    <div class="flex flex-wrap items-center">
                                                        {{ $task->title }}
                                                        @if($task->priority == 'Urgent')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Urgent</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'High')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>High</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Normal')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Normal</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Low')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Low</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @endif
                                                        @foreach($task->workspaces as $ws)
                                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] ml-1.5 shadow-sm border border-[#1C427A]/10" title="{{ $ws->name }}">
                                                                {{ strtoupper(substr($ws->name, 0, 2)) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($task->start_time || $task->end_time)
                                                        <div class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <span>
                                                                {{ $task->start_time ? $task->start_time->format('H:i') : '' }}
                                                                {{ ($task->start_time && $task->end_time) ? '-' : '' }}
                                                                {{ $task->end_time ? $task->end_time->format('H:i') : '' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </span>
                                            <div class="relative ml-2">
                                                <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="{{ $task->id }}">⋯</button>
                                                <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                                        Duplicate
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Overdue Section -->
                    <div class="mb-6">
                        <button class="flex items-center justify-between w-full mb-2 text-xl font-bold text-red-600 category-toggle" data-category="overdue">
                            <span>Overdue</span>
                            <span class="flex items-center justify-center w-6 h-6 text-sm font-bold text-white bg-red-600 rounded-full">{{ $overdueCount }}</span>
                        </button>
                        <div class="category-content" id="overdue-content">
                            <div class="space-y-4 text-lg text-[#132C51]">
                                @foreach ($overdueTasks as $task)
                                    <div class="mb-2">
                                        <div class="flex items-center ml-3 mr-8">
                                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="{{ $task->id }}">
                                            <span data-task-title="{{ $task->id }}" class="flex-1 min-w-0 ml-4 break-all cursor-pointer">
                                                <div class="flex flex-col">
                                                    <div class="flex flex-wrap items-center">
                                                        {{ $task->title }}
                                                        @if($task->priority == 'Urgent')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Urgent</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'High')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>High</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Normal')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Normal</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Low')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Low</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @endif
                                                        @foreach($task->workspaces as $ws)
                                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] ml-1.5 shadow-sm border border-[#1C427A]/10" title="{{ $ws->name }}">
                                                                {{ strtoupper(substr($ws->name, 0, 2)) }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if($task->start_time || $task->end_time)
                                                        <div class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            <span>
                                                                {{ $task->start_time ? $task->start_time->format('H:i') : '' }}
                                                                {{ ($task->start_time && $task->end_time) ? '-' : '' }}
                                                                {{ $task->end_time ? $task->end_time->format('H:i') : '' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </span>
                                            <div class="relative ml-2">
                                                <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="{{ $task->id }}">⋯</button>
                                                <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        Edit
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                                        Duplicate
                                                    </button>
                                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">
                                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Side: History -->
                <div class="flex-1 min-w-0 p-8 bg-white shadow-xl rounded-3xl">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-3xl font-bold text-[#132C51]">History</h3>
                        <a href="{{ route('history.report') }}" target="_blank" class="flex items-center gap-2 px-6 py-2 text-sm font-bold font-poppins text-white bg-[#0E213D] shadow-md rounded-3xl focus:outline-none transition-transform duration-200 hover:scale-110">
                            <svg fill="#ffffff" viewBox="0 0 32 32" id="icon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style>.cls-1{fill:none;}</style></defs><title>report--alt</title><rect x="10" y="18" width="8" height="2"></rect><rect x="10" y="13" width="12" height="2"></rect><rect x="10" y="23" width="5" height="2"></rect><path d="M25,5H22V4a2,2,0,0,0-2-2H12a2,2,0,0,0-2,2V5H7A2,2,0,0,0,5,7V28a2,2,0,0,0,2,2H25a2,2,0,0,0,2-2V7A2,2,0,0,0,25,5ZM12,4h8V8H12ZM25,28H7V7h3v3H22V7h3Z"></path><rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;" class="cls-1" width="32" height="32"></rect></g></svg>
                            <span>Generate Report</span>
                        </a>
                    </div>
                    @foreach ($historyTasks as $task)
                        <div class="flex items-center mb-2 ml-3 mr-8">
                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="{{ $task->id }}" checked>
                            <span data-task-title="{{ $task->id }}" class="flex-1 min-w-0 ml-4 text-lg text-gray-500 line-through break-all cursor-pointer">
                                <div class="flex flex-col">
                                    <div class="flex flex-wrap items-center">
                                                        {{ $task->title }}
                                                        @if($task->priority == 'Urgent')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-red-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Urgent</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'High')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>High</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Normal')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Normal</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @elseif($task->priority == 'Low')
                                                            <svg class="flex-shrink-0 inline-block w-6 h-6 ml-2 text-green-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Low</title><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                                        @endif
                                                        @foreach($task->workspaces as $ws)
                                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] ml-1.5 shadow-sm border border-[#1C427A]/10" title="{{ $ws->name }}">
                                                                {{ strtoupper(substr($ws->name, 0, 2)) }}
                                                            </div>
                                                        @endforeach
                                    </div>
                                    @if($task->start_time || $task->end_time)
                                        <div class="flex items-center gap-1 text-xs text-gray-400 mt-0.5 no-line-through">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>
                                                {{ $task->start_time ? $task->start_time->format('H:i') : '' }}
                                                {{ ($task->start_time && $task->end_time) ? '-' : '' }}
                                                {{ $task->end_time ? $task->end_time->format('H:i') : '' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </span>
                            <div class="relative ml-2">
                                <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="{{ $task->id }}">⋯</button>
                                <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                    <button class="flex items-center w-full gap-3 px-4 py-2 text-sm text-red-500 rounded-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Add, Rename, Delete, Details) -->
    <!-- (Potongan modal Anda yang lain tetap di sini, pastikan tidak ada tag yang bocor) -->

    <!-- Task Details Modal (view only) -->
    <div id="task-details-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 bg-[#132C51] shadow-xl rounded-xl w-[850px] max-w-full my-8">
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

                    <!-- Row 3: Due Date, Created Date, Completed Date -->
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

                    <!-- Row 5: Workspaces -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4"></path></svg>
                            <label class="font-semibold text-gray-100">Workspaces</label>
                        </div>
                        <div id="details-workspaces" class="flex flex-wrap gap-2 text-sm text-gray-200">
                            <p class="text-gray-400">No workspaces assigned</p>
                        </div>
                    </div>

                    <!-- Row 6: Attachments -->
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
    </div>

    <!-- Edit Task Modal -->
    <div id="edit-task-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 shadow-xl rounded-xl w-[850px] bg-[#132C51] max-w-full my-8">
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
                            <div x-data="{ open: false, selected: null }" class="relative w-full">
                                <button @click="open = !open" type="button"
                                    class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] border border-gray-300 rounded-lg"
                                    :class="{
                                        'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
                                        'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
                                        'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
                                        'bg-green-50 border-green-300 text-green-700': selected === 'Low',
                                        'text-white border-gray-600': selected === null
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

                    <!-- Workspace Selection (Multiple) -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4"></path></svg>
                            <label class="font-semibold text-gray-100">Workspaces</label>
                        </div>
                        <div id="edit-workspaces-container" class="grid grid-cols-2 gap-2 p-3 border border-gray-600 rounded-lg bg-[#0C1F3B]">
                            @foreach($workspaces as $workspace)
                                <label class="flex items-start gap-2 cursor-pointer group">
                                    <input type="checkbox" name="workspace_ids[]" value="{{ $workspace->id }}" class="w-4 h-4 mt-0.5 rounded text-[#1C427A] focus:ring-[#1C427A] bg-gray-700 border-gray-600 edit-workspace-checkbox">
                                    <span class="text-sm text-gray-300 group-hover:text-white break-all whitespace-normal">{{ $workspace->name }}</span>
                                </label>
                            @endforeach
                            @if($workspaces->isEmpty())
                                <p class="col-span-2 text-xs text-gray-500 italic">No workspaces available. Create one first.</p>
                            @endif
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
    </div>    
    <!-- Add Task Modal -->
    <div id="add-task-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 shadow-xl rounded-xl w-[850px] bg-[#132C51] max-w-full my-8">
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
                            <div x-data="{ open: false, selected: null }" class="relative w-full">
                                <button @click="open = !open" type="button"
                                    class="flex items-center w-full gap-2 px-3 py-2 text-left bg-[#0C1F3B] border border-gray-300 rounded-lg"
                                    :class="{
                                        'bg-red-50 border-red-300 text-red-700': selected === 'Urgent',
                                        'bg-yellow-50 border-yellow-300 text-yellow-700': selected === 'High',
                                        'bg-blue-50 border-blue-300 text-blue-700': selected === 'Normal',
                                        'bg-green-50 border-green-300 text-green-700': selected === 'Low',
                                        'text-white border-gray-600': selected === null
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

                    <!-- Row 4: Workspace Selection (Multiple) -->
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4"></path></svg>
                            <label class="font-semibold text-gray-100">Workspaces</label>
                        </div>
                        <div class="grid grid-cols-2 gap-2 p-3 border border-gray-600 rounded-lg bg-[#0C1F3B]">
                            @foreach($workspaces as $workspace)
                                <label class="flex items-start gap-2 cursor-pointer group">
                                    <input type="checkbox" name="workspace_ids[]" value="{{ $workspace->id }}" class="w-4 h-4 mt-0.5 rounded text-[#1C427A] focus:ring-[#1C427A] bg-gray-700 border-gray-600">
                                    <span class="text-sm text-gray-300 group-hover:text-white break-all whitespace-normal">{{ $workspace->name }}</span>
                                </label>
                            @endforeach
                            @if($workspaces->isEmpty())
                                <p class="col-span-2 text-xs text-gray-500 italic">No workspaces available. Create one first.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Row 5: Attachments -->
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

            // Toggle Category Visibility
            document.querySelectorAll('.category-toggle').forEach(toggleBtn => {
                toggleBtn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    if (!category) return;
                    const contentDiv = document.getElementById(`${category}-content`);
                    if (contentDiv) {
                        contentDiv.classList.toggle('hidden');
                        if (contentDiv.classList.contains('hidden')) {
                            this.classList.add('opacity-50');
                        } else {
                            this.classList.remove('opacity-50');
                        }
                    }
                });
            });

            const addTaskBtns = document.querySelectorAll('.add-task-btn');
            const addTaskModal = document.getElementById('add-task-modal');
            const editTaskModal = document.getElementById('edit-task-modal');
            const taskDetailsModal = document.getElementById('task-details-modal');
            const editTaskForm = document.getElementById('edit-task-form');
            const editDetailsBtn = document.getElementById('edit-details-btn');
            const closeModal = document.getElementById('close-modal');
            const closeDetailsModal = document.getElementById('close-details-modal');
            const closeEditModalBtn = document.getElementById('close-edit-modal');

            // Handler ini dipakai untuk beberapa tombol jika ada (tetap aman).
            // Untuk memastikan tombol + New Task berfungsi, handler utama sudah dipasang di atas dengan id add-task-btn.
            if (addTaskBtns.length > 0 && addTaskModal) {
                addTaskBtns.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        // Show the modal and reset form
                        addTaskModal.classList.remove('hidden');
                        window.newTaskFiles = new DataTransfer();
                        const fileInput = document.getElementById('task-file-input');
                        const fileList = document.getElementById('file-list');
                        const label = document.getElementById('file-label');
                        if (fileInput) fileInput.value = '';
                        if (fileList) fileList.innerHTML = '';
                        if (label) label.textContent = 'Add File';
                    });
                });
            }


            if (closeModal && addTaskModal) {
                closeModal.addEventListener('click', () => {
                    addTaskModal.classList.add('hidden');
                });
            }

            // Click outside modal to close
            if (addTaskModal) {
                addTaskModal.addEventListener('click', (e) => {
                    if (e.target === addTaskModal) {
                        addTaskModal.classList.add('hidden');
                    }
                });
            }

            // Task Checkbox completion/uncompletion
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
                        body: JSON.stringify({ completed: isCompleted })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Failed to update task.');
                            checkbox.checked = !isCompleted;
                        }
                    })
                    .catch(() => {
                        alert('Error updating task.');
                        checkbox.checked = !isCompleted;
                    });
                }
            });

            // ---- FIX "TITIK TIGA" (task menu) ----
            // Toggle menu per task and close all menus when clicking outside.
            document.addEventListener('click', function(e) {
                const taskMenuBtn = e.target.closest('.task-menu-btn');

                // 1) Klik tombol titik tiga
                if (taskMenuBtn) {
                    const taskId = taskMenuBtn.dataset.task;
                    const menu = document.querySelector(`.task-menu[data-task="${taskId}"]`);
                    if (!menu) return;

                    const isHidden = menu.classList.contains('hidden');

                    // Tutup semua menu terlebih dahulu
                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));

                    // Jika sebelumnya tersembunyi, maka tampilkan
                    if (isHidden) {
                        menu.classList.remove('hidden');
                    }
                    return;
                }

                // 2) Klik di luar menu
                if (!e.target.closest('.task-menu')) {
                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
                }
            });

            // Menu actions: Duplicate, Delete, Edit
            document.addEventListener('click', function(e) {

                // Edit (open edit modal)
                const editBtn = e.target.closest('.edit-btn');
                if (editBtn) {
                    const taskId = editBtn.dataset.task;
                    if (!taskId) return;

                    // Hide details modal if open
                    taskDetailsModal?.classList.add('hidden');

                    // Show edit modal
                    editTaskModal?.classList.remove('hidden');
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Pre-fill form
                    fetch(`/tasks/${taskId}`)
                        .then(r => r.json())
                        .then(task => {
                            const editTaskIdEl = document.getElementById('edit-task-id');
                            const editTitleEl = document.getElementById('edit-title');
                            const editDescriptionEl = document.getElementById('edit-description');
                            const editDueDateEl = document.getElementById('edit-due-date');
                            const editPrioritySelect = document.getElementById('edit-priority');
                            const editPriorityLabel = document.getElementById('edit-priority-label');

                            if (editTaskIdEl) editTaskIdEl.value = task.id;
                            if (editTitleEl) editTitleEl.value = task.title ?? '';
                            if (editDescriptionEl) editDescriptionEl.value = task.description ?? '';
                            if (editDueDateEl) editDueDateEl.value = task.due_date ?? '';
                            
                            const editStartTimeEl = document.getElementById('edit-start-time');
                            const editEndTimeEl = document.getElementById('edit-end-time');
                            if (editStartTimeEl) editStartTimeEl.value = task.start_time ? task.start_time.substring(0, 5) : '';
                            if (editEndTimeEl) editEndTimeEl.value = task.end_time ? task.end_time.substring(0, 5) : '';

                            const pr = task.priority ?? '';

                            // Ensure Alpine x-data `selected` is updated as the source of truth
                            // (jika Alpine belum booting atau structure berbeda, lakukan fallback + trigger event)
                            const alpineRoot = editPrioritySelect?.closest('[x-data]');
                            if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data) {
                                alpineRoot.__x.$data.selected = pr || null;
                                // paksa update tampilan
                                alpineRoot.__x.$data.$nextTick?.(() => {});
                            }

                            // Fallback: set value select + trigger change (agar x-model mengikuti)
                            if (editPrioritySelect) {
                                editPrioritySelect.value = pr;
                                editPrioritySelect.dispatchEvent(new Event('input', { bubbles: true }));
                                editPrioritySelect.dispatchEvent(new Event('change', { bubbles: true }));
                            }

                            // UI label update (untuk reliability walau Alpine belum sempat me-render)
                            if (editPriorityLabel) editPriorityLabel.textContent = pr || 'Priority';

                            // Update form action
                            if (editTaskForm) editTaskForm.action = `/tasks/${taskId}`;

                            // Reset file input preview
                            window.editTaskFiles = new DataTransfer();
                            const editFileInput = document.getElementById('edit-task-file-input');
                            const editFileList = document.getElementById('edit-file-list');
                            const editFileLabel = document.getElementById('edit-file-label');
                            if (editFileInput) editFileInput.value = '';
                            if (editFileList) editFileList.innerHTML = '';
                            if (editFileLabel) editFileLabel.textContent = 'Add File';

                            // Attachments existing preview with remove option
                            const existingWrap = document.getElementById('edit-existing-attachments-wrap');
                            const existingContainer = document.getElementById('edit-existing-attachments');
                            if (existingWrap && existingContainer) {
                                const existingAttachments = task.attachments && Array.isArray(task.attachments) ? task.attachments : [];

                                if (existingAttachments.length) {
                                    existingWrap.classList.remove('hidden');
                                    existingContainer.innerHTML = existingAttachments.map(att => {
                                        const originalName = att.original_name || att.filename || 'Attachment';
                                        const id = att.id;
                                        const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                        
                                        const isImage = att.mime_type && att.mime_type.startsWith('image/') && att.storage_path;
                                        const imgHtml = isImage ? `<img src="${path}" class="flex-shrink-0 object-cover w-12 h-12 rounded-md">` : '';

                                        return `
                                            <div class="flex items-center gap-4 p-3 bg-[#1A365D] border border-gray-600 rounded-xl shadow-sm">
                                                <a href="${path}" target="_blank" class="flex items-center flex-1 gap-4 min-w-0 hover:opacity-80 transition-opacity">
                                                    ${imgHtml}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-gray-200 truncate" title="${originalName}">${originalName}</div>
                                                        <div class="mt-1 text-xs text-gray-400">${att.mime_type || att.type || ''}</div>
                                                    </div>
                                                </a>
                                                <button type="button" class="flex-shrink-0 text-xl font-bold leading-none text-red-400 hover:text-red-600 ml-2" onclick="
                                                    const div = this.closest('.border-gray-600');
                                                    div.style.display = 'none';
                                                    const input = document.createElement('input');
                                                    input.type = 'hidden';
                                                    input.name = 'remove_attachments[]';
                                                    input.value = '${id}';
                                                    div.parentNode.appendChild(input);
                                                ">&times;</button>
                                            </div>
                                        `;
                                    }).join('');
                                } else {
                                    existingWrap.classList.add('hidden');
                                    existingContainer.innerHTML = '';
                                }
                            }

                            // Workspaces: check corresponding checkboxes
                            const workspaceCheckboxes = document.querySelectorAll('.edit-workspace-checkbox');
                            workspaceCheckboxes.forEach(cb => cb.checked = false);
                            if (task.workspaces && Array.isArray(task.workspaces)) {
                                task.workspaces.forEach(ws => {
                                    const cb = document.querySelector(`.edit-workspace-checkbox[value="${ws.id}"]`);
                                    if (cb) cb.checked = true;
                                });
                            }
                        })
                        .catch(() => alert('Failed to load task details'));

                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
                    return;
                }

                // Duplicate
                const duplicateBtn = e.target.closest('.duplicate-btn');
                if (duplicateBtn) {
                    const taskId = duplicateBtn.dataset.task;
                    fetch(`/tasks/${taskId}/duplicate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload());

                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
                    return;
                }

                // Delete
                const deleteBtn = e.target.closest('.delete-btn');
                if (deleteBtn) {
                    const taskId = deleteBtn.dataset.task;
                    if (!confirm('Are you sure you want to delete this task?')) return;

                    fetch(`/tasks/${taskId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => location.reload());

                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
                    return;
                }

            // Reusable function to show task details
            window.showTaskDetails = function(taskId) {
                if (!taskId) return;
                
                if (editDetailsBtn) editDetailsBtn.dataset.taskId = String(taskId);
                
                const prLabel = document.getElementById('edit-priority-label');
                if (prLabel) prLabel.textContent = 'Priority';

                const editForm = document.getElementById('edit-task-form');
                if (editForm) editForm.action = `/tasks/${taskId}`;

                fetch(`/tasks/${taskId}`)
                    .then(r => r.json())
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

                        if (detailsTitle) detailsTitle.textContent = task.title ?? '';
                        if (detailsDescription) detailsDescription.textContent = task.description ?? '';
                        if (detailsDueDate) detailsDueDate.textContent = task.due_date ?? '';
                        if (detailsCreatedDate) detailsCreatedDate.textContent = formatDateTime(task.created_at);
                        if (detailsStartTime) detailsStartTime.textContent = task.start_time ? task.start_time.substring(0, 5) : '-';
                        if (detailsEndTime) detailsEndTime.textContent = task.end_time ? task.end_time.substring(0, 5) : '-';
                        if (detailsCompletedAt) {
                            let completedDate = task.completed_at || task.complated_at || '';
                            detailsCompletedAt.textContent = completedDate ? formatDateTime(completedDate) : '-';
                        }

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
                            detailsCompleted.innerHTML = task.completed ? 
                                '<span class="text-green-500 font-bold">Completed</span>' : 
                                '<span class="text-red-500 font-bold">Not Completed</span>';
                        }

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
                                    return `
                                        <a href="${path}" target="_blank" class="flex items-center gap-4 p-3 bg-[#1A365D] rounded-xl border border-gray-600 shadow-sm hover:bg-[#254A7A] transition-colors group">
                                            ${imgHtml}
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-200 truncate group-hover:text-blue-400" title="${originalName}">${originalName}</div>
                                                <div class="text-gray-400 text-xs mt-1">${att.mime_type || att.type || ''}</div>
                                            </div>
                                        </a>
                                    `;
                                }).join('');
                            }
                        }

                        if (taskDetailsModal) taskDetailsModal.classList.remove('hidden');
                        if (editDetailsBtn) {
                            task.completed ? editDetailsBtn.classList.add('hidden') : editDetailsBtn.classList.remove('hidden');
                        }
                    })
                    .catch(() => alert('Failed to load task details'));
            };

            // Global listener for search details
            window.addEventListener('open-task-details', (e) => {
                window.showTaskDetails(e.detail.taskId);
            });

            // Handle URL param open_task
            const urlParams = new URLSearchParams(window.location.search);
            const openTaskId = urlParams.get('open_task');
            if (openTaskId) {
                window.showTaskDetails(openTaskId);
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Open Task Details Modal (view-only) when clicking the task title
            const taskTitleEl = e.target.closest('[data-task-title]');
            if (taskTitleEl) {
                window.showTaskDetails(taskTitleEl.dataset.taskTitle);
                document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
            }
            });

            // Close detail modal

            if (closeDetailsModal && taskDetailsModal) {
                closeDetailsModal.addEventListener('click', () => {
                    taskDetailsModal.classList.add('hidden');
                });
            }

            // ---- Edit task modal (from details modal) ----


            if (editDetailsBtn && editTaskModal && editTaskForm) {
                editDetailsBtn.addEventListener('click', () => {
                    const taskTitleEl = document.getElementById('details-title');
                    // details title is view-only; we rely on current fetched task by storing id on button dataset
                    // (we set it when opening details)

                    const taskId = editDetailsBtn.dataset.taskId;
                    if (!taskId) {
                        alert('Task id not found');
                        return;
                    }

                    // Pre-fill form
                    fetch(`/tasks/${taskId}`)
                        .then(r => r.json())
                        .then(task => {
                            document.getElementById('edit-task-id').value = task.id;
                            document.getElementById('edit-title').value = task.title ?? '';
                            document.getElementById('edit-description').value = task.description ?? '';
                            document.getElementById('edit-due-date').value = task.due_date ?? '';
                            document.getElementById('edit-start-time').value = task.start_time ? task.start_time.substring(0, 5) : '';
                            document.getElementById('edit-end-time').value = task.end_time ? task.end_time.substring(0, 5) : '';

                            // priority dropdown label + value
                            const prSelect = document.getElementById('edit-priority');
                            const prLabel = document.getElementById('edit-priority-label');
                            const pr = task.priority ?? '';

                            // Sync Alpine selected (source of truth) + fallback trigger events
                            const alpineRoot2 = prSelect?.closest('[x-data]');
                            if (alpineRoot2 && alpineRoot2.__x && alpineRoot2.__x.$data) {
                                alpineRoot2.__x.$data.selected = pr || null;
                            }

                            if (prSelect) {
                                prSelect.value = pr;
                                prSelect.dispatchEvent(new Event('input', { bubbles: true }));
                                prSelect.dispatchEvent(new Event('change', { bubbles: true }));
                            }

                            if (prLabel) prLabel.textContent = pr || 'Priority';

                            // Reset file input preview
                            window.editTaskFiles = new DataTransfer();
                            const editFileInput = document.getElementById('edit-task-file-input');
                            const editFileList = document.getElementById('edit-file-list');
                            const editFileLabel = document.getElementById('edit-file-label');
                            if (editFileInput) editFileInput.value = '';
                            if (editFileList) editFileList.innerHTML = '';
                            if (editFileLabel) editFileLabel.textContent = 'Add File';

                            // Attachments existing preview with remove option (hapus attachment lama) via checkbox
                            const existingAttachments = task.attachments && Array.isArray(task.attachments) ? task.attachments : [];
                            const existingWrap = document.getElementById('edit-existing-attachments-wrap');
                            const existingContainer = document.getElementById('edit-existing-attachments');

                            if (existingWrap && existingContainer) {
                                if (existingAttachments.length) {
                                    existingWrap.classList.remove('hidden');
                                    existingContainer.innerHTML = existingAttachments.map(att => {
                                        const originalName = att.original_name || att.filename || 'Attachment';
                                        const id = att.id;
                                        const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                        
                                        const isImage = att.mime_type && att.mime_type.startsWith('image/') && att.storage_path;
                                        const imgHtml = isImage ? `<img src="${path}" class="flex-shrink-0 object-cover w-12 h-12 rounded-md">` : '';

                                        return `
                                            <div class="flex items-center gap-4 p-3 bg-[#1A365D] border border-gray-600 rounded-xl shadow-sm">
                                                <a href="${path}" target="_blank" class="flex items-center flex-1 gap-4 min-w-0 hover:opacity-80 transition-opacity">
                                                    ${imgHtml}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-medium text-gray-200 truncate" title="${originalName}">${originalName}</div>
                                                        <div class="mt-1 text-xs text-gray-400">${att.mime_type || att.type || ''}</div>
                                                    </div>
                                                </a>
                                                <button type="button" class="flex-shrink-0 text-xl font-bold leading-none text-red-400 hover:text-red-600 ml-2" onclick="
                                                    const div = this.closest('.border-gray-600');
                                                    div.style.display = 'none';
                                                    const input = document.createElement('input');
                                                    input.type = 'hidden';
                                                    input.name = 'remove_attachments[]';
                                                    input.value = '${id}';
                                                    div.parentNode.appendChild(input);
                                                ">&times;</button>
                                            </div>
                                        `;
                                    }).join('');
                                } else {
                                    existingWrap.classList.add('hidden');
                                    existingContainer.innerHTML = '';
                                }
                            }


                    // Show edit modal
                            taskDetailsModal.classList.add('hidden');
                            editTaskModal.classList.remove('hidden');
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        });
                });
            }

            // Close edit modal
            if (closeEditModalBtn && editTaskModal) {
                closeEditModalBtn.addEventListener('click', () => {
                    editTaskModal.classList.add('hidden');
                });
            }

            if (editTaskModal) {
                editTaskModal.addEventListener('click', (e) => {
                    if (e.target === editTaskModal) {
                        editTaskModal.classList.add('hidden');
                    }
                });
            }


            // Click outside to close detail modal
            if (taskDetailsModal) {
                taskDetailsModal.addEventListener('click', (e) => {
                    if (e.target === taskDetailsModal) {
                        taskDetailsModal.classList.add('hidden');
                    }
                });
            }

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
                        label.textContent = 'Add File';
                    }
                });
            }
        });
    </script>

</x-app-layout>

