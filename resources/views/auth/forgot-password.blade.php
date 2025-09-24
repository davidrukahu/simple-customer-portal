<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
        
        <!-- Styles -->
        <style>
            :root {
                --primary-color: #6366f1;
                --primary-hover: #5855eb;
                --secondary-color: #64748b;
                --light-bg: #f8fafc;
                --border-color: #e2e8f0;
                --text-muted: #64748b;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background-color: var(--light-bg);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            .forgot-card {
                background: white;
                border-radius: 12px;
                box-shadow: var(--shadow-md);
                padding: 2rem;
                width: 100%;
                max-width: 400px;
                border: 1px solid var(--border-color);
            }
            .forgot-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            .forgot-header h1 {
                color: #1e293b;
                font-weight: 600;
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }
            .forgot-header p {
                color: var(--text-muted);
                margin: 0;
                font-size: 0.9rem;
            }
            .form-control {
                border-radius: 8px;
                border: 1px solid var(--border-color);
                padding: 0.75rem 1rem;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                transition: all 0.2s ease;
                background-color: white;
            }
            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
                outline: none;
            }
            .form-control::placeholder {
                color: var(--text-muted);
            }
            .btn-send {
                background-color: var(--primary-color);
                border: none;
                border-radius: 8px;
                padding: 0.75rem 1rem;
                font-weight: 500;
                width: 100%;
                color: white;
                transition: all 0.2s ease;
                font-size: 0.9rem;
            }
            .btn-send:hover {
                background-color: var(--primary-hover);
            }
            .text-danger {
                color: #ef4444;
                font-size: 0.8rem;
                margin-top: 0.25rem;
            }
            .alert-success {
                background-color: #d1edff;
                border-color: #bee5eb;
                color: #0c5460;
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }
            .brand-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 1.25rem;
                color: var(--primary-color);
            }
        </style>
    </head>
    <body>
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="brand-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h1>{{ config('app.name') }}</h1>
                <p>Forgot your password?</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email Address">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-send">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a class="text-decoration-none" href="{{ route('login') }}">
                        {{ __('Back to Login') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
