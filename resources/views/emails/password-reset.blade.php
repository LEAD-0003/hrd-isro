<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - ISRO HRD</title>
    <style>
        body {
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
        }

        .email-card {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: auto;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: {{ $headerColor ?? '#0a192f' }};
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }

        .content {
            padding: 40px 30px;
            line-height: 1.6;
            color: #334155;
            text-align: center;
        }

        .content p {
            font-size: 16px;
            margin-bottom: 25px;
        }

        .btn-container {
            margin: 35px 0;
        }

        .btn {
            display: inline-block;
            background: #dc2626; /* ISRO Red style for action */
            color: white !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .footer {
            background: #f8fafc;
            padding: 25px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
        }

        .divider {
            margin: 20px 0;
            border-top: 1px solid #e2e8f0;
        }

        .warning-text {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 20px;
        }
    </style>
</head>
<body>
   <div class="email-card">
    <div class="header" style="background: {{ $headerColor }}">
        <h2>{{ $mailTitle }}</h2>
    </div>
    <div class="content">
        <p>Dear User,</p>
        <div>{!! $mailMessage !!}</div>
        <div class="btn-container">
            <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        </div>
    </div>
    <div class="footer">© {{ date('Y') }} ISRO HRD</div>
</div>
</body>
</html>