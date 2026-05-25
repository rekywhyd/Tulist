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
        .info-box { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 24px; margin-bottom: 32px; text-align: center; }
        .info-text { color: #475569; font-size: 15px; line-height: 1.6; }
        .btn-container { text-align: center; margin-top: 32px; }
        .btn { display: inline-block; background: #0E213D; color: #ffffff !important; text-decoration: none; padding: 16px 40px; border-radius: 32px; font-weight: 600; font-size: 16px; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(14, 33, 61, 0.2); }
        .footer { color: #94A3B8; font-size: 12px; margin-top: 40px; text-align: center; line-height: 1.6; border-top: 1px solid #E2E8F0; padding-top: 24px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="title">Reset Password 🔒</div>
            <div class="subtitle">
                Hello <strong>{{ $notifiable->name }}</strong>,<br>
                You are receiving this email because we received a password reset request for your account.
            </div>
        </div>

        <div class="info-box">
            <div class="info-text">
                Click the button below to reset your password. This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ $url }}" class="btn">Reset Password</a>
        </div>

        <div class="footer">
            If you did not request a password reset, no further action is required.<br><br>
            If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
            <a href="{{ $url }}" style="color: #64748B; word-break: break-all;">{{ $url }}</a>
        </div>
    </div>
</body>
</html>
