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
                                    <div class="flex items-center justify-center min-w-[22px] h-[22px] px-1.5 text-[11px] font-bold text-white bg-red-500 rounded-full shadow-sm animate-pulse">
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
                                            <div class="w-2 h-2 rounded-full 
                                                {{ $task->priority === 'Urgent' ? 'bg-red-500' : ($task->priority === 'High' ? 'bg-orange-500' : ($task->priority === 'Normal' ? 'bg-blue-500' : 'bg-gray-400')) }}">
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-[#132C51] {{ $task->completed ? 'line-through opacity-50' : '' }}">
                                                    {{ $task->title }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    Due: {{ $task->due_date->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full 
                                                {{ $task->priority === 'Urgent' ? 'bg-red-50 text-red-600' : ($task->priority === 'High' ? 'bg-orange-50 text-orange-600' : ($task->priority === 'Normal' ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-600')) }}">
                                                {{ $task->priority }}
                                            </span>
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
    <div id="create-workspace-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-6 shadow-xl rounded-xl w-[480px] bg-[#132C51]">
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

    {{-- Edit Workspace Modal --}}
    <div id="edit-workspace-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-6 shadow-xl rounded-xl w-[480px] bg-[#132C51]">
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

    {{-- Workspace Details Modal --}}
    <div id="workspace-details-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-0 shadow-2xl rounded-3xl w-[480px] bg-white overflow-hidden">
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

    {{-- Member List Modal --}}
    <div id="member-list-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-0 shadow-2xl rounded-3xl w-[700px] bg-white overflow-hidden">
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

    {{-- Invite Member Modal --}}
    <div id="invite-member-modal" class="fixed inset-0 z-50 hidden bg-gray-600 bg-opacity-80 font-poppins">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-6 shadow-xl rounded-xl w-[480px] bg-[#132C51]">
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
    });
    </script>
</x-app-layout>
