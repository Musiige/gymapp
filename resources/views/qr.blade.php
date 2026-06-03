<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Check-in</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #111;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: sans-serif;
        }

        .card {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 20px;
            padding: 48px 40px;
            text-align: center;
            max-width: 420px;
            width: 90%;
        }

        .gym-name {
            color: #fff;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #888;
            font-size: 14px;
            margin-bottom: 36px;
        }

        .qr-wrapper {
            background: #fff;
            display: inline-block;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 32px;
        }

        .instructions {
            color: #aaa;
            font-size: 13px;
            line-height: 1.8;
        }

        .instructions span {
            color: #fff;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="gym-name">GYM NAME</div>
        <div class="subtitle">Scan to check in or register</div>

        <div class="qr-wrapper">
            {!! QrCode::size(220)->generate('http://192.168.1.65/gymapp/public/register') !!}
        </div>

        <div class="instructions">
            <span>New member?</span> Scan and register.<br>
            <span>Already a member?</span> Scan and log in.<br><br>
            Point your phone camera at the code above.
        </div>
    </div>
</body>
</html>