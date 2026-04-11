<!DOCTYPE html>
<html>

<head>
    <style>
        .email-card {
            font-family: sans-serif;
            max-width: 600px;
            margin: auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: {{ $headerColor }};
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
            line-height: 1.6;
            color: #334155;
        }

        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }

        .badge {
            background: #dcfce7;
            color: #166534;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
        }

        .status {
            font-weight: bold;
            padding: 6px 14px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <div class="email-card">

        <div class="header">
            <h2>{{ $mailTitle }}</h2>
        </div>

        <div class="content">

            <p>Dear {{ $application->nominee_name ?? 'Participant' }},</p>

            <p>{!! $mailMessage !!}</p>

            <div style="background:#f1f5f9;padding:15px;border-radius:8px">

                <p><strong>Program:</strong> {{ $training->title }}</p>

                <p>
                    <strong>Duration:</strong>
                    {{ \Carbon\Carbon::parse($training->start_date)->format('d M Y') }}
                    to
                    {{ \Carbon\Carbon::parse($training->end_date)->format('d M Y') }}
                </p>

                @if (isset($allottedSeats))
                    <p><strong>Allotted Seats:</strong> <span class="badge">{{ $allottedSeats }}</span></p>
                @endif



            </div>

        </div>

        <div class="footer">
            © {{ date('Y') }} ISRO HRD Training Management System
        </div>

    </div>
</body>

</html>
