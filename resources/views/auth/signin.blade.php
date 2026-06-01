<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Prahari Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #7b6b43;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .signin-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo-container img {
            width: 100px;
            margin-bottom: 10px;
        }
        .form-label {
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }
        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.2);
        }
        .btn-primary {
            background-color: #111827;
            border-color: #111827;
            padding: 10px;
            font-weight: 500;
            border-radius: 6px;
            font-size: 15px;
        }
        .btn-primary:hover {
            background-color: #374151;
            border-color: #374151;
        }
        .link-text {
            color: #111827;
            text-decoration: none;
            font-weight: 600;
        }
        .link-text:hover {
            text-decoration: underline;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
            border-radius: 6px;
            font-size: 13px;
        }
        .text-muted {
            color: #6b7280 !important;
        }
    </style>
</head>
<body>

<div class="signin-card" >
    <div class="logo-container">
        <!-- Reusing the logo from welcome page -->
        <img src="/images/prahari-logo.png" alt="Prahari Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
        <h3 class="fw-bold mt-2 mb-0" style="color: #1e293b; display: none; text-transform: uppercase;">Prahari</h3>
    </div>

    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="color: #111827;">Sign In</h4>
        <p class="text-muted small mb-0">Welcome back! Please enter your details.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger p-2 mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('verifyLoginCredentials') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                <a href="#" class="link-text" style="font-size: 12px; font-weight: 500;">Forgot password?</a>
            </div>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="mb-4 form-check mt-3">
            <input type="checkbox" class="form-check-input" id="remember" name="remember_me" value="1">
            <label class="form-check-label text-muted" for="remember" style="font-size: 13px;">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-4" style="background-color: #e1bb80">Sign In</button>

        <div class="text-center text-muted" style="font-size: 14px;">
            Don't have an account? <a href="{{ route('signup') }}" class="link-text">Sign Up</a>
        </div>
    </form>
</div>

</body>
</html>