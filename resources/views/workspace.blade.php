<x-app-layout>
    <div class="min-h-full items-center ml-20 py-6 border-white shadow-md bg-white/50 rounded-[40px] pt-6 mt-20 overflow-x-hidden">
        <h1 class="items-center mr-2 text-4xl font-bold text-center text-black font-poppins">Workspaces</h1>

        @if(session('success'))
            <div class="px-4 py-3 mx-10 mt-4 text-green-800 bg-green-100 border border-green-300 rounded-xl font-poppins" id="flash-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="px-4 py-3 mx-10 mt-4 text-red-800 bg-red-100 border border-red-300 rounded-xl font-poppins" id="flash-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="pt-4 pl-10 pr-10 mx-auto max-w-7xl font-poppins">
            <div class="flex justify-center gap-8">
                {{-- LEFT SIDE: Workspace List --}}
                <div class="flex-1 min-w-0 p-6 bg-white shadow-xl rounded-3xl" style="max-width: 340px;">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl text-[#1C427A] font-bold">My Workspaces</h3>
                        <button type="button" id="create-workspace-btn"
                            class="p-2 text-white bg-[#0E213D] rounded-full transition-transform duration-200 hover:scale-110" title="Create Workspace">
                            <svg viewBox="0 0 20 20" fill="none" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#ffffff" fill-rule="evenodd" d="M9 17a1 1 0 102 0v-6h6a1 1 0 100-2h-6V3a1 1 0 10-2 0v6H3a1 1 0 000 2h6v6z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2 overflow-y-auto max-h-[60vh] pr-1" id="workspace-list">
                        @forelse($workspaces as $ws)
                            <a href="{{ route('workspace', ['workspace_id' => $ws->id]) }}"
                               class="flex items-center gap-3 p-3 transition-all duration-200 rounded-2xl group
                                   {{ $selectedWorkspace && $selectedWorkspace->id === $ws->id
                                       ? 'bg-[#0E213D] text-white shadow-lg'
                                       : 'hover:bg-[#E8EEF9] text-[#132C51]' }}">
                                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-bold rounded-xl
                                    {{ $selectedWorkspace && $selectedWorkspace->id === $ws->id ? 'bg-[#1C427A] text-white' : 'bg-[#E8EEF9] text-[#1C427A]' }}">
                                    {{ strtoupper(substr($ws->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold truncate">{{ $ws->name }}</div>
                                    <div class="text-xs opacity-60">{{ $ws->members_count }} {{ $ws->members_count === 1 ? 'member' : 'members' }}</div>
                                </div>
                                @if(isset($ws->unread_tasks_count) && $ws->unread_tasks_count > 0)
                                    <div class="flex items-center justify-center min-w-[24px] h-[24px] text-[10px] font-bold text-white bg-red-500 rounded-full shadow-lg border-2 border-white animate-pulse" title="{{ $ws->unread_tasks_count }} new tasks">
                                        {{ $ws->unread_tasks_count > 99 ? '99+' : $ws->unread_tasks_count }}
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="py-8 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-sm text-gray-400">No workspaces yet</p>
                                <p class="mt-1 text-xs text-gray-300">Create one to get started!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- RIGHT SIDE: Workspace Content --}}
                <div class="flex-[2] min-w-0 p-8 bg-white shadow-xl rounded-3xl">
                    @if($selectedWorkspace)
                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-8 gap-4">
                            <div class="min-w-0">
                                <h3 class="text-3xl text-[#1C427A] font-bold break-words max-w-2xl">{{ $selectedWorkspace->name }}</h3>


                            </div>
                            <div class="flex items-center gap-3">
                                {{-- Workspace Options Dropdown --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" type="button"
                                        class="flex items-center justify-center w-10 h-10 transition-all duration-200 rounded-full hover:bg-gray-100 text-[#717C8F]">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 256 256"><path d="M144,128a16,16,0,1,1-16-16A16,16,0,0,1,144,128ZM128,72a16,16,0,1,0-16-16A16,16,0,0,0,128,72Zm0,112a16,16,0,1,0,16,16A16,16,0,0,0,128,184Z"></path></svg>
                                    </button>

                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 z-50 w-48 mt-2 origin-top-right bg-white border border-gray-100 shadow-xl rounded-2xl py-2 font-poppins" 
                                         style="display: none;">
                                        
                                        <button type="button" @click="open = false; showWorkspaceDetails({{ $selectedWorkspace->id }})" class="flex items-center w-full px-4 py-2.5 text-sm text-[#132C51] hover:bg-[#E8EEF9] transition-colors gap-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Details
                                        </button>

                                        <button type="button" id="view-members-btn-dropdown" @click="open = false; document.getElementById('view-members-btn').click()" class="flex items-center w-full px-4 py-2.5 text-sm text-[#132C51] hover:bg-[#E8EEF9] transition-colors gap-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            Members
                                        </button>

                                        @if($userRole === 'admin')
                                            <div class="h-px mx-4 my-2 bg-gray-50"></div>
                                            
                                            <button type="button" @click="open = false; document.getElementById('edit-workspace-modal').classList.remove('hidden')" class="flex items-center w-full px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition-colors gap-3">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit Workspace
                                            </button>

                                            <button type="button" @click="open = false; if(confirm('Are you sure you want to delete this workspace? This cannot be undone.')) { document.getElementById('delete-workspace-form').submit(); }" class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors gap-3">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m3 4v4m4-4v4m1-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Delete Workspace
                                            </button>

                                            <form id="delete-workspace-form" action="{{ route('workspace.destroy', $selectedWorkspace->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- Hidden trigger for existing members modal JS --}}
                                <button type="button" id="view-members-btn" class="hidden"></button>
                            </div>
                        </div>

                        {{-- Workspace Tasks Section --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-bold text-[#132C51]">Tasks</h4>
                                <button type="button" 
                                    @click="$dispatch('open-add-task-modal', { workspace_id: {{ $selectedWorkspace->id }} })"
                                    class="text-xs font-bold text-[#1C427A] hover:underline">
                                    + Add Task
                                </button>
                            </div>

                            <div class="grid gap-3">
                                @forelse($tasks as $task)
                                    <div class="flex items-center justify-between p-4 bg-white border border-gray-100 shadow-sm rounded-2xl hover:border-blue-200 transition-colors">
                                        <div class="flex items-center gap-4">
                                            <input type="checkbox" class="w-5 h-5 rounded-full task-checkbox accent-blue-500" data-id="{{ $task->id }}" {{ $task->completed ? 'checked' : '' }}>
                                            <div class="flex-1 min-w-0">
                                                <div data-task-title="{{ $task->id }}" class="flex flex-wrap items-center gap-2 text-sm font-semibold text-[#132C51] cursor-pointer hover:text-blue-600 transition-colors break-all {{ $task->completed ? 'line-through opacity-50' : '' }}">
                                                    {{ $task->title }}
                                                    <svg class="flex-shrink-0 w-4 h-4 {{ $task->priority === 'Urgent' ? 'text-red-500' : ($task->priority === 'High' ? 'text-yellow-500' : ($task->priority === 'Normal' ? 'text-blue-500' : 'text-green-500')) }}" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path>
                                                    </svg>
                                                    @foreach($task->workspaces as $ws)
                                                        <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-[9px] font-bold rounded-lg bg-[#E8EEF9] text-[#1C427A] ml-1.5 shadow-sm border border-[#1C427A]/10" title="{{ $ws->name }}">
                                                            {{ strtoupper(substr($ws->name, 0, 2)) }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mt-1 flex flex-col gap-0.5">
                                                    <div class="text-[11px] text-gray-500 font-medium flex items-center gap-1.5">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Due: {{ $task->due_date->format('M d, Y') }}
                                                    </div>
                                                    @if($task->start_time || $task->end_time)
                                                        <div class="text-[11px] text-gray-400 flex items-center gap-1.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            {{ $task->start_time ? $task->start_time->format('H:i') : '' }}{{ $task->start_time && $task->end_time ? ' - ' : '' }}{{ $task->end_time ? $task->end_time->format('H:i') : '' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="relative ml-2">
                                            <button class="text-gray-500 task-menu-btn hover:text-gray-700 font-bold text-xl" data-task="{{ $task->id }}">⋯</button>
                                            <div class="absolute right-0 z-50 hidden w-48 mt-1 shadow-xl rounded-xl bg-[#0C1F3B] task-menu" data-task="{{ $task->id }}">
                                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-white rounded-t-xl hover:bg-gray-600 edit-btn" data-task="{{ $task->id }}">Edit</button>
                                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-white hover:bg-gray-600 duplicate-btn" data-task="{{ $task->id }}">Duplicate</button>
                                                <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-red-500 rounded-b-xl hover:bg-gray-600 delete-btn" data-task="{{ $task->id }}">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-12 text-center bg-gray-50/50 rounded-3xl border-2 border-dashed border-gray-100">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="text-sm text-gray-400">No tasks in this workspace yet</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="flex flex-col items-center justify-center py-20">
                            <svg class="w-20 h-20 mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <h3 class="text-xl font-bold text-gray-400">Select or Create a Workspace</h3>
                            <p class="mt-2 text-sm text-gray-300">Choose a workspace from the left panel or create a new one.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Create Workspace Modal --}}
    <div id="create-workspace-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-6 shadow-xl rounded-xl w-[480px] bg-[#132C51] max-w-full my-8">
            <h3 class="mb-4 text-2xl font-semibold text-white">Create Workspace</h3>
            <form action="{{ route('workspace.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold text-gray-100">Workspace Name</label>
                    <input type="text" name="name" placeholder="e.g. Marketing Team"
                        class="w-full px-4 py-2.5 text-white bg-[#0C1F3B] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div class="mb-6">
                    <label class="block mb-1 text-sm font-semibold text-gray-100">Description (optional)</label>
                    <textarea name="description" placeholder="Brief description..."
                        class="w-full px-4 py-2.5 text-white bg-[#0C1F3B] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" rows="3"></textarea>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="submit" class="px-6 py-2 text-white bg-[#1C427A] rounded-3xl transition-transform duration-200 hover:scale-105 font-semibold">Create</button>
                    <button type="button" id="close-create-modal" class="px-6 py-2 text-white bg-gray-500 rounded-3xl transition-transform duration-200 hover:scale-95">Cancel</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    {{-- Edit Workspace Modal --}}
    <div id="edit-workspace-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-6 shadow-xl rounded-xl w-[480px] bg-[#132C51] max-w-full my-8">
            <h3 class="mb-4 text-2xl font-semibold text-white">Edit Workspace</h3>
            @if($selectedWorkspace)
            <form action="{{ route('workspace.update', $selectedWorkspace->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block mb-1 text-sm font-semibold text-gray-100">Workspace Name</label>
                    <input type="text" name="name" value="{{ $selectedWorkspace->name }}"
                        class="w-full px-4 py-2.5 text-white bg-[#0C1F3B] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div class="mb-6">
                    <label class="block mb-1 text-sm font-semibold text-gray-100">Description (optional)</label>
                    <textarea name="description"
                        class="w-full px-4 py-2.5 text-white bg-[#0C1F3B] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" rows="3">{{ $selectedWorkspace->description }}</textarea>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-3xl transition-transform duration-200 hover:scale-105 font-semibold">Save Changes</button>
                    <button type="button" onclick="document.getElementById('edit-workspace-modal').classList.add('hidden')" class="px-6 py-2 text-white bg-gray-500 rounded-3xl transition-transform duration-200 hover:scale-95">Cancel</button>
                </div>
            </form>
            @endif
            </div>
        </div>
    </div>

    {{-- Workspace Details Modal --}}
    <div id="workspace-details-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative shadow-2xl rounded-3xl w-[480px] bg-white overflow-hidden max-w-full my-8">
            <div class="p-6 bg-[#E8EEF9] flex justify-between items-center">
                <h3 class="text-xl font-bold text-[#132C51]">Workspace Details</h3>
                <button type="button" onclick="document.getElementById('workspace-details-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Workspace Name</label>
                    <p id="detail-name" class="text-lg font-bold text-[#132C51] mt-1 break-words"></p>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Description</label>
                    <p id="detail-description" class="text-sm text-gray-600 mt-1 whitespace-pre-line break-words"></p>
                </div>
                <div class="grid grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Owner</label>
                        <p id="detail-owner" class="text-sm font-semibold text-[#132C51] mt-1"></p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Created At</label>
                        <p id="detail-date" class="text-sm font-semibold text-[#132C51] mt-1"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Members</label>
                        <p id="detail-members" class="text-sm font-semibold text-[#132C51] mt-1"></p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Tasks</label>
                        <p id="detail-tasks" class="text-sm font-semibold text-[#132C51] mt-1"></p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-50 flex justify-center">
                <button type="button" onclick="document.getElementById('workspace-details-modal').classList.add('hidden')" class="px-8 py-2 text-white bg-[#0E213D] rounded-3xl font-semibold transition-all hover:scale-105">Close</button>
            </div>
            </div>
        </div>
    </div>

    {{-- Member List Modal --}}
    <div id="member-list-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative shadow-2xl rounded-3xl w-[700px] bg-white overflow-hidden max-w-full my-8">
            <div class="flex items-center justify-between p-6 bg-[#E8EEF9]">
                <div class="flex items-center gap-4">
                    <h3 class="text-2xl font-bold text-[#132C51]">Workspace Members</h3>
                    @if($userRole === 'admin')
                        <button type="button" id="invite-member-btn"
                            class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-white bg-[#0E213D] shadow-sm rounded-3xl transition-transform duration-200 hover:scale-105">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Invite
                        </button>
                    @endif
                </div>
                <button type="button" id="close-members-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 pb-0">
                <div class="relative mb-6">
                    <input type="text" id="member-search" placeholder="Search members by name or email..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            
            <div class="p-6 pt-0 max-h-[50vh] overflow-y-auto">
                <table class="w-full" id="members-table">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase">Member</th>
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase">Email</th>
                            <th class="pb-3 text-xs font-bold text-gray-400 uppercase">Role</th>
                            @if($userRole === 'admin')
                                <th class="pb-3 text-xs font-bold text-right text-gray-400 uppercase">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($members as $member)
                            <tr class="group" data-user-id="{{ $member->id }}">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        @if($member->profile_photo_path)
                                            <img src="{{ asset('storage/' . $member->profile_photo_path) }}" class="object-cover w-9 h-9 rounded-full" alt="">
                                        @else
                                            <div class="flex items-center justify-center w-9 h-9 rounded-full bg-[#E8EEF9] text-[#1C427A] font-bold text-sm">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-semibold text-[#132C51]">{{ $member->name }}</div>
                                            @if($selectedWorkspace->owner_id === $member->id)
                                                <span class="text-[10px] text-gray-400">Owner</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-sm text-gray-500">{{ $member->email }}</td>
                                <td class="py-4">
                                    @if($userRole === 'admin' && $member->id !== Auth::id())
                                        <select class="role-select px-3 py-1.5 text-xs font-semibold border rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all
                                            {{ $member->pivot->role === 'admin' ? 'bg-yellow-50 border-yellow-200 text-yellow-700' : 'bg-blue-50 border-blue-200 text-blue-700' }}"
                                            data-workspace-id="{{ $selectedWorkspace->id }}"
                                            data-user-id="{{ $member->id }}">
                                            <option value="admin" {{ $member->pivot->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="member" {{ $member->pivot->role === 'member' ? 'selected' : '' }}>Member</option>
                                        </select>
                                    @else
                                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full
                                            {{ $member->pivot->role === 'admin' ? 'bg-yellow-50 text-yellow-700' : 'bg-blue-50 text-blue-700' }}">
                                            {{ ucfirst($member->pivot->role) }}
                                        </span>
                                    @endif
                                </td>
                                @if($userRole === 'admin')
                                    <td class="py-4 text-right">
                                        @if($member->id !== Auth::id())
                                            <button class="remove-member-btn px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-red-100"
                                                data-workspace-id="{{ $selectedWorkspace->id }}"
                                                data-user-id="{{ $member->id }}"
                                                data-user-name="{{ $member->name }}">
                                                Remove
                                            </button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    {{-- Invite Member Modal --}}
    <div id="invite-member-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-6 shadow-xl rounded-xl w-[480px] bg-[#132C51] max-w-full my-8">
            <h3 class="mb-2 text-2xl font-semibold text-white">Invite Member</h3>
            <p class="mb-4 text-sm text-gray-400">Send an invitation email to add a new member to this workspace.</p>
            <div class="mb-4">
                <label class="block mb-1 text-sm font-semibold text-gray-100">Email Address</label>
                <input type="email" id="invite-email" placeholder="colleague@example.com"
                    class="w-full px-4 py-2.5 text-white bg-[#0C1F3B] border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>
            <div id="invite-feedback" class="hidden px-4 py-2 mb-4 text-sm rounded-lg"></div>
            <div class="flex justify-center gap-4">
                <button type="button" id="send-invite-btn" class="px-6 py-2 text-white bg-[#1C427A] rounded-3xl transition-transform duration-200 hover:scale-105 font-semibold">
                    <span id="invite-btn-text">Send Invitation</span>
                    <span id="invite-btn-loading" class="hidden">Sending...</span>
                </button>
                <button type="button" id="close-invite-modal" class="px-6 py-2 text-white bg-gray-500 rounded-3xl transition-transform duration-200 hover:scale-95">Cancel</button>
            </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // Flash message auto-hide
        ['flash-success', 'flash-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) setTimeout(() => { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 4000);
        });

        // Create Workspace Modal
        const createModal = document.getElementById('create-workspace-modal');
        const createBtn = document.getElementById('create-workspace-btn');
        const closeCreateBtn = document.getElementById('close-create-modal');
        if (createBtn && createModal) {
            createBtn.addEventListener('click', () => createModal.classList.remove('hidden'));
            closeCreateBtn?.addEventListener('click', () => createModal.classList.add('hidden'));
            createModal.addEventListener('click', e => { if (e.target === createModal) createModal.classList.add('hidden'); });
        }

        // Member List Modal
        const membersModal = document.getElementById('member-list-modal');
        const viewMembersBtn = document.getElementById('view-members-btn');
        const closeMembersBtn = document.getElementById('close-members-modal');
        const memberSearch = document.getElementById('member-search');
        
        if (viewMembersBtn && membersModal) {
            viewMembersBtn.addEventListener('click', () => membersModal.classList.remove('hidden'));
            closeMembersBtn?.addEventListener('click', () => membersModal.classList.add('hidden'));
            membersModal.addEventListener('click', e => { if (e.target === membersModal) membersModal.classList.add('hidden'); });
            
            // Real-time search
            memberSearch?.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#members-table tbody tr');
                rows.forEach(row => {
                    const name = row.querySelector('.text-sm.font-semibold').textContent.toLowerCase();
                    const email = row.querySelector('.text-sm.text-gray-500').textContent.toLowerCase();
                    row.style.display = (name.includes(term) || email.includes(term)) ? '' : 'none';
                });
            });
        }

        // Invite Member Modal
        const inviteModal = document.getElementById('invite-member-modal');
        const inviteBtn = document.getElementById('invite-member-btn');
        const closeInviteBtn = document.getElementById('close-invite-modal');
        const sendInviteBtn = document.getElementById('send-invite-btn');
        if (inviteBtn && inviteModal) {
            inviteBtn.addEventListener('click', () => {
                document.getElementById('invite-email').value = '';
                document.getElementById('invite-feedback').classList.add('hidden');
                inviteModal.classList.remove('hidden');
            });
            closeInviteBtn?.addEventListener('click', () => inviteModal.classList.add('hidden'));
            inviteModal.addEventListener('click', e => { if (e.target === inviteModal) inviteModal.classList.add('hidden'); });
        }

        // Send Invitation
        if (sendInviteBtn) {
            sendInviteBtn.addEventListener('click', async () => {
                const email = document.getElementById('invite-email').value.trim();
                const feedback = document.getElementById('invite-feedback');
                const btnText = document.getElementById('invite-btn-text');
                const btnLoading = document.getElementById('invite-btn-loading');

                if (!email) { showFeedback(feedback, 'Please enter an email address.', 'error'); return; }

                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');
                sendInviteBtn.disabled = true;

                const workspaceId = {{ $selectedWorkspace ? $selectedWorkspace->id : 'null' }};
                try {
                    const res = await fetch(`/workspace/${workspaceId}/invite`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ email })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        showFeedback(feedback, data.success || 'Invitation sent!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showFeedback(feedback, data.error || 'Failed to send invitation.', 'error');
                    }
                } catch (err) {
                    showFeedback(feedback, 'Network error. Please try again.', 'error');
                } finally {
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                    sendInviteBtn.disabled = false;
                }
            });
        }

        // Role Change
        document.querySelectorAll('.role-select').forEach(select => {
            select.addEventListener('change', async function() {
                const workspaceId = this.dataset.workspaceId;
                const userId = this.dataset.userId;
                const newRole = this.value;

                if (!confirm(`Change this member's role to "${newRole}"?`)) {
                    this.value = this.value === 'admin' ? 'member' : 'admin';
                    return;
                }

                try {
                    const res = await fetch(`/workspace/${workspaceId}/member/${userId}/role`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ role: newRole })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        // Update styling
                        if (newRole === 'admin') {
                            this.className = this.className.replace(/bg-blue-50 border-blue-200 text-blue-700/g, 'bg-yellow-50 border-yellow-200 text-yellow-700');
                        } else {
                            this.className = this.className.replace(/bg-yellow-50 border-yellow-200 text-yellow-700/g, 'bg-blue-50 border-blue-200 text-blue-700');
                        }
                    } else {
                        alert(data.error || 'Failed to update role.');
                        this.value = this.value === 'admin' ? 'member' : 'admin';
                    }
                } catch (err) {
                    alert('Network error.');
                    this.value = this.value === 'admin' ? 'member' : 'admin';
                }
            });
        });

        // Remove Member
        document.querySelectorAll('.remove-member-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const workspaceId = this.dataset.workspaceId;
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;

                if (!confirm(`Remove "${userName}" from this workspace? They will lose all access.`)) return;

                try {
                    const res = await fetch(`/workspace/${workspaceId}/member/${userId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok) {
                        const row = this.closest('tr');
                        row.style.transition = 'opacity 0.3s, transform 0.3s';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        alert(data.error || 'Failed to remove member.');
                    }
                } catch (err) {
                    alert('Network error.');
                }
            });
        });

        function showFeedback(el, msg, type) {
            el.textContent = msg;
            el.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            if (type === 'success') { el.classList.add('bg-green-100', 'text-green-800'); }
            else { el.classList.add('bg-red-100', 'text-red-800'); }
        }

        window.showWorkspaceDetails = async function(id) {
            const modal = document.getElementById('workspace-details-modal');
            modal.classList.remove('hidden');
            
            // Show loading state or clear previous
            ['name', 'description', 'owner', 'date', 'members', 'tasks'].forEach(field => {
                document.getElementById(`detail-${field}`).textContent = '...';
            });

            try {
                const res = await fetch(`/workspace/${id}`);
                const data = await res.json();
                if (res.ok) {
                    document.getElementById('detail-name').textContent = data.name;
                    document.getElementById('detail-description').textContent = data.description || 'No description provided.';
                    document.getElementById('detail-owner').textContent = data.owner;
                    document.getElementById('detail-date').textContent = data.created_at;
                    document.getElementById('detail-members').textContent = data.members_count;
                    document.getElementById('detail-tasks').textContent = data.tasks_count;
                }
            } catch (err) {
                console.error(err);
                modal.classList.add('hidden');
            }
        };

        // --- Task Management JS ---
        function formatDateTime(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }

        const hideMenus = () => {
            document.querySelectorAll('.task-menu').forEach(m => m.classList.add('hidden'));
        };

        // Menu Toggle
        document.addEventListener('click', function(e) {
            const taskMenuBtn = e.target.closest('.task-menu-btn');
            const taskMenu = e.target.closest('.task-menu');

            document.querySelectorAll('.task-menu').forEach(m => {
                if (m !== taskMenu) m.classList.add('hidden');
            });

            if (taskMenuBtn) {
                const taskId = taskMenuBtn.dataset.task;
                const menu = document.querySelector(`.task-menu[data-task="${taskId}"]`);
                if (menu) menu.classList.toggle('hidden');
            } else if (!taskMenu) {
                hideMenus();
            }
        });

        // Task Details
        document.addEventListener('click', function(e) {
            const detailsBtn = e.target.closest('.details-btn') || e.target.closest('[data-task-title]');
            if (detailsBtn) {
                const taskId = detailsBtn.dataset.task || detailsBtn.dataset.taskTitle;
                if (!taskId) return;
                hideMenus();
                
                fetch(`/tasks/${taskId}`)
                    .then(res => res.json())
                    .then(task => {
                        document.getElementById('details-title').textContent = task.title ?? '';
                        document.getElementById('details-description').textContent = task.description ?? 'No description provided';
                        document.getElementById('details-due-date').textContent = task.due_date ?? 'N/A';
                        document.getElementById('details-created-date').textContent = formatDateTime(task.created_at);
                        document.getElementById('details-start-time').textContent = task.start_time ? task.start_time.substring(0, 5) : '-';
                        document.getElementById('details-end-time').textContent = task.end_time ? task.end_time.substring(0, 5) : '-';
                        
                        let completedDate = task.completed_at || task.complated_at || '';
                        document.getElementById('details-completed-at').textContent = completedDate ? formatDateTime(completedDate) : '-';

                        const detailsWorkspaces = document.getElementById('details-workspaces');
                        if (task.workspaces && task.workspaces.length > 0) {
                            detailsWorkspaces.innerHTML = task.workspaces.map(ws => `<span class="px-2 py-0.5 bg-teal-900/50 text-teal-300 border border-teal-500/30 rounded-full text-xs break-all">${ws.name}</span>`).join('');
                        } else {
                            detailsWorkspaces.innerHTML = '<p class="text-gray-400">No workspaces assigned</p>';
                        }

                        const detailsPriority = document.getElementById('details-priority');
                        const pr = task.priority ?? '';
                        let textClass = 'text-gray-400';
                        if (pr === 'Urgent') textClass = 'text-red-500';
                        else if (pr === 'High') textClass = 'text-yellow-500';
                        else if (pr === 'Normal') textClass = 'text-blue-500';
                        else if (pr === 'Low') textClass = 'text-green-500';

                        if (pr) {
                            detailsPriority.innerHTML = `<div class="flex items-center gap-1.5"><svg class="w-5 h-5 ${textClass}" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg><span class="${textClass} font-semibold">${pr}</span></div>`;
                        } else {
                            detailsPriority.textContent = '-';
                        }
                        
                        document.getElementById('details-completed').innerHTML = task.completed ? '<span class="text-green-500 font-bold">Completed</span>' : '<span class="text-red-500 font-bold">Not Completed</span>';

                        const detailsAttachments = document.getElementById('details-attachments');
                        const attachments = Array.isArray(task.attachments) ? task.attachments : [];
                        if (!attachments.length) {
                            detailsAttachments.innerHTML = '<p class="col-span-2 text-gray-400">No attachments</p>';
                        } else {
                            detailsAttachments.innerHTML = attachments.map(att => {
                                const originalName = att.original_name || att.filename || 'Attachment';
                                const path = att.storage_path ? `/storage/${att.storage_path}` : '#';
                                const isImage = att.mime_type && att.mime_type.startsWith('image/') && att.storage_path;
                                const imgHtml = isImage ? `<img src="${path}" class="w-12 h-12 object-cover rounded-md flex-shrink-0">` : '';
                                return `<a href="${path}" target="_blank" class="flex items-center gap-4 p-3 bg-[#1A365D] rounded-xl border border-gray-600 hover:bg-[#254A7A] transition-colors group">${imgHtml}<div class="flex-1 min-w-0"><div class="font-medium text-gray-200 truncate group-hover:text-blue-400" title="${originalName}">${originalName}</div><div class="text-gray-400 text-xs mt-1">${att.mime_type || ''}</div></div></a>`;
                            }).join('');
                        }

                        document.getElementById('task-details-modal').classList.remove('hidden');
                        const editDetailsBtn = document.getElementById('edit-details-btn');
                        if (editDetailsBtn) {
                            editDetailsBtn.dataset.task = String(taskId);
                            task.completed ? editDetailsBtn.classList.add('hidden') : editDetailsBtn.classList.remove('hidden');
                        }
                    });
            }
        });

        document.getElementById('close-details-modal')?.addEventListener('click', () => document.getElementById('task-details-modal').classList.add('hidden'));

        // Edit Task
        document.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-btn') || (e.target.id === 'edit-details-btn' ? e.target : null);
            if (editBtn) {
                const taskId = editBtn.dataset.task;
                if (!taskId) return;
                document.getElementById('task-details-modal').classList.add('hidden');
                hideMenus();

                fetch(`/tasks/${taskId}`)
                    .then(res => res.json())
                    .then(data => {
                        const form = document.getElementById('edit-task-form');
                        form.action = `/tasks/${data.id}`;
                        document.getElementById('edit-task-id').value = data.id;
                        document.getElementById('edit-title').value = data.title || '';
                        document.getElementById('edit-description').value = data.description || '';
                        document.getElementById('edit-due-date').value = data.due_date || '';
                        document.getElementById('edit-start-time').value = data.start_time ? data.start_time.substring(0, 5) : '';
                        document.getElementById('edit-end-time').value = data.end_time ? data.end_time.substring(0, 5) : '';
                        
                        const pr = data.priority || '';
                        document.getElementById('edit-priority').value = pr;
                        document.getElementById('edit-priority-label').textContent = pr || 'Priority';
                        window.dispatchEvent(new CustomEvent('set-edit-priority', { detail: { priority: pr } }));

                        const workspaceCheckboxes = document.querySelectorAll('.edit-workspace-checkbox');
                        workspaceCheckboxes.forEach(cb => cb.checked = false);
                        if (data.workspaces) {
                            data.workspaces.forEach(ws => {
                                const cb = document.querySelector(`.edit-workspace-checkbox[value="${ws.id}"]`);
                                if (cb) cb.checked = true;
                            });
                        }

                        const existingWrap = document.getElementById('edit-existing-attachments-wrap');
                        const existingContainer = document.getElementById('edit-existing-attachments');
                        if (data.attachments && data.attachments.length) {
                            existingWrap.classList.remove('hidden');
                            existingContainer.innerHTML = data.attachments.map(att => {
                                const name = att.original_name || att.filename || 'Attachment';
                                const path = `/storage/${att.storage_path}`;
                                const img = att.mime_type?.startsWith('image/') ? `<img src="${path}" class="w-12 h-12 object-cover rounded-md flex-shrink-0">` : '';
                                return `<div class="flex items-center gap-4 p-3 bg-[#1A365D] border border-gray-600 rounded-xl"><a href="${path}" target="_blank" class="flex items-center flex-1 min-w-0 gap-4 hover:opacity-80">${img}<div class="flex-1 min-w-0"><div class="font-medium text-gray-200 truncate">${name}</div></div></a><button type="button" class="text-red-400 hover:text-red-600 text-xl font-bold" onclick="this.closest('div').style.display='none'; const inp=document.createElement('input'); inp.type='hidden'; inp.name='remove_attachments[]'; inp.value='${att.id}'; this.parentNode.appendChild(inp);">&times;</button></div>`;
                            }).join('');
                        } else {
                            existingWrap.classList.add('hidden');
                        }

                        document.getElementById('edit-task-modal').classList.remove('hidden');
                    });
            }
        });

        document.getElementById('close-edit-modal')?.addEventListener('click', () => document.getElementById('edit-task-modal').classList.add('hidden'));

        // Duplicate
        document.addEventListener('click', function(e) {
            const dupBtn = e.target.closest('.duplicate-btn');
            if (dupBtn) {
                hideMenus();
                fetch(`/tasks/${dupBtn.dataset.task}/duplicate`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
                    .then(() => location.reload());
            }
        });

        // Delete
        document.addEventListener('click', function(e) {
            const delBtn = e.target.closest('.delete-btn');
            if (delBtn) {
                hideMenus();
                const modal = document.getElementById('delete-task-confirm-modal');
                modal.dataset.taskId = delBtn.dataset.task;
                modal.classList.remove('hidden');
            }
        });

        document.getElementById('cancel-delete-task-btn')?.addEventListener('click', () => document.getElementById('delete-task-confirm-modal').classList.add('hidden'));

        document.getElementById('confirm-delete-task-btn')?.addEventListener('click', function() {
            const taskId = this.closest('#delete-task-confirm-modal').dataset.taskId;
            fetch(`/tasks/${taskId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
                .then(() => location.reload());
        });

        // Global listener for search details
        window.addEventListener('open-task-details', (e) => {
            if (typeof window.showTaskDetails === 'function') {
                window.showTaskDetails(e.detail.taskId);
            } else {
                // Fallback to local click logic if function not global
                const detailsBtn = document.createElement('button');
                detailsBtn.dataset.task = e.detail.taskId;
                detailsBtn.className = 'details-btn hidden';
                document.body.appendChild(detailsBtn);
                detailsBtn.click();
                detailsBtn.remove();
            }
        });

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
                        'X-CSRF-TOKEN': csrfToken
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
                .catch(err => {
                    console.error(err);
                    checkbox.checked = !isCompleted;
                });
            }
        });

    });
    </script>
    <!-- Task Details Modal (view only) -->
    <div id="task-details-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-5 bg-[#132C51] shadow-xl rounded-xl w-[850px] max-w-full my-8">
            <div>
                <h3 class="mb-3 text-2xl font-semibold text-white">Task Details</h3>

                <div class="grid grid-cols-12 gap-y-2 gap-x-6">
                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <label class="font-semibold text-gray-100">Title</label>
                        </div>
                        <p id="details-title" class="text-gray-200 break-words"></p>
                    </div>

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

                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4"></path></svg>
                            <label class="font-semibold text-gray-100">Workspaces</label>
                        </div>
                        <div id="details-workspaces" class="flex flex-wrap gap-2 text-sm text-gray-200">
                            <p class="text-gray-400">No workspaces assigned</p>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <label class="font-semibold text-gray-100">Attachments</label>
                        </div>
                        <div id="details-attachments" class="grid grid-cols-2 gap-4 text-sm text-gray-200">
                            <p class="col-span-2 text-gray-400">No attachments</p>
                        </div>
                    </div>

                    <div class="flex justify-center col-span-12 gap-4 mt-6 font-medium">
                        <button type="button" id="edit-details-btn" class="px-5 py-1 text-white transition-transform duration-200 bg-[#1C427A] hover:scale-110 rounded-3xl">Edit</button>
                        <button type="button" id="close-details-modal" class="px-5 py-1 text-white transition-transform duration-200 bg-gray-500 hover:scale-110 rounded-3xl">Close</button>
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

                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <label class="font-semibold text-gray-100">Title</label>
                        </div>
                        <input id="edit-title" placeholder="Title Name" type="text" name="title" class="w-full px-3 py-2 border text-white border-gray-600 bg-[#0C1F3B] rounded-lg" required>
                    </div>

                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            <label class="font-semibold text-gray-100">Description</label>
                        </div>
                        <textarea id="edit-description" placeholder="Add Description" name="description" class="w-full bg-[#0C1F3B] px-3 text-white py-2 border-gray-600 border rounded-lg"></textarea>
                    </div>

                    <div class="grid grid-cols-1 col-span-12 gap-6 md:grid-cols-4">
                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <label class="font-semibold text-gray-100">Due Date</label>
                            </div>
                            <input id="edit-due-date" type="date" name="due_date" class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]" required>
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <label class="font-semibold text-gray-100">Start Time</label>
                            </div>
                            <input id="edit-start-time" type="time" name="start_time" class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
                        </div>

                        <div class="flex flex-col justify-end">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <label class="font-semibold text-gray-100">End Time</label>
                            </div>
                            <input id="edit-end-time" type="time" name="end_time" class="w-full text-white bg-[#0C1F3B] border-gray-600 px-3 py-2 border rounded-lg [color-scheme:dark]">
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
                                        'text-white border-gray-600': selected === null
                                    }">
                                    <svg class="w-5 h-5" :class="{ 'text-red-500': selected === 'Urgent', 'text-yellow-500': selected === 'High', 'text-blue-500': selected === 'Normal', 'text-green-500': selected === 'Low', 'text-gray-400': selected === null }" fill="currentColor" viewBox="0 0 24 24"><path d="M19.42 4.44994C19.3203 4.38116 19.2053 4.3379 19.085 4.32395C18.9647 4.31 18.8428 4.32579 18.73 4.36994C17.5425 4.8846 16.2857 5.22155 15 5.36994C14.1879 5.15273 13.4127 4.81569 12.7 4.36994C11.7802 3.80143 10.763 3.40813 9.7 3.20994C8.41 3.08994 5.34 4.09994 4.7 4.30994C4.55144 4.36012 4.42234 4.4556 4.33086 4.58295C4.23938 4.71031 4.19012 4.86314 4.19 5.01994V19.9999C4.19 20.1989 4.26902 20.3896 4.40967 20.5303C4.55032 20.6709 4.74109 20.7499 4.94 20.7499C5.13891 20.7499 5.32968 20.6709 5.47033 20.5303C5.61098 20.3896 5.69 20.1989 5.69 19.9999V14.1399C6.93659 13.6982 8.23315 13.4127 9.55 13.2899C10.3967 13.4978 11.2062 13.8351 11.95 14.2899C12.8201 14.8218 13.7734 15.2038 14.77 15.4199H15C16.4474 15.2326 17.8633 14.8526 19.21 14.2899C19.3506 14.2342 19.4713 14.1379 19.5568 14.0132C19.6423 13.8885 19.6887 13.7411 19.69 13.5899V5.06994C19.6975 4.95258 19.6769 4.83512 19.63 4.7273C19.583 4.61947 19.511 4.5244 19.42 4.44994Z" fill="currentColor"></path></svg>
                                    <span id="edit-priority-label" x-text="selected || 'Priority'" class="flex-1"></span>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition class="absolute z-10 w-full p-1 mt-1 bg-[#EAF0FA] rounded-xl shadow-xl">
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
                        </div>
                    </div>

                    <div class="col-span-12">
                        <div class="flex items-center gap-2 mt-2 mb-1">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <label class="font-semibold text-gray-100">Attachments</label>
                        </div>
                        <div id="edit-existing-attachments-wrap" class="hidden mb-4">
                            <div id="edit-existing-attachments" class="grid grid-cols-2 gap-4"></div>
                        </div>
                        <label class="flex items-center justify-center w-full gap-2 px-3 py-1 text-white border border-gray-600 rounded-lg cursor-pointer bg-[#0C1F3B] hover:bg-[#1A365D]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="edit-file-label">Add New File</span>
                            <input type="file" name="attachments[]" multiple class="hidden" id="edit-task-file-input">
                        </label>
                        <div id="edit-file-list" class="grid grid-cols-2 gap-4 mt-4 text-sm text-gray-300"></div>
                    </div>

                    <div class="flex justify-center col-span-12 gap-6 mt-4 font-medium">
                        <button type="submit" class="px-5 py-1 text-white bg-[#1C427A] rounded-3xl hover:scale-110">Save</button>
                        <button type="button" id="close-edit-modal" class="px-5 py-1 text-white bg-gray-500 rounded-3xl hover:scale-95">Cancel</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-task-confirm-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative p-8 bg-[#132C51] shadow-xl rounded-2xl w-[450px] max-w-full">
                <div class="text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 bg-red-100 rounded-full">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="mb-2 text-2xl font-bold text-white">Delete Task?</h3>
                    <p class="mb-8 text-gray-300">This action cannot be undone. Are you sure you want to delete this task?</p>
                    <div class="flex justify-center gap-4">
                        <button id="confirm-delete-task-btn" class="px-8 py-2 text-white bg-red-600 rounded-3xl hover:bg-red-700 transition-colors font-semibold">Delete</button>
                        <button id="cancel-delete-task-btn" class="px-8 py-2 text-white bg-gray-500 rounded-3xl hover:bg-gray-600 transition-colors font-semibold">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
