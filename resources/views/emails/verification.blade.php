<!-- resources/views/emails/verification.blade.php -->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>E-posta Doğrulama</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .code {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 4px;
            text-align: center;
            padding: 20px;
            background-color: #f0f4f8;
            color: #2d3436;
            border-radius: 6px;
            margin: 30px auto;
            width: fit-content;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        .footer {
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>E-posta Doğrulama</h2>

        <p>Aşağıdaki doğrulama kodunu uygulamaya girerek e-posta adresinizi onaylayabilirsiniz:</p>

        <div class="code">{{ $code }}</div>

        <p>Bu kod <strong>15 dakika</span> içinde geçerliliğini yitirecektir.</p>

        <div class="footer">
            <p>Eğer bu işlemi siz gerçekleştirmediyseniz bu e-postayı göz ardı edebilirsiniz.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
