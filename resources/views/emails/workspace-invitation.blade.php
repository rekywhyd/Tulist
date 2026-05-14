<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #E8EEF9; padding: 40px; }
        .card { max-width: 520px; margin: 0 auto; background: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .title { color: #132C51; font-size: 24px; font-weight: 700; margin-bottom: 8px; }
        .subtitle { color: #717C8F; font-size: 14px; margin-bottom: 28px; }
        .workspace-name { color: #1C427A; font-size: 20px; font-weight: 600; background: #E8EEF9; padding: 12px 20px; border-radius: 12px; margin-bottom: 24px; text-align: center; }
        .btn { display: inline-block; background: #0E213D; color: #fff !important; text-decoration: none; padding: 14px 36px; border-radius: 30px; font-weight: 600; font-size: 15px; }
        .btn:hover { background: #1C427A; }
        .footer { color: #9CA3AF; font-size: 12px; margin-top: 32px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="title">You've been invited! 🎉</div>
        <div class="subtitle">
            <strong>{{ $invitation->inviter->name }}</strong> has invited you to join the workspace:
        </div>
        <div class="workspace-name">{{ $workspace->name }}</div>
        <p style="color: #4B5563; font-size: 14px; line-height: 1.6;">
            Click the button below to accept the invitation and join the team.
        </p>
        <div style="text-align: center; margin: 28px 0;">
            <a href="{{ $acceptUrl }}" class="btn">Accept Invitation</a>
        </div>
        <div class="footer">
            This invitation will expire in 7 days.<br>
            If you did not expect this invitation, you can safely ignore this email.
        </div>
    </div>
</body>
</html>
