<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Forbidden</title>
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
            <h1>Access forbidden</h1>
            <p>You do not have permission to access this page.</p>
            <a href="{{ url('/') }}">Go to homepage</a>
        </div>
    </div>
</body>
</html>
