<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Due Reminder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #F0F4F8;
            padding: 40px 16px;
            margin: 0;
            color: #334155;
        }
        .card {
            max-width: 540px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 10px 25px rgba(19, 44, 81, 0.06);
            border: 1px solid #E2E8F0;
        }
        .header {
            text-align: center;
            margin-bottom: 28px;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-1day {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .badge-1hour {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .title {
            color: #132C51;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .subtitle {
            color: #64748B;
            font-size: 15px;
            line-height: 1.5;
        }
        .task-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-left: 4px solid #3B82F6;
            border-radius: 14px;
            padding: 20px;
            margin: 24px 0;
        }
        .task-title {
            color: #132C51;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .task-desc {
            color: #475569;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 14px;
        }
        .task-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 13px;
            color: #64748B;
            padding-top: 10px;
            border-top: 1px dashed #CBD5E1;
        }
        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .priority-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 8px;
            text-transform: uppercase;
        }
        .priority-high { background: #FEE2E2; color: #DC2626; }
        .priority-medium { background: #FEF3C7; color: #D97706; }
        .priority-low { background: #E0E7FF; color: #4F46E5; }

        .btn-container {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .btn {
            display: inline-block;
            background: #132C51;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 34px;
            border-radius: 28px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 14px rgba(19, 44, 81, 0.2);
        }
        .footer {
            color: #94A3B8;
            font-size: 12px;
            margin-top: 32px;
            text-align: center;
            line-height: 1.6;
            border-top: 1px solid #F1F5F9;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            @if($reminderType === '1_hour')
                <div class="badge badge-1hour">⏰ Due in 1 Hour</div>
            @else
                <div class="badge badge-1day">📅 Due Tomorrow</div>
            @endif
            <div class="title">Task Deadline Reminder</div>
            <div class="subtitle">
                Hello <strong>{{ $user->name }}</strong>, you have a task that is approaching its deadline.
            </div>
        </div>

        <div class="task-box">
            <div class="task-title">{{ $task->title }}</div>
            @if($task->description)
                <div class="task-desc">
                    {{ Str::limit($task->description, 180) }}
                </div>
            @endif

            <div class="task-meta">
                <div class="meta-item">
                    <span>🗓️ <strong>Due:</strong> {{ $dueFormatted }}</span>
                </div>
                @if($task->priority)
                    <div class="meta-item">
                        @php
                            $pClass = match(strtolower($task->priority)) {
                                'high' => 'priority-high',
                                'medium' => 'priority-medium',
                                default => 'priority-low',
                            };
                        @endphp
                        <span class="priority-badge {{ $pClass }}">{{ $task->priority }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ $taskUrl }}" class="btn">View & Complete Task</a>
        </div>

        <div class="footer">
            You received this reminder because you created this task or are a member of its workspace.<br>
            © {{ date('Y') }} {{ config('app.name', 'Tulist') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
