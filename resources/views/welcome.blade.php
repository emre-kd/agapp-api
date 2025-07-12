<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/public/logo.png" type="image/x-icon">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #ff4500;
            font-size: 48px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            color: #555555;
        }

        .emoji {
            font-size: 80px;
        }
    </style>

</head>

<body>
    <div class="container">
        <h1>Agalar şuan bir şey diyemiyor</h1>
        <p>Web site güncelleniyor.</p>
        <div class="emoji">🚧</div>
        <p>Agalar Ne Diyor v2.0</p>
    </div>
</body>

</html>
