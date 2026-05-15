<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F0F4F8; padding: 40px; margin: 0; }
        .card { max-width: 520px; margin: 0 auto; background: #fff; border-radius: 24px; padding: 48px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { text-align: center; margin-bottom: 32px; }
        .title { color: #132C51; font-size: 28px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px; }
        .subtitle { color: #64748B; font-size: 16px; line-height: 1.5; margin-bottom: 32px; }
        .workspace-box { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; margin-bottom: 32px; }
        .workspace-name { color: #1C427A; font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        .workspace-description { color: #475569; font-size: 14px; line-height: 1.6; font-style: italic; margin-bottom: 16px; }
        .workspace-meta { color: #94A3B8; font-size: 12px; font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .btn-container { text-align: center; margin-top: 32px; }
        .btn { display: inline-block; background: #0E213D; color: #ffffff !important; text-decoration: none; padding: 16px 40px; border-radius: 32px; font-weight: 600; font-size: 16px; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(14, 33, 61, 0.2); }
        .footer { color: #94A3B8; font-size: 12px; margin-top: 40px; text-align: center; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="title">You're Invited! 🎉</div>
            <div class="subtitle">
                <strong>{{ $invitation->inviter->name }}</strong> wants you to join their collaborative workspace.
            </div>
        </div>

        <div class="workspace-box">
            <div class="workspace-name">{{ $workspace->name }}</div>
            
            @if($workspace->description)
                <div class="workspace-description">
                    "{{ $workspace->description }}"
                </div>
            @endif

            <div class="workspace-meta">
                <span>Created on {{ $workspace->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <p style="color: #475569; font-size: 15px; line-height: 1.6; text-align: center; margin-bottom: 0;">
            Accept this invitation to start collaborating with the team in this workspace.
        </p>

        <div class="btn-container">
            <a href="{{ $acceptUrl }}" class="btn">Join Workspace</a>
        </div>

        <div class="footer">
            This invitation expires in 7 days.<br>
            If you weren't expecting this, you can safely ignore this email.
        </div>
    </div>
</body>
</html>
