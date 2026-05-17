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
        .workspace-box { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; margin-bottom: 32px; text-align: center; }
        .workspace-name { color: #1C427A; font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        .workspace-description { color: #475569; font-size: 14px; line-height: 1.6; font-style: italic; margin-bottom: 16px; }
        .workspace-meta { color: #94A3B8; font-size: 12px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 4px; }
        .btn-container { text-align: center; margin-top: 32px; }
        .btn { display: inline-block; background: #0E213D; color: #ffffff !important; text-decoration: none; padding: 16px 40px; border-radius: 32px; font-weight: 600; font-size: 16px; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(14, 33, 61, 0.2); }
        .footer { color: #94A3B8; font-size: 12px; margin-top: 40px; text-align: center; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="title">Verify Email Address ✉️</div>
            <div class="subtitle">
                Please click the button below to verify your email address.
            </div>
        </div>

        <p style="color: #475569; font-size: 15px; line-height: 1.6; text-align: center; margin-bottom: 0;">
            Thank you for signing up! We just need to verify your email address before you can access all features.
        </p>

        <div class="btn-container">
            <a href="{{ $url }}" class="btn">Verify Email Address</a>
        </div>

        <div class="footer">
            If you did not create an account, no further action is required.<br>
            If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:<br>
            <a href="{{ $url }}" style="word-break: break-all; color: #1C427A;">{{ $url }}</a>
        </div>
    </div>
</body>
</html>
