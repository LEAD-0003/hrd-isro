<!DOCTYPE html>
<html>
<head>
    <style>
        .email-card { font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .header { background: #1e3a8a; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; line-height: 1.6; color: #334155; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
        .badge { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-weight: bold; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h2>ISRO HRD - New Training Notification</h2>
        </div>
        <div class="content">
            <p>Dear HRD Coordinator,</p>
            <p>A new training program has been scheduled by HQ. Details are provided below:</p>
            
            <div style="background: #f1f5f9; padding: 15px; border-radius: 8px;">
                <p><strong>Program:</strong> {{ $training->title }}</p>
                <p><strong>Duration:</strong> {{ $training->start_date->format('d M Y') }} to {{ $training->end_date->format('d M Y') }}</p>
                <p><strong>Allotted Seats for Your Centre:</strong> <span class="badge">{{ $allottedSeats }}</span></p>
            </div>

            <p style="margin-top: 20px;">Kindly nominate the eligible participants from your centre through the portal before the last date.</p>
            
        </div>
        <div class="footer">
            © {{ date('Y') }} ISRO HRD Training Management System. All Rights Reserved.
        </div>
    </div>
</body>
</html>