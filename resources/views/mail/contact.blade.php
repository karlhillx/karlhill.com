<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(to right, #4f46e5, #06b6d4); padding: 20px; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0;">New Contact Form Submission</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <div style="margin-bottom: 25px;">
            <strong style="color: #374151; display: block; margin-bottom: 5px;">Name:</strong>
            <p style="margin: 0; color: #6b7280;">{{ $name }}</p>
        </div>
        
        <div style="margin-bottom: 25px;">
            <strong style="color: #374151; display: block; margin-bottom: 5px;">Email:</strong>
            <p style="margin: 0;">
                <a href="mailto:{{ $email }}" style="color: #4f46e5; text-decoration: none;">{{ $email }}</a>
            </p>
        </div>
        
        <div style="margin-bottom: 25px;">
            <strong style="color: #374151; display: block; margin-bottom: 5px;">Message:</strong>
            <div style="background: white; padding: 15px; border-radius: 6px; border-left: 4px solid #4f46e5; margin-top: 10px;">
                <p style="margin: 0; color: #374151; white-space: pre-wrap;">{{ $messageContent }}</p>
            </div>
        </div>
        
        <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 30px;">
            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                <strong>IP Address:</strong> {{ $ip }}<br>
                <strong>Submitted:</strong> {{ now()->format('F j, Y \a\t g:i A') }}
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px; padding: 15px; background: #f3f4f6; border-radius: 8px;">
        <p style="margin: 0; font-size: 12px; color: #6b7280;">
            This email was sent from your website's contact form.
        </p>
    </div>
</body>
</html>

