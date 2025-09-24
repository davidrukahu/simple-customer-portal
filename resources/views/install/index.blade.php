<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Portal - Installation</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
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
            padding: 2rem 0;
        }
        
        .install-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .install-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        
        .install-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .install-header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }
        
        .install-header p {
            margin: 0.5rem 0 0;
            opacity: 0.9;
        }
        
        .install-body {
            padding: 2rem;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }
        
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--border-color);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .step.active .step-number {
            background: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background: #10b981;
            color: white;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(99 102 241 / 0.1);
            outline: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .requirements-list {
            list-style: none;
            padding: 0;
        }
        
        .requirements-list li {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .requirements-list li:last-child {
            border-bottom: none;
        }
        
        .requirement-icon {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .requirement-icon.success {
            color: #10b981;
        }
        
        .requirement-icon.error {
            color: #ef4444;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-card">
            <div class="install-header">
                <h1><i class="bi bi-shield-check me-2"></i>Customer Portal</h1>
                <p>Installation Wizard</p>
            </div>
            
            <div class="install-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step active" id="step-1">
                        <div class="step-number">1</div>
                        <span>Requirements</span>
                    </div>
                    <div class="step" id="step-2">
                        <div class="step-number">2</div>
                        <span>Configuration</span>
                    </div>
                    <div class="step" id="step-3">
                        <div class="step-number">3</div>
                        <span>Installation</span>
                    </div>
                </div>
                
                <!-- Step 1: Requirements Check -->
                <div id="requirements-step" class="step-content">
                    <h3 class="mb-3">System Requirements</h3>
                    <p class="text-muted mb-4">Checking if your server meets the minimum requirements...</p>
                    
                    <div id="requirements-loading" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Checking requirements...</p>
                    </div>
                    
                    <div id="requirements-results" style="display: none;">
                        <h5>PHP Extensions</h5>
                        <ul class="requirements-list" id="php-requirements"></ul>
                        
                        <h5 class="mt-4">File Permissions</h5>
                        <ul class="requirements-list" id="permission-requirements"></ul>
                        
                        <div id="requirements-error" class="alert alert-danger" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Requirements not met!</strong> Please fix the issues above before proceeding.
                        </div>
                        
                        <div id="requirements-success" class="alert alert-success" style="display: none;">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>All requirements met!</strong> You can proceed with the installation.
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-primary" id="next-to-config" disabled>
                                Next Step <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Configuration -->
                <div id="configuration-step" class="step-content" style="display: none;">
                    <h3 class="mb-3">Application Configuration</h3>
                    <p class="text-muted mb-4">Configure your application settings and database connection.</p>
                    
                    <form id="install-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label">Application Name</label>
                                <input type="text" class="form-control" id="app_name" name="app_name" value="Customer Portal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="app_url" class="form-label">Application URL</label>
                                <input type="url" class="form-control" id="app_url" name="app_url" placeholder="https://yourdomain.com" required>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5>Database Configuration</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="db_host" class="form-label">Database Host</label>
                                <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="db_port" class="form-label">Database Port</label>
                                <input type="number" class="form-control" id="db_port" name="db_port" value="3306" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="db_database" class="form-label">Database Name</label>
                                <input type="text" class="form-control" id="db_database" name="db_database" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="db_username" class="form-label">Database Username</label>
                                <input type="text" class="form-control" id="db_username" name="db_username" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="db_password" class="form-label">Database Password</label>
                            <input type="password" class="form-control" id="db_password" name="db_password" required>
                        </div>
                        
                        <hr class="my-4">
                        <h5>Administrator Account</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="admin_name" class="form-label">Admin Name</label>
                                <input type="text" class="form-control" id="admin_name" name="admin_name" value="Administrator" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="admin_password" class="form-label">Admin Password</label>
                            <input type="password" class="form-control" id="admin_password" name="admin_password" minlength="8" required>
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" id="back-to-requirements">
                                <i class="bi bi-arrow-left me-2"></i>Back
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Install Application <i class="bi bi-download ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Step 3: Installation -->
                <div id="installation-step" class="step-content" style="display: none;">
                    <h3 class="mb-3">Installing Application</h3>
                    <p class="text-muted mb-4">Please wait while we set up your application...</p>
                    
                    <div id="installation-progress" class="text-center">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Installing...</span>
                        </div>
                        <p id="installation-status">Initializing installation...</p>
                    </div>
                    
                    <div id="installation-success" class="alert alert-success" style="display: none;">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Installation completed successfully!</strong>
                        <p class="mb-0 mt-2">You can now access your application.</p>
                    </div>
                    
                    <div id="installation-error" class="alert alert-danger" style="display: none;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Installation failed!</strong>
                        <p class="mb-0 mt-2" id="error-message"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentStep = 1;
        
        // Check requirements on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkRequirements();
        });
        
        function checkRequirements() {
            fetch('/install/requirements')
                .then(response => response.json())
                .then(data => {
                    displayRequirements(data);
                })
                .catch(error => {
                    console.error('Error checking requirements:', error);
                    document.getElementById('requirements-loading').innerHTML = 
                        '<div class="alert alert-danger">Error checking requirements. Please refresh the page.</div>';
                });
        }
        
        function displayRequirements(data) {
            document.getElementById('requirements-loading').style.display = 'none';
            document.getElementById('requirements-results').style.display = 'block';
            
            // Display PHP requirements
            const phpRequirements = document.getElementById('php-requirements');
            Object.entries(data.requirements).forEach(([requirement, status]) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <i class="bi bi-${status ? 'check-circle-fill success' : 'x-circle-fill error'} requirement-icon"></i>
                    <span>${requirement}</span>
                `;
                phpRequirements.appendChild(li);
            });
            
            // Display permission requirements
            const permissionRequirements = document.getElementById('permission-requirements');
            Object.entries(data.writable_paths).forEach(([path, status]) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <i class="bi bi-${status ? 'check-circle-fill success' : 'x-circle-fill error'} requirement-icon"></i>
                    <span>${path} (writable)</span>
                `;
                permissionRequirements.appendChild(li);
            });
            
            // Show success/error message
            if (data.all_requirements_met && data.all_paths_writable) {
                document.getElementById('requirements-success').style.display = 'block';
                document.getElementById('next-to-config').disabled = false;
            } else {
                document.getElementById('requirements-error').style.display = 'block';
            }
        }
        
        // Step navigation
        document.getElementById('next-to-config').addEventListener('click', function() {
            showStep(2);
        });
        
        document.getElementById('back-to-requirements').addEventListener('click', function() {
            showStep(1);
        });
        
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(el => el.style.display = 'none');
            
            // Show current step
            if (step === 1) {
                document.getElementById('requirements-step').style.display = 'block';
            } else if (step === 2) {
                document.getElementById('configuration-step').style.display = 'block';
            } else if (step === 3) {
                document.getElementById('installation-step').style.display = 'block';
            }
            
            // Update step indicators
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active', 'completed'));
            for (let i = 1; i <= step; i++) {
                const stepEl = document.getElementById(`step-${i}`);
                if (i < step) {
                    stepEl.classList.add('completed');
                } else {
                    stepEl.classList.add('active');
                }
            }
            
            currentStep = step;
        }
        
        // Form submission
        document.getElementById('install-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            showStep(3);
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            fetch('/install', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('installation-progress').style.display = 'none';
                    document.getElementById('installation-success').style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 3000);
                } else {
                    document.getElementById('installation-progress').style.display = 'none';
                    document.getElementById('installation-error').style.display = 'block';
                    document.getElementById('error-message').textContent = data.message;
                }
            })
            .catch(error => {
                document.getElementById('installation-progress').style.display = 'none';
                document.getElementById('installation-error').style.display = 'block';
                document.getElementById('error-message').textContent = 'An unexpected error occurred during installation.';
            });
        });
    </script>
</body>
</html>
