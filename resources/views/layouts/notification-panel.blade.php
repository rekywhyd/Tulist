{{-- Notification Badge on bell button --}}
<span id="notif-badge" class="absolute hidden w-4 h-4 text-[9px] font-bold text-white bg-red-500 rounded-full -top-0.5 -right-0.5 flex items-center justify-center leading-none">0</span>

{{-- Notifications Panel --}}
<div id="notifications-popup" class="fixed left-[88px] top-16 hidden w-[380px] max-h-[85vh] bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-200/60 z-[60] flex flex-col font-poppins mb-10 overflow-hidden">
    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-[#0E213D] rounded-t-2xl">
        <h3 class="text-base font-bold text-white tracking-wide">Notifications</h3>
        <div class="flex gap-1.5">
            <button id="mark-all-read-btn" title="Mark all as read" class="p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
            <button id="delete-all-btn" title="Delete all" class="p-1.5 text-white/70 hover:text-red-300 hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H9.862a2 2 0 01-1.995-1.858L7 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Notification List --}}
    <div id="notifications-list" class="flex-1 overflow-y-auto custom-scrollbar" style="max-height: calc(85vh - 52px);">
        <div id="notif-loading" class="flex items-center justify-center py-10">
            <div class="w-6 h-6 border-2 border-blue-500 rounded-full border-t-transparent animate-spin"></div>
        </div>
        <div id="notif-empty" class="hidden px-6 py-10 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p class="text-sm text-gray-400">No notifications yet</p>
        </div>
    </div>
</div>


<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #E2E8F0;
        border-radius: 20px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #CBD5E0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('notifications-btn');
    const popup = document.getElementById('notifications-popup');
    const list = document.getElementById('notifications-list');
    const badge = document.getElementById('notif-badge');
    const loading = document.getElementById('notif-loading');
    const empty = document.getElementById('notif-empty');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function timeAgo(dateStr) {
        const now = new Date(), d = new Date(dateStr);
        const diff = Math.floor((now - d) / 1000);
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff/60) + 'm ago';
        if (diff < 86400) return Math.floor(diff/3600) + 'h ago';
        if (diff < 604800) return Math.floor(diff/86400) + 'd ago';
        return d.toLocaleDateString('en-US', {day:'2-digit', month:'short'});
    }

    function getIcon(type) {
        if (type === 'due_reminder') return '<div class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-50 text-amber-500 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>';
        if (type === 'workspace_invitation') return '<div class="flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 text-blue-500 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>';
        if (type === 'mention') return '<div class="flex items-center justify-center w-9 h-9 rounded-xl bg-purple-50 text-purple-500 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg></div>';
        if (type === 'mention_completed') return '<div class="flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50 text-emerald-500 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg></div>';
        return '<div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gray-50 text-gray-400 flex-shrink-0"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>';
    }

    function renderNotifications(notifications) {
        // Remove old rendered items but keep loading/empty
        list.querySelectorAll('.notif-item').forEach(el => el.remove());
        loading.classList.add('hidden');

        if (!notifications.length) {
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        notifications.forEach(n => {
            const item = document.createElement('div');
            item.className = 'notif-item flex gap-3 px-4 py-3 border-b border-gray-50 transition-all duration-200 cursor-pointer hover:bg-blue-50/50 ' + (n.is_read ? 'opacity-60' : 'bg-white');
            item.dataset.id = n.id;

            let actions = '';
            if (n.type === 'workspace_invitation' && !n.is_read) {
                actions = `<div class="flex gap-1.5 mt-2">
                    <button class="accept-invite-btn px-3 py-1 text-[11px] font-semibold text-white bg-green-500 hover:bg-green-600 rounded-lg transition-colors" data-id="${n.id}">Accept</button>
                    <button class="decline-invite-btn px-3 py-1 text-[11px] font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors" data-id="${n.id}">Decline</button>
                </div>`;
            }

            let clickHandler = '';
            if (n.type === 'due_reminder' && n.data && n.data.task_id) {
                clickHandler = `data-task-id="${n.data.task_id}"`;
            } else if (n.type === 'workspace_invitation' && n.data && n.data.workspace_id && n.is_read) {
                clickHandler = `data-workspace-id="${n.data.workspace_id}"`;
            }

            item.innerHTML = `
                ${getIcon(n.type)}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[13px] font-semibold text-[#132C51] leading-tight">${n.title}</p>
                        <span class="text-[10px] text-gray-400 flex-shrink-0 mt-0.5">${timeAgo(n.created_at)}</span>
                    </div>
                    <p class="text-[12px] text-gray-500 mt-0.5 leading-snug">${n.message}</p>
                    ${actions}
                </div>
                ${!n.is_read ? '<div class="w-2 h-2 mt-1.5 bg-blue-500 rounded-full flex-shrink-0"></div>' : ''}
            `;

            // Click to redirect for due reminders and mentions
            if ((n.type === 'due_reminder' || n.type === 'mention' || n.type === 'mention_completed') && n.data && n.data.task_id) {
                item.addEventListener('click', function(e) {
                    if (e.target.closest('.accept-invite-btn') || e.target.closest('.decline-invite-btn')) return;
                    markRead(n.id);
                    popup.classList.add('hidden');

                    if (typeof window.showTaskDetails === 'function') {
                        window.dispatchEvent(new CustomEvent('open-task-details', { detail: { taskId: n.data.task_id } }));
                    } else {
                        window.location.href = '/home?open_task=' + n.data.task_id;
                    }
                });
            } else if (n.type === 'workspace_invitation' && n.is_read && n.data && n.data.workspace_id) {
                item.addEventListener('click', function() {
                    window.location.href = '/workspace?workspace_id=' + n.data.workspace_id;
                });
            }

            list.appendChild(item);
        });

        // Bind accept/decline buttons
        list.querySelectorAll('.accept-invite-btn').forEach(b => {
            b.addEventListener('click', function(e) {
                e.stopPropagation();
                handleInvitation(this.dataset.id, 'accept');
            });
        });
        list.querySelectorAll('.decline-invite-btn').forEach(b => {
            b.addEventListener('click', function(e) {
                e.stopPropagation();
                handleInvitation(this.dataset.id, 'decline');
            });
        });
    }

    function markRead(id) {
        fetch('/notifications/' + id, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ is_read: true })
        });
    }

    function handleInvitation(notifId, action) {
        const url = '/notifications/' + notifId + '/' + action + '-invitation';
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                updateBadge();
                if (action === 'accept' && data.workspace_id) {
                    setTimeout(() => window.location.href = '/workspace?workspace_id=' + data.workspace_id, 500);
                }
            } else {
                alert(data.error || 'Action failed.');
                loadNotifications();
            }
        })
        .catch(() => alert('Network error.'));
    }

    function loadNotifications() {
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        list.querySelectorAll('.notif-item').forEach(el => el.remove());

        fetch('/notifications', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => renderNotifications(data))
            .catch(() => { loading.classList.add('hidden'); empty.classList.remove('hidden'); });
    }

    function updateBadge() {
        fetch('/notifications/unread-count', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                    badge.classList.add('flex');
                } else {
                    badge.classList.add('hidden');
                    badge.classList.remove('flex');
                }
            })
            .catch(() => {});
    }

    // Toggle popup
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isHidden = popup.classList.contains('hidden');
        popup.classList.toggle('hidden');
        if (isHidden) loadNotifications();
    });

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!popup.contains(e.target) && !btn.contains(e.target)) {
            popup.classList.add('hidden');
        }
    });

    // Mark all read
    document.getElementById('mark-all-read-btn').addEventListener('click', function() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => { loadNotifications(); updateBadge(); });
    });

    // Delete all
    document.getElementById('delete-all-btn').addEventListener('click', function() {
        if (!confirm('Delete all notifications?')) return;
        fetch('/notifications/delete-all', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).then(() => { loadNotifications(); updateBadge(); });
    });

    // Initial badge update & periodic refresh
    updateBadge();
    setInterval(updateBadge, 60000);
});
</script>
