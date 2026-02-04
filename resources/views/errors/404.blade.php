<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Page Not Found</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ assetImage(readconfig('site_logo')) }}" type="image/svg+xml">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .error-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            width: 100%;
        }

        .error-card img {
            max-width: 300px;
            margin-bottom: 30px;
        }

        .error-card h1 {
            font-size: 32px;
            color: #f0ad4e;
            margin-bottom: 15px;
        }

        .error-card p {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 5px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn-primary {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .text-muted-small {
            font-size: 12px;
            color: #adb5bd;
            margin-top: 20px;
            display: block;
        }

        .alert {
            text-align: left;
            background: #f8d7da;
            color: #842029;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-card">
            <!-- Large Centered Image -->
            <img src="{{ asset('assets/images/404-error.jpg') }}" alt="Page Not Found">

            <h1>404 | Page Not Found</h1>

            <!-- Show user information if logged in -->
            @auth
            <p>You are logged in as <strong>{{ auth()->user()->name }}</strong>. The page or action
                <strong>{{ $action ?? request()->path() }}</strong> could not be found.
            </p>
            @else
            <p>The page or action <strong>{{ $action ?? request()->path() }}</strong> could not be found.</p>
            @endauth

            <p>Something might be broken, or the URL may be incorrect. Please check the URL or try one of the options below.</p>

            <div>
                <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
                <a href="javascript:location.reload()" class="btn btn-secondary">Refresh Page</a>
            </div>

            <p>If the problem persists, contact the <strong>JM Innovatech Support Team</strong> at
                <a href="tel:0791446968">0791 446 968</a> or visit our website contact page:
                <a href="https://jminnovatechsolution.co.ke/contact">https://jminnovatechsolution.co.ke/contact</a>
            </p>

            <span class="text-muted-small">Error Code: 404</span>
        </div>
    </div>
</body>

</html>