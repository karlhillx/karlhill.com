<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="color-scheme" content="light dark">
    <title>New message via karlhill.com</title>
</head>
<body style="margin:0;padding:24px;background:#f5f5f5;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#171717;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;">
        <div style="background:#080808;padding:20px 24px;">
            <p style="margin:0;color:#f97316;font-size:12px;letter-spacing:.15em;text-transform:uppercase;">New contact message</p>
        </div>
        <div style="padding:24px;">
            <p style="margin:0 0 4px;font-size:13px;color:#737373;">From</p>
            <p style="margin:0 0 16px;font-size:16px;font-weight:600;">
                {{ $senderName }}
                &lt;<a href="mailto:{{ $senderEmail }}" style="color:#c2410c;text-decoration:none;">{{ $senderEmail }}</a>&gt;
            </p>
            <p style="margin:0 0 4px;font-size:13px;color:#737373;">Message</p>
            <div style="font-size:15px;line-height:1.6;white-space:pre-wrap;">{{ $body }}</div>
        </div>
        <div style="padding:16px 24px;border-top:1px solid #e5e5e5;">
            <p style="margin:0;font-size:12px;color:#a3a3a3;">Reply directly to this email to respond to {{ $senderName }}.</p>
        </div>
    </div>
</body>
</html>
