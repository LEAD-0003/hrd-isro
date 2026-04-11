<!DOCTYPE html>
<html>
<head>
    <style>
        .email-card { font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .header { background: #0284c7; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; line-height: 1.6; color: #334155; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
        .button { display: inline-block; background: #0284c7; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h2>Training Completed!</h2>
        </div>
        <div class="content">
            <p>Dear {{ $application->nominee_name ?? 'Participant' }},</p>
            
            <p>Congratulations on successfully completing the <strong>{{ $application->training->title }}</strong> program!</p>
            
            <p>To help us improve our future programs, we would greatly appreciate it if you could take a few minutes to share your experience and feedback with us.</p>
            
            
        </div>
        <div class="footer">
            © {{ date('Y') }} ISRO HRD Training Management System
        </div>
    </div>
</body>
</html>