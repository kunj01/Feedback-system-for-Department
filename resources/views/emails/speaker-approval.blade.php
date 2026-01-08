<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Approved - Feedback Requested</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f7fafc;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }
        .event-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .detail-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-label {
            font-weight: bold;
            color: #4a5568;
            display: inline-block;
            width: 120px;
        }
        .button {
            display: inline-block;
            background: #48bb78;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background: #38a169;
        }
        .footer {
            text-align: center;
            color: #718096;
            font-size: 14px;
            padding: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .message-box {
            background: #edf2f7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #4299e1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Event Approved!</h1>
        <p>Your session has been confirmed</p>
    </div>

    <div class="content">
        <p>Dear {{ $speaker->name }},</p>

        <p>We are pleased to inform you that your speaking engagement at our institution has been <strong>approved</strong>!</p>

        <div class="event-details">
            <h3 style="margin-top: 0; color: #667eea;">📅 Event Details</h3>
            <div class="detail-row">
                <span class="detail-label">Speaker Name:</span>
                <span>{{ $speaker->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Department:</span>
                <span>{{ $speaker->department }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Venue:</span>
                <span>{{ $speaker->venue }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span>{{ $speaker->date->format('l, F d, Y') }}</span>
            </div>
            <div class="detail-row" style="border-bottom: none;">
                <span class="detail-label">Time:</span>
                <span>{{ \Carbon\Carbon::parse($speaker->time)->format('h:i A') }}</span>
            </div>
        </div>

        <div class="message-box">
            <p style="margin: 0;"><strong>📝 Feedback Request</strong></p>
            <p style="margin: 10px 0 0 0;">After the event, we kindly request you to provide your valuable feedback about the event organization, venue facilities, hospitality, and overall experience. Your feedback will help us improve future events.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ $feedbackUrl }}" class="button">
                🔗 Click Here to Provide Feedback
            </a>
        </div>

        <p style="font-size: 14px; color: #718096;">
            <em>Note: This is a unique link generated specifically for you. Please use it to submit your feedback after the event.</em>
        </p>

        <p>We look forward to your session and thank you in advance for taking the time to share your feedback!</p>

        <p>Best regards,<br>
        <strong>{{ config('app.name') }}</strong></p>
    </div>

    <div class="footer">
        <p>If you have any questions, please contact the event coordinator.</p>
        <p style="font-size: 12px; color: #a0aec0;">This is an automated email. Please do not reply directly to this message.</p>
    </div>
</body>
</html>
