<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 20px;
            }
            .email-container {
                max-width: 600px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }
            .email-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2rem;
                text-align: center;
            }
            .email-header h1 {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 600;
            }
            .email-body {
                padding: 2rem;
            }
            .email-body h2 {
                color: #333;
                margin-top: 0;
            }
            .email-body p {
                color: #666;
                line-height: 1.6;
            }
            .btn-reset {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                padding: 1rem 2rem;
                border-radius: 10px;
                font-weight: 500;
                margin: 1rem 0;
            }
            .email-footer {
                background: #f8f9fa;
                padding: 1rem 2rem;
                text-align: center;
                color: #666;
                font-size: 0.875rem;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                <h1>{{ config('app.name') }}</h1>
            </div>
            
            <div class="email-body">
                <h2>Password Reset Request</h2>
                
                <p>Hello {{ $name }},</p>
                
                <p>We received a request to reset your password for your {{ config('app.name') }} account.</p>
                
                <p>Click the button below to reset your password:</p>
                
                <a href="{{ $actionUrl }}" class="btn-reset">Reset Password</a>
                
                <p>This password reset link will expire in {{ $count }} minutes.</p>
                
                <p>If you did not request a password reset, please ignore this email.</p>
                
                <p>Best regards,<br>
                {{ config('app.name') }} Team</p>
            </div>
            
            <div class="email-footer">
                <p>This email was sent from {{ config('app.name') }}. If you have any questions, please contact us.</p>
            </div>
        </div>
    </body>
</html>
