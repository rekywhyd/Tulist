<x-app-layout>
    <div class="min-h-full items-center mr-8 py-6 ml-20 border-white shadow-md bg-white/50 rounded-[40px] pt-6 mt-20">

        <h1 class="items-center mr-2 text-4xl font-bold text-center text-black font-poppins">Home</h1>

        <div class="p-6 mx-auto max-w-7xl font-poppins">
            <div class="flex justify-center gap-10">

                <!-- Left Side: All My Task -->
                <div class="w-[47%] bg-white shadow-xl rounded-3xl p-8">
                    <h3 class="mb-10 text-3xl text-[#1C427A] font-bold">All My Task</h3>

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
                                            <span data-task-title="{{ $task->id }}" class="{{ $task->completed ? 'line-through text-gray-500' : '' }} ml-4 flex-1 cursor-pointer">
                                                {{ $task->title }}
                                            </span>
                                            <div class="relative ml-2">
                                                <button class="text-gray-500 task-menu-btn hover:text-gray-700" data-task="{{ $task->id }}">⋯</button>
                                                <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                                    <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="{{ $task->id }}">Edit</button>
                                                    <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="{{ $task->id }}">Duplicate</button>
                                                    <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">Delete</button>
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
                                            <span class="flex-1 ml-4">{{ $task->title }}</span>
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
                                            <span class="flex-1 ml-4">{{ $task->title }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button id="add-task-btn" class="px-2 flex text-lg transition duration-200 hover:scale-105 font-medium items-center gap-6 mt-16 py-2 text-[#2658A2]">
                        <svg class="w-12 h-12" viewBox="0 0 20 20" fill="currentColor"><path d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM11 11H6v-2h5V4h2v5h5v2h-5v5h-2v-5z"/></svg>
                        Add task or reminder
                    </button>
                </div>

                <!-- Right Side: History -->
                <div class="w-[47%] bg-white shadow-xl rounded-3xl p-8">
                    <h3 class="mb-10 text-3xl font-bold text-[#132C51]">History</h3>
                    @foreach ($historyTasks as $task)
                        <div class="flex items-center mb-2 ml-2 space-x-6">
                            <span class="w-4 h-4 bg-[#132C51] rounded-full"></span>
                            <span class="text-lg text-gray-500 line-through">{{ $task->title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Add, Rename, Delete, Details) -->
    <!-- (Potongan modal Anda yang lain tetap di sini, pastikan tidak ada tag yang bocor) -->

    <!-- Task Details Modal (view only) -->
    <div id="task-details-modal" class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50 font-poppins">
        <div class="absolute p-5 mx-auto -translate-x-1/2 -translate-y-1/2 bg-[#132C51] shadow-xl top-1/2 left-1/2 rounded-xl w-[500px]">
            <div class="mt-3">
                <h3 class="mb-4 text-lg font-semibold text-white">Task Details</h3>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Title</label>
                    <p id="details-title" class="text-gray-200 break-words"></p>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Description</label>
                    <p id="details-description" class="text-gray-200 break-words"></p>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Due Date</label>
                    <p id="details-due-date" class="text-gray-200"></p>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Created Date</label>
                    <p id="details-created-date" class="text-gray-200"></p>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Completed Date</label>
                    <p id="details-completed-at" class="text-gray-200"></p>

                </div>



                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Priority</label>
                    <p id="details-priority" class="font-semibold text-gray-200"></p>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Status</label>
                    <p id="details-completed" class="text-gray-200"></p>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-gray-100">Attachments</label>
                    <div id="details-attachments" class="space-y-2 text-sm text-gray-200">
                        <p class="text-gray-400">No attachments</p>
                    </div>
                </div>


                <div class="flex justify-center gap-4 mt-6 font-medium">
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

    <!-- Edit Task Modal -->
    <div id="edit-task-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 shadow-xl rounded-xl w-[600px] bg-[#132C51]">
            <div class="mt-3">
                <h3 class="mb-8 text-2xl font-semibold text-white">Edit Task</h3>

                <form id="edit-task-form" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="task_id" id="edit-task-id">

                    <div class="mb-5">
                        <input id="edit-title" placeholder="Title Name" type="text" name="title"
                            class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg"
                            required>
                    </div>

                    <div class="mb-4">
                        <textarea id="edit-description" placeholder="Add Description" name="description"
                            class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 mb-5">
                        <input id="edit-due-date" type="date" name="due_date"
                            class="w-[49%] text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                            required>

                        <div x-data="{ open: false, selected: null }" class="relative w-[49%]">
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

                    {{-- Add File --}}
                    <div id="edit-existing-attachments-wrap" class="hidden mb-5">
                        <div class="mb-2">
                            <label class="block mb-2 font-semibold text-gray-100">Existing Attachments (optional remove)</label>
                            <div id="edit-existing-attachments" class="space-y-2"></div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="flex items-center justify-center w-full gap-2 px-3 py-2 text-white transition-transform duration-200 border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="edit-file-label">Add File</span>
                            <input type="file" name="attachments[]" multiple class="hidden" id="edit-task-file-input" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        </label>
                        <div id="edit-file-list" class="mt-2 space-y-1 text-sm text-gray-300"></div>
                    </div>

                    <div class="flex justify-center gap-6 mt-8 font-medium">
                        <button type="button" id="close-edit-modal" class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 rounded-3xl hover:hover:scale-95">Cancel</button>
                        <button type="submit" class="transition-transform duration-200 hover:hover:scale-110 px-5 py-1 text-white bg-[#1C427A] rounded-3xl">Save</button>
                    </div>

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
                </form>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="add-task-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 shadow-xl rounded-xl w-[600px] bg-[#132C51]">
            <div class="mt-3">
                <h3 class="mb-8 text-2xl font-semibold text-white">New Task</h3>
                <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-5">
                        <input placeholder="Title Name" type="text" name="title"
                            class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg"
                            required>
                    </div>
                    <div class="mb-4">
                        <textarea placeholder="Add Description" name="description"
                            class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 mb-5">
                        <input type="date" name="due_date"
                            class="w-[49%] text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]"
                            required value="{{ date('Y-m-d') }}">

                        <div x-data="{ open: false, selected: null }" class="relative w-[49%]">
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
                                <div @click="selected = null; open = false" class="flex items-center gap-2 p-2 rounded-md cursor-pointer hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addTaskBtn = document.getElementById('add-task-btn');
            const addTaskModal = document.getElementById('add-task-modal');
            const closeModal = document.getElementById('close-modal');

            if (addTaskBtn && addTaskModal) {
                addTaskBtn.addEventListener('click', () => {
                    addTaskModal.classList.remove('hidden');
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

            // ---- FIX "TITIK TIGA" (task menu) ----
            // Toggle menu per task and close all menus when clicking outside.
            document.addEventListener('click', function(e) {
                const taskMenuBtn = e.target.closest('.task-menu-btn');

                // 1) Klik tombol titik tiga
                if (taskMenuBtn) {
                    const taskId = taskMenuBtn.dataset.task;
                    const menu = document.querySelector(`.task-menu[data-task="${taskId}"]`);
                    if (!menu) return;

                    // Tutup semua menu terlebih dahulu
                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));

                    // Buka menu yang diklik
                    menu.classList.remove('hidden');
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

                            const pr = task.priority ?? '';
                            if (editPrioritySelect) editPrioritySelect.value = pr;
                            if (editPriorityLabel) editPriorityLabel.textContent = pr || 'Priority';

                            // Update form action
                            if (editTaskForm) editTaskForm.action = `/tasks/${taskId}`;

                            // Reset file input preview
                            const editFileInput = document.getElementById('edit-task-file-input');
                            const editFileList = document.getElementById('edit-file-list');
                            if (editFileInput) editFileInput.value = '';
                            if (editFileList) editFileList.innerHTML = '';

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
                                        return `
                                            <div class="flex items-start justify-between gap-3 px-3 py-2 text-sm text-gray-200 border border-gray-600 rounded-lg">
                                                <div class="min-w-0">
                                                    <div class="font-medium break-words">${originalName}</div>
                                                    <div class="text-xs text-gray-400">${att.mime_type || att.type || ''}</div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <label class="flex items-center gap-2 text-gray-200 cursor-pointer">
                                                        <input type="checkbox" name="remove_attachments[]" value="${id}" class="accent-blue-500" />
                                                        Remove
                                                    </label>
                                                </div>
                                            </div>
                                        `;
                                    }).join('');
                                } else {
                                    existingWrap.classList.add('hidden');
                                    existingContainer.innerHTML = '';
                                }
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

                // Open Task Details Modal (view-only) when clicking the task title
                const taskTitleEl = e.target.closest('[data-task-title]');
                if (taskTitleEl) {
                    const taskId = taskTitleEl.dataset.taskTitle;

                const editDetailsBtn = document.getElementById('edit-details-btn');
                if (editDetailsBtn) editDetailsBtn.dataset.taskId = String(taskId);

                // reset priority UI label to avoid displaying unexpected values
                const prLabel = document.getElementById('edit-priority-label');
                if (prLabel) prLabel.textContent = 'Priority';


                // update edit form action to tasks/{id}
                const editTaskForm = document.getElementById('edit-task-form');
                if (editTaskForm) editTaskForm.action = `/tasks/${taskId}`;


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

                            if (detailsTitle) detailsTitle.textContent = task.title ?? '';
                            if (detailsDescription) detailsDescription.textContent = task.description ?? '';
                            if (detailsDueDate) detailsDueDate.textContent = task.due_date ?? '';
                            if (detailsCreatedDate) detailsCreatedDate.textContent = task.created_at ?? '';
                            if (detailsCompletedAt) detailsCompletedAt.textContent = task.completed_at ?? '';
                            // fallback (jika kolom typo/beda penamaan di API)
                            if (detailsCompletedAt && (task.completed_at ?? '') === '' && (task.complated_at ?? '')) {
                                detailsCompletedAt.textContent = task.complated_at ?? '';
                            }
                            if (detailsPriority) detailsPriority.textContent = task.priority ?? '';
                            if (detailsCompleted) detailsCompleted.textContent = task.completed ? 'Completed' : 'Not Completed';

                            // Attachments
                            const detailsAttachments = document.getElementById('details-attachments');
                            if (detailsAttachments) {
                                const attachments = Array.isArray(task.attachments) ? task.attachments : [];

                                if (!attachments.length) {
                                    detailsAttachments.innerHTML = '<p class="text-gray-400">No attachments</p>';
                                } else {
                                    detailsAttachments.innerHTML = '';
                                    attachments.forEach(att => {
                                        // Set Created Date location aligned with Due Date (task.due_date)
                                        // because user wants it to appear the same as the task's due date.
                                        const createdAt = task?.due_date ?? '';
                                        const originalName = att.original_name || att.filename || 'Attachment';

                                        const row = document.createElement('div');
                                        row.className = 'flex items-start justify-between gap-4';

                                        const left = document.createElement('div');
                                        left.className = 'flex-1 min-w-0';

                                        const nameEl = document.createElement('div');
                                        nameEl.className = 'font-medium text-gray-200 break-words';
                                        nameEl.textContent = originalName;

                                        const typeEl = document.createElement('div');
                                        typeEl.className = 'text-gray-400 text-xs mt-1';
                                        typeEl.textContent = att.type ? `Type: ${att.type}` : (att.mime_type ? att.mime_type : '');

                                        left.appendChild(nameEl);
                                        if (typeEl.textContent) left.appendChild(typeEl);

                                        const right = document.createElement('div');
                                        right.className = 'text-right whitespace-nowrap';
                                        const createdEl = document.createElement('div');
                                        createdEl.className = 'hidden';
                                        createdEl.textContent = '';
                                        right.appendChild(createdEl);

                                        row.appendChild(left);
                                        row.appendChild(right);

                                        detailsAttachments.appendChild(row);

                                        // If it's an image, show a small thumbnail
                                        if (att.mime_type && att.mime_type.startsWith('image/') && att.storage_path) {
                                            const img = document.createElement('img');
                                            img.className = 'mt-2 w-24 h-24 object-cover rounded';
                                            img.src = `/storage/${att.storage_path}`;
                                            row.appendChild(img);
                                        }
                                    });
                                }
                            }

                            const modal = document.getElementById('task-details-modal');
                            if (modal) modal.classList.remove('hidden');
                        })
                        .catch(() => alert('Failed to load task details'));

                    document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
                }
            });

            // Close detail modal
            const closeDetailsModal = document.getElementById('close-details-modal');
            const taskDetailsModal = document.getElementById('task-details-modal');
            if (closeDetailsModal && taskDetailsModal) {
                closeDetailsModal.addEventListener('click', () => {
                    taskDetailsModal.classList.add('hidden');
                });
            }

            // ---- Edit task modal (from details modal) ----
            const editDetailsBtn = document.getElementById('edit-details-btn');
            const editTaskModal = document.getElementById('edit-task-modal');
            const editTaskForm = document.getElementById('edit-task-form');
            const closeEditModalBtn = document.getElementById('close-edit-modal');

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

                            // priority dropdown label + value
                            const prSelect = document.getElementById('edit-priority');
                            const prLabel = document.getElementById('edit-priority-label');
                            const pr = task.priority ?? '';
                            if (prSelect) prSelect.value = pr;
                            if (prLabel) prLabel.textContent = pr || 'Priority';

                            // Reset file input preview
                            const editFileInput = document.getElementById('edit-task-file-input');
                            const editFileList = document.getElementById('edit-file-list');
                            if (editFileInput) editFileInput.value = '';
                            if (editFileList) editFileList.innerHTML = '';

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
                                        return `
                                            <div class="flex items-start justify-between gap-3 px-3 py-2 text-sm text-gray-200 border border-gray-600 rounded-lg">
                                                <div class="min-w-0">
                                                    <div class="font-medium break-words">${originalName}</div>
                                                    <div class="text-xs text-gray-400">${att.mime_type || att.type || ''}</div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <label class="flex items-center gap-2 text-gray-200 cursor-pointer">
                                                        <input type="checkbox" name="remove_attachments[]" value="${id}" class="accent-blue-500" />
                                                        Remove
                                                    </label>
                                                </div>
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
        });
    </script>

</x-app-layout>

