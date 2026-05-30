{{-- Onboarding Tour Component --}}
{{-- Usage: <x-onboarding-tour :page="'home'" /> --}}
@props(['page' => ''])

@if($page && Auth::check())
<div x-data="onboardingTour('{{ $page }}')" x-show="active" x-cloak
     class="font-poppins" style="display: none;"
     @keydown.escape.window="skipTour()">

    {{-- Dark overlay with spotlight cutout --}}
    <div class="fixed inset-0 z-[9998] transition-opacity duration-300"
         :class="active ? 'opacity-100' : 'opacity-0 pointer-events-none'"
         @click="nextStep()">
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <mask id="tour-spotlight-mask">
                    <rect width="100%" height="100%" fill="white"/>
                    <rect :x="spotlight.x" :y="spotlight.y" :width="spotlight.w" :height="spotlight.h"
                          rx="16" ry="16" fill="black"
                          class="transition-all duration-500 ease-out"/>
                </mask>
            </defs>
            <rect width="100%" height="100%" fill="rgba(12, 31, 59, 0.75)" mask="url(#tour-spotlight-mask)"/>
        </svg>
    </div>

    {{-- Spotlight border glow --}}
    <div class="fixed z-[9999] pointer-events-none transition-all duration-500 ease-out rounded-2xl"
         :style="`top: ${spotlight.y - 3}px; left: ${spotlight.x - 3}px; width: ${spotlight.w + 6}px; height: ${spotlight.h + 6}px;`"
         x-show="!isCenter">
        <div class="w-full h-full rounded-2xl border-2 border-blue-400/60 shadow-[0_0_30px_rgba(44,130,201,0.3)]"></div>
    </div>

    {{-- Tooltip card --}}
    <div class="fixed z-[10000] transition-all duration-500 ease-out"
         :style="tooltipStyle"
         @click.stop>

        {{-- Tooltip arrow --}}
        <div class="absolute w-4 h-4 rotate-45 bg-white/95 backdrop-blur-md"
             :class="{
                 'bottom-[-8px] left-8': tooltipPosition === 'top',
                 'top-[-8px] left-8': tooltipPosition === 'bottom',
                 'right-[-8px] top-6': tooltipPosition === 'left',
                 'left-[-8px] top-6': tooltipPosition === 'right',
                 'hidden': isCenter
             }"
             x-show="!isCenter">
        </div>

        <div class="relative w-[450px] max-w-[90vw] bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/50 overflow-hidden">

            {{-- Progress bar --}}
            <div class="h-1 bg-gray-100">
                <div class="h-full transition-all duration-500 ease-out rounded-r-full"
                     style="background: linear-gradient(90deg, #1C427A, #2F6ECB);"
                     :style="`width: ${((currentStep + 1) / steps.length) * 100}%`">
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6">
                {{-- Icon + Step indicator --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl"
                             :class="currentStepData?.iconBg || 'bg-blue-50'"
                             x-html="currentStepData?.icon || ''">
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold tracking-wider uppercase"
                                  style="color: #1C427A;"
                                  x-text="`Step ${currentStep + 1} of ${steps.length}`"></span>
                        </div>
                    </div>
                    <button @click="skipTour()"
                            class="text-[11px] font-medium text-gray-400 hover:text-red-500 transition-colors px-2 py-1 rounded-lg hover:bg-red-50">
                        Skip Tour
                    </button>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-[#0E213D] mb-2 leading-tight"
                    x-text="currentStepData?.title || ''"></h3>

                {{-- Description --}}
                <p class="text-sm leading-relaxed text-gray-500 mb-5"
                   x-text="currentStepData?.description || ''"></p>

                {{-- Navigation --}}
                <div class="flex items-center justify-between">
                    {{-- Dots --}}
                    <div class="flex items-center gap-1.5">
                        <template x-for="(step, idx) in steps" :key="idx">
                            <button @click="goToStep(idx)"
                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                    :class="idx === currentStep
                                        ? 'bg-[#1C427A] w-6'
                                        : idx < currentStep ? 'bg-[#2F6ECB]/50' : 'bg-gray-200'">
                            </button>
                        </template>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-2">
                        <button x-show="currentStep > 0"
                                @click="prevStep()"
                                class="px-4 py-2 text-sm font-medium text-[#1C427A] rounded-xl hover:bg-[#E8EEF9] transition-colors">
                            Back
                        </button>
                        <button @click="nextStep()"
                                class="px-5 py-2 text-sm font-bold text-white rounded-xl transition-all duration-200 hover:scale-105 shadow-lg"
                                style="background: linear-gradient(135deg, #1C427A, #2F6ECB);">
                            <span x-text="isLastStepOnPage ? 'Finish' : 'Next'"></span>
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    @keyframes tour-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(44, 130, 201, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(44, 130, 201, 0); }
    }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('onboardingTour', (initialPage) => ({
        active: false,
        currentPage: initialPage,
        currentStep: 0,
        spotlight: { x: 0, y: 0, w: 0, h: 0 },
        tooltipPosition: 'bottom',
        tooltipStyle: '',
        isCenter: false,

        pages: ['home', 'schedule', 'workspace', 'workspace_selected', 'profile', 'help', 'privacy'],

        tourConfig: {
            home: [
                {
                    target: null,
                    title: 'Welcome to TuList! 👋',
                    description: "Let's take a quick tour to help you get started with managing tasks and collaborating with your team.",
                    icon: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    iconBg: 'bg-blue-50',
                    position: 'center'
                },
                {
                    target: '#tour-task-list',
                    title: 'All My Tasks 📋',
                    description: 'This is a list of all your tasks. Tasks are grouped into categories: Today, Tomorrow, Upcoming, and Overdue for easy tracking.',
                    icon: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>',
                    iconBg: 'bg-blue-50',
                    position: 'top'
                },
                {
                    target: '#add-task-btn',
                    title: 'Create New Task ✨',
                    description: 'Click this "New Task" button to create a new task. A form will appear to fill in the task details.',
                    icon: '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                    iconBg: 'bg-emerald-50',
                    position: 'bottom'
                },
                {
                    target: '#tour-add-task-modal',
                    title: 'New Task Form 📝',
                    description: 'In this form, you can fill in: Title, Description, Due Date, Start & End Time, Priority (Urgent, High, Normal, Low), associated Workspaces, Attachments, and Initial Comments.',
                    icon: '<svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                    iconBg: 'bg-violet-50',
                    position: 'left',
                    onAction: 'openAddTaskModal'
                },
                {
                    target: '#tour-save-task',
                    title: 'Save Task 💾',
                    description: 'After filling in all the details, click the "Save" button to save your new task.',
                    icon: '<svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                    iconBg: 'bg-cyan-50',
                    position: 'top',
                    onAction: 'openAddTaskModal'
                },
                {
                    target: '#tour-history',
                    title: 'Task History 📚',
                    description: 'The History section displays all completed tasks. You can review finished tasks and undo their completion status if needed.',
                    icon: '<svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    iconBg: 'bg-slate-50',
                    position: 'top',
                    onAction: 'closeAddTaskModal'
                },
                {
                    target: '#tour-generate-report',
                    title: 'Generate Report 📊',
                    description: 'Click the "Generate Report" button to create a PDF report of all your completed tasks. This is useful for documentation and reporting.',
                    icon: '<svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                    iconBg: 'bg-rose-50',
                    position: 'bottom'
                },
                {
                    target: '#tour-header-search',
                    title: 'Search Tasks 🔍',
                    description: 'Use this search bar to quickly find tasks by their title.',
                    icon: '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>',
                    iconBg: 'bg-indigo-50',
                    position: 'bottom'
                },
                {
                    target: '#tour-header-profile',
                    title: 'Your Profile',
                    description: 'Click here to view and edit your profile settings.',
                    icon: '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
                    iconBg: 'bg-emerald-50',
                    position: 'bottom'
                },
                {
                    target: '#tour-sidebar-schedule',
                    title: 'Schedule',
                    description: 'View your tasks in a calendar layout to plan ahead.',
                    icon: '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    iconBg: 'bg-purple-50',
                    position: 'right'
                },
                {
                    target: '#tour-sidebar-workspace',
                    title: 'Workspace',
                    description: 'Collaborate with your team by creating and joining workspaces.',
                    icon: '<svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
                    iconBg: 'bg-teal-50',
                    position: 'right'
                },
                {
                    target: '#notifications-btn',
                    title: 'Notifications',
                    description: 'Check your reminders and workspace invitations here.',
                    icon: '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>',
                    iconBg: 'bg-amber-50',
                    position: 'right'
                },
                {
                    target: '#tour-sidebar-privacy',
                    title: 'Privacy Policy',
                    description: 'Read about how we handle and protect your personal data.',
                    icon: '<svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                    iconBg: 'bg-orange-50',
                    position: 'right'
                },
                {
                    target: '#tour-sidebar-help',
                    title: 'Help Center',
                    description: 'Get support and find answers to your questions.',
                    icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    iconBg: 'bg-green-50',
                    position: 'right'
                },
                {
                    target: '#tour-sidebar-logout',
                    title: 'Logout',
                    description: 'Click here when you are ready to log out safely.',
                    icon: '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>',
                    iconBg: 'bg-red-50',
                    position: 'right'
                }
            ],
            schedule: [
                {
                    target: null,
                    title: 'Schedule Feature 📅',
                    description: 'This page allows you to view and manage all your tasks in a calendar layout. You can easily see what tasks are coming up on any given day.',
                    icon: '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    iconBg: 'bg-purple-50',
                    position: 'center'
                },
                {
                    target: '#tour-schedule-header',
                    title: 'Month Navigation ⏪⏩',
                    description: 'Use these arrow buttons to navigate to the previous or next month. You can preview your scheduled tasks ahead of time.',
                    icon: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    iconBg: 'bg-blue-50',
                    position: 'bottom'
                },
                {
                    target: '#add-task-btn',
                    title: 'Quick Create Task ⚡',
                    description: 'Just like on the home page, you can press this button to quickly create a new task directly from the schedule page.',
                    icon: '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>',
                    iconBg: 'bg-emerald-50',
                    position: 'bottom'
                },
                {
                    target: '#calendar-view',
                    title: 'Task Calendar 🗓️',
                    description: 'Colored dots indicate tasks scheduled on that date, with the color representing their priority (Urgent, High, Normal, Low). Click a date to view its tasks.',
                    icon: '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                    iconBg: 'bg-indigo-50',
                    position: 'right'
                },
                {
                    target: '#tour-task-panel',
                    title: 'Daily Task List 📝',
                    description: 'When you click a date on the calendar, all tasks scheduled for that day will appear in detail within this panel.',
                    icon: '<svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>',
                    iconBg: 'bg-orange-50',
                    position: 'left'
                }
            ],
            workspace: [
                {
                    target: null,
                    title: 'Workspace Feature',
                    description: 'Workspaces are where you collaborate with your team. You can create new workspaces, invite members, and manage shared tasks in one place.',
                    icon: '<svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
                    iconBg: 'bg-teal-50',
                    position: 'center'
                },
                {
                    target: '#create-workspace-btn',
                    title: 'Create New Workspace ➕',
                    description: 'Click this button to start creating a new workspace for your team.',
                    icon: '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>',
                    iconBg: 'bg-indigo-50',
                    position: 'bottom',
                    onAction: 'closeCreateWorkspaceModal'
                },
                {
                    target: '#create-workspace-modal form',
                    title: 'New Workspace Form 📝',
                    description: 'Fill in the workspace name and add an optional description explaining the purpose of this workspace.',
                    icon: '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                    iconBg: 'bg-emerald-50',
                    position: 'left',
                    onAction: 'openCreateWorkspaceModal'
                },
                {
                    target: '#create-workspace-modal form button[type="submit"]',
                    title: 'Save Workspace 💾',
                    description: 'Once all fields are filled, click the "Create" button to finish creating your workspace.',
                    icon: '<svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
                    iconBg: 'bg-cyan-50',
                    position: 'top',
                    onAction: 'openCreateWorkspaceModal'
                }
            ],
            workspace_selected: [
                {
                    target: '#tour-workspace-options-btn',
                    title: 'Workspace Menu ⚙️',
                    description: 'Click this three-dots button to open the workspace options.',
                    icon: '<svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 256 256"><path d="M144,128a16,16,0,1,1-16-16A16,16,0,0,1,144,128ZM128,72a16,16,0,1,0-16-16A16,16,0,0,0,128,72Zm0,112a16,16,0,1,0,16,16A16,16,0,0,0,128,184Z"></path></svg>',
                    iconBg: 'bg-gray-50',
                    position: 'left',
                    onAction: 'closeWorkspaceOptions'
                },
                {
                    target: '#tour-workspace-options-menu',
                    title: 'Workspace Options 📋',
                    description: 'Here you can view details, manage members, edit the workspace, or delete it.',
                    icon: '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>',
                    iconBg: 'bg-indigo-50',
                    position: 'left',
                    onAction: 'openWorkspaceOptions'
                }
            ],
            profile: [
                {
                    target: 'label[for="photo-input"]',
                    title: 'Upload Photo',
                    description: 'Click here to upload or change your profile picture.',
                    icon: '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>',
                    iconBg: 'bg-blue-50',
                    position: 'bottom'
                },
                {
                    target: '#photo-delete-form button',
                    title: 'Delete Photo',
                    description: 'Delete your current profile picture to return to the default avatar.',
                    icon: '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
                    iconBg: 'bg-red-50',
                    position: 'bottom'
                },
                {
                    target: '#edit-profile-btn',
                    title: 'Edit Profile',
                    description: 'Update your personal information and change your password.',
                    icon: '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                    iconBg: 'bg-indigo-50',
                    position: 'bottom'
                },
                {
                    target: '#tour-delete-account-btn',
                    title: 'Delete Account',
                    description: 'Permanently delete your account and all associated data. Please use with caution!',
                    icon: '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                    iconBg: 'bg-red-50',
                    position: 'top'
                }
            ],
            help: [
                {
                    target: null,
                    title: 'Help Page',
                    description: 'The Help Page contains FAQs and a contact form if you encounter issues or need assistance.',
                    icon: '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                    iconBg: 'bg-green-50',
                    position: 'center'
                }
            ],
            privacy: [
                {
                    target: null,
                    title: 'Privacy Policy',
                    description: 'We value your privacy. This page explains how your personal data is collected, used, and protected.',
                    icon: '<svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                    iconBg: 'bg-orange-50',
                    position: 'center'
                }
            ]
        },

        get steps() {
            return this.tourConfig[this.currentPage] || [];
        },

        get currentStepData() {
            return this.steps[this.currentStep] || null;
        },

        get isLastStepOnPage() {
            return this.currentStep === this.steps.length - 1;
        },

        pageIndex(page) {
            return this.pages.indexOf(page);
        },

        init() {
            // If user got redirected here for tour, show it
            if (this.tourConfig[this.currentPage]) {
                const tourState = JSON.parse(localStorage.getItem('onboarding_tour') || '{}');
                // Small delay so page finishes rendering
                setTimeout(() => {
                    this.active = true;
                    this.currentStep = tourState[this.currentPage + '_step'] || 0;
                    this.updateSpotlight();
                }, 600);
            }

            // Listen for window resize
            window.addEventListener('resize', () => {
                if (this.active) this.updateSpotlight();
            });
        },

        updateSpotlight() {
            const step = this.currentStepData;
            if (!step) return;

            // Handle onAction callbacks for modal open/close
            if (step.onAction) {
                this.handleAction(step.onAction);
            }

            if (!step.target || step.position === 'center') {
                // Center overlay mode — spotlight in center
                this.isCenter = true;
                const cx = window.innerWidth / 2;
                const cy = window.innerHeight / 2;
                this.spotlight = { x: cx - 1, y: cy - 1, w: 2, h: 2 };
                this.positionTooltipCenter();
                return;
            }

            this.isCenter = false;
            const el = document.querySelector(step.target);
            if (!el) {
                // Element not found, go center
                this.isCenter = true;
                const cx = window.innerWidth / 2;
                const cy = window.innerHeight / 2;
                this.spotlight = { x: cx - 1, y: cy - 1, w: 2, h: 2 };
                this.positionTooltipCenter();
                return;
            }

            // Scroll element into view smoothly (kecuali di halaman profile dan schedule)
            if (this.currentPage !== 'profile' && this.currentPage !== 'schedule') {
                el.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
            }

            // Wait for scroll then position
            setTimeout(() => {
                const rect = el.getBoundingClientRect();
                const padding = 8;
                this.spotlight = {
                    x: rect.left - padding,
                    y: rect.top - padding,
                    w: rect.width + padding * 2,
                    h: rect.height + padding * 2
                };
                this.positionTooltip(rect, step.position);
            }, 400);
        },

        handleAction(action) {
            const taskModal = document.getElementById('add-task-modal');
            const wsModal = document.getElementById('create-workspace-modal');

            if (action === 'openAddTaskModal' && taskModal) {
                taskModal.classList.remove('hidden');
                taskModal.style.pointerEvents = 'none';
            } else if (action === 'closeAddTaskModal' && taskModal) {
                taskModal.classList.add('hidden');
                taskModal.style.pointerEvents = '';
            }

            if (action === 'openCreateWorkspaceModal' && wsModal) {
                wsModal.classList.remove('hidden');
                wsModal.style.pointerEvents = 'none';
            } else if (action === 'closeCreateWorkspaceModal' && wsModal) {
                wsModal.classList.add('hidden');
                wsModal.style.pointerEvents = '';
            }

            if (action === 'openWorkspaceOptions') {
                window.dispatchEvent(new CustomEvent('open-dropdown'));
            } else if (action === 'closeWorkspaceOptions') {
                window.dispatchEvent(new CustomEvent('close-dropdown'));
            }
        },

        closeAllModals() {
            const taskModal = document.getElementById('add-task-modal');
            if (taskModal) {
                taskModal.classList.add('hidden');
                taskModal.style.pointerEvents = '';
            }
            const wsModal = document.getElementById('create-workspace-modal');
            if (wsModal) {
                wsModal.classList.add('hidden');
                wsModal.style.pointerEvents = '';
            }
            window.dispatchEvent(new CustomEvent('close-dropdown'));
        },

        positionTooltipCenter() {
            const tooltipW = 450;
            const tooltipH = 280;
            const x = (window.innerWidth - tooltipW) / 2;
            const y = (window.innerHeight - tooltipH) / 2;
            this.tooltipPosition = 'center';
            this.tooltipStyle = `left: ${x}px; top: ${y}px;`;
        },

        positionTooltip(rect, preferred) {
            const tooltipW = 450;
            const tooltipH = 280;
            const gap = 20;
            const vw = window.innerWidth;
            const vh = window.innerHeight;

            let x, y;

            // Try preferred position, fallback if no space
            const positions = [preferred, 'bottom', 'top', 'right', 'left'];

            for (const pos of positions) {
                if (pos === 'bottom' && rect.bottom + gap + tooltipH < vh) {
                    x = Math.max(16, Math.min(rect.left, vw - tooltipW - 16));
                    y = rect.bottom + gap;
                    this.tooltipPosition = 'bottom';
                    this.tooltipStyle = `left: ${x}px; top: ${y}px;`;
                    return;
                }
                if (pos === 'top' && rect.top - gap - tooltipH > 0) {
                    x = Math.max(16, Math.min(rect.left, vw - tooltipW - 16));
                    y = rect.top - gap - tooltipH;
                    this.tooltipPosition = 'top';
                    this.tooltipStyle = `left: ${x}px; top: ${y}px;`;
                    return;
                }
                if (pos === 'right' && rect.right + gap + tooltipW < vw) {
                    x = rect.right + gap;
                    y = Math.max(16, Math.min(rect.top, vh - tooltipH - 16));
                    this.tooltipPosition = 'right';
                    this.tooltipStyle = `left: ${x}px; top: ${y}px;`;
                    return;
                }
                if (pos === 'left' && rect.left - gap - tooltipW > 0) {
                    x = rect.left - gap - tooltipW;
                    y = Math.max(16, Math.min(rect.top, vh - tooltipH - 16));
                    this.tooltipPosition = 'left';
                    this.tooltipStyle = `left: ${x}px; top: ${y}px;`;
                    return;
                }
            }

            // Fallback to center
            this.positionTooltipCenter();
        },

        nextStep() {
            if (this.currentStep < this.steps.length - 1) {
                this.currentStep++;
                this.saveStepProgress();
                this.updateSpotlight();
            } else {
                this.finishPage();
            }
        },

        prevStep() {
            if (this.currentStep > 0) {
                this.currentStep--;
                // Close modals if the step we're going back to doesn't need them
                const step = this.currentStepData;
                if (!step || !step.onAction || step.onAction !== 'openAddTaskModal') {
                    this.closeAllModals();
                }
                this.saveStepProgress();
                this.updateSpotlight();
            }
        },

        goToStep(idx) {
            this.currentStep = idx;
            this.saveStepProgress();
            this.updateSpotlight();
        },

        saveStepProgress() {
            const tourState = JSON.parse(localStorage.getItem('onboarding_tour') || '{}');
            tourState[this.currentPage + '_step'] = this.currentStep;
            localStorage.setItem('onboarding_tour', JSON.stringify(tourState));
        },

        skipTour() {
            this.finishPage();
        },

        async finishPage() {
            this.active = false;
            this.closeAllModals();

            // Clear local storage for this page
            const tourState = JSON.parse(localStorage.getItem('onboarding_tour') || '{}');
            delete tourState[this.currentPage + '_step'];
            localStorage.setItem('onboarding_tour', JSON.stringify(tourState));

            // Call backend to mark this page as completed
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                await fetch('/onboarding/complete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ page: this.currentPage })
                });
            } catch (err) {
                console.error('Failed to save onboarding status:', err);
            }
        }
    }));
});
</script>
@endif
