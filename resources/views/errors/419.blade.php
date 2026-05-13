<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f5f7fb; color: #1f2937; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { max-width: 520px; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08); text-align: center; }
        h1 { margin: 0 0 12px; font-size: 28px; }
        p { margin: 0 0 16px; line-height: 1.6; color: #4b5563; }
        a { color: #0f766e; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Page expired</h1>
            <p>Your session has expired. Please refresh the page and try again.</p>
            <a href="{{ url()->previous() }}">Go back</a>
        </div>
    </div>
</body>
</html>
