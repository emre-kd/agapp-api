<!-- resources/views/emails/verification.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Email Verification</h2>
    <p>Your verification code is:</p>

    <div class="code">{{ $code }}</div>

    <p>This code will expire in 15 minutes.</p>

    <div class="footer">
        <p>If you didn't request this code, please ignore this email.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>
