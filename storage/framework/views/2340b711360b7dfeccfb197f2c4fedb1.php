<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Styles -->
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .reset-card {
                background: white;
                border-radius: 15px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                padding: 2rem;
                width: 100%;
                max-width: 400px;
            }
            .reset-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            .reset-header h1 {
                color: #333;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }
            .reset-header p {
                color: #666;
                margin: 0;
            }
            .form-control {
                border-radius: 10px;
                border: 1px solid #e0e0e0;
                padding: 0.75rem 1rem;
                margin-bottom: 1rem;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .btn-reset {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                border-radius: 10px;
                padding: 0.75rem 1rem;
                font-weight: 500;
                width: 100%;
                color: white;
                transition: transform 0.2s;
            }
            .btn-reset:hover {
                transform: translateY(-2px);
                color: white;
            }
            .text-danger {
                font-size: 0.875rem;
                margin-top: -0.5rem;
                margin-bottom: 1rem;
            }
            .password-field {
                position: relative;
            }
            .password-toggle {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #666;
                cursor: pointer;
                padding: 0;
                font-size: 1rem;
                transition: color 0.2s ease;
            }
            .password-toggle:hover {
                color: #667eea;
            }
            .password-field .form-control {
                padding-right: 40px;
            }
        </style>
    </head>
    <body>
        <div class="reset-card">
            <div class="reset-header">
                <h1><?php echo e(config('app.name')); ?></h1>
                <p>Reset your password</p>
            </div>

            <form method="POST" action="<?php echo e(route('password.store')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="<?php echo e($request->route('token')); ?>">

                <!-- Email Address -->
                <div>
                    <input id="email" class="form-control" type="email" name="email" value="<?php echo e(old('email', $request->email)); ?>" required autofocus autocomplete="username" placeholder="Email Address">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password -->
                <div class="password-field">
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="New Password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="password-icon"></i>
                    </button>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Confirm Password -->
                <div class="password-field">
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="bi bi-eye" id="password_confirmation-icon"></i>
                    </button>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-reset">
                        <?php echo e(__('Reset Password')); ?>

                    </button>
                </div>

                <div class="text-center mt-3">
                    <a class="text-decoration-none" href="<?php echo e(route('login')); ?>">
                        <?php echo e(__('Back to Login')); ?>

                    </a>
                </div>
            </form>
        </div>

        <script>
            function togglePassword(fieldId) {
                const passwordField = document.getElementById(fieldId);
                const passwordIcon = document.getElementById(fieldId + '-icon');
                
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    passwordIcon.className = 'bi bi-eye-slash';
                } else {
                    passwordField.type = 'password';
                    passwordIcon.className = 'bi bi-eye';
                }
            }
        </script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
<?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/auth/reset-password.blade.php ENDPATH**/ ?>