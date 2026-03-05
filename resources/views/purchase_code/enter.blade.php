<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Enter Purchase Code')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --secondary-color: #6c757d;
            --danger-color: #ef476f;
            --warning-color: #ffd166;
            --success-color: #06d6a0;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --border-radius: 12px;
            --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            background-image: url('{{ asset('assets/design_1/img/background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .page-container {
            max-width: 1200px;
            width: 100%;
            padding: 2rem 0;
            position: relative;
            z-index: 1;
        }
        
        /* Simplified Layered Card Effect using a single element */
        .card-stack {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
            padding-bottom: 8px; /* Reduced space for single layer */
        }
        
        .card-layer {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7); /* Very subtle light color */
            border-radius: var(--border-radius);
            z-index: 0;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }
        
        .card {
            position: relative;
            z-index: 3;
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); /* Lighter shadow */
            overflow: hidden;
            background-color: white;
            max-width: 100%;
            margin: 0;
        }
        
        .card-body {
            padding: 2rem;
            text-align: center;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background-color: #4361ee;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .logo-icon i {
            color: white;
            font-size: 1.5rem;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
        }
        
        .card-subtitle {
            font-size: 0.9rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
            font-size: 0.85rem;
            text-align: left;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            border: 1px solid rgba(0,0,0,0.1);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            margin-bottom: 0;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }
        
        .btn {
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .alert {
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background-color: rgba(239, 71, 111, 0.1);
            border-color: transparent;
            color: var(--danger-color);
        }
        
        .notice-box {
            background-color: rgba(0,0,0,0.02);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,0,0,0.05);
            text-align: left;
        }
        
        .notice-box h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .notice-box p {
            font-size: 0.9rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .notice-box p:last-child {
            margin-bottom: 0;
        }
        
        .domain-notice h5 {
            color: var(--danger-color);
        }
        
        .crm-notice h5 {
            color: var(--warning-color);
        }
        
        .login-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--secondary-color);
            font-size: 0.9rem;
            text-decoration: none;
        }
        
        .login-link:hover {
            color: var(--primary-color);
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            padding: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-left {
            display: flex;
            align-items: center;
        }
        
        .help-icon {
            width: 32px;
            height: 32px;
            background-color: #f0f0f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }
        
        .help-text {
            font-size: 0.85rem;
        }
        
        .help-text p {
            margin: 0;
            line-height: 1.2;
        }
        
        .help-text p:first-child {
            font-weight: 600;
            color: var(--dark-bg);
        }
        
        .help-text p:last-child {
            color: var(--secondary-color);
            font-size: 0.8rem;
        }
        
        .footer-right {
            display: flex;
            gap: 1rem;
        }

        .rounded-24 {
           border-radius: 24px;
        }
        
        .footer-link {
            color: var(--secondary-color);
            font-size: 0.85rem;
            text-decoration: none;
        }
        
        .footer-link:hover {
            color: var(--primary-color);
        }
        
        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 9999;
            width: 100%;
            max-width: 350px;
        }
        
        .toast-notification {
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 15px;
            color: white;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            background-color: #e4534d;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        
        .toast-notification.error {
            background-color: #e4534d;
        }
        
        .toast-notification.success {
            background-color: #2ecc71;
        }
        
        .toast-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            position: relative;
        }
        
        .toast-icon::before {
            content: '';
            position: absolute;
            width: 30px;
            height: 30px;
            background-color: white;
            border-radius: 50%;
        }
        
        .toast-icon i {
            position: relative;
            z-index: 1;
            color: #f8a5a3;
            font-size: 1rem;
        }
        
        .toast-content {
            flex-grow: 1;
        }
        
        .toast-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .toast-message {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .toast-close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: white;
            background: none;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        
        .toast-close:hover {
            opacity: 1;
        }
        
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.5);
            width: 100%;
        }
        
        .toast-divider {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        @media (max-width: 768px) {
            .card {
                margin: 0;
            }
            
            .card-stack {
                margin: 0 1rem;
                padding-bottom: 15px;
            }
            
            .card-layer {
                top: 7px;
                left: 7px;
                right: 7px;
            }
            
            .footer {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .footer-right {
                width: 100%;
                justify-content: space-around;
            }
        }
        
        .invalid-feedback, .text-danger {
            font-size: 0.85rem;
            margin-top: 0.25rem !important;
            display: block;
        }
        
        .d-grid {
            margin-top: 1.5rem;
        }
    </style>
    @yield('additional_styles')
</head>
<body>
    <!-- Define session keys at the top -->
    @php
        $errorSessionKey = 'purchase_code_error';
        $errorTypeSessionKey = 'error_type';
        
        if (trim($__env->yieldContent('error_session_key'))) {
            $errorSessionKey = trim($__env->yieldContent('error_session_key'));
        }
        
        if (trim($__env->yieldContent('error_type_session_key'))) {
            $errorTypeSessionKey = trim($__env->yieldContent('error_type_session_key'));
        }
    @endphp

    <!-- Toast Container -->
    @if(session($errorSessionKey))
    <div class="toast-container">
        <div class="toast-notification error">
            <div class="toast-icon">
                <i class="fas fa-exclamation"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">Error</div>
                <div class="toast-message">{{ session($errorSessionKey) }}</div>
            </div>
            <button class="toast-close">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-divider"></div>
            <div class="toast-progress"></div>
        </div>
    </div>
    @endif

    <div class="page-container">
        <!-- Layered Card Effect with a single layer -->
        <div class="card-stack">
            <div class="card-layer"></div>
            <div class="card rounded-24">
                <div class="card-body">
                        <div class="mb-4">
                            <img src="{{ asset('assets/design_1/img/logo.svg') }}" alt="Rocket LMS" class="img-fluid">
                        </div>
                    
                    <h1 class="card-title">@yield('heading', 'License Verification')</h1>
                    <p class="card-subtitle">Enter your Rocket LMS purchase code to verify your license</p>
                    
                    @if(session($errorTypeSessionKey) == 'domain_mismatch' || (session($errorSessionKey) && str_contains(session($errorSessionKey), 'registered for')))
                        <div class="notice-box domain-notice">
                            <h5><i class="fas fa-exclamation-circle me-2"></i> Domain Registration Issue</h5>
                            <p>Your purchase code appears to be registered for another domain. Please update your license domain address.</p>
                            <p class="mb-0">Visit our <a href="https://crm.rocket-soft.org/index.php/licenses" target="_blank">CRM Portal</a> to update domain for your license.</p>
                        </div>
                    @endif
                    
                    @if(session($errorTypeSessionKey) == 'product_mismatch' || (session($errorSessionKey) && str_contains(session($errorSessionKey), 'different product')))
                        <div class="notice-box domain-notice">
                            <h5><i class="fas fa-exclamation-circle me-2"></i> Product Mismatch Issue</h5>
                            <p>Your purchase code appears to be for a different product. Please make sure you are using the correct purchase code.</p>
                            <p class="mb-0">If you believe this is an error, visit our <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank">CRM Portal</a> to submit a support request.</p>
                        </div>
                    @endif
                    
                    @if(session($errorTypeSessionKey) == 'no_code' || (session($errorSessionKey) && str_contains(session($errorSessionKey), 'not registered') || str_contains(session($errorSessionKey), 'CRM')))
                        <div class="notice-box crm-notice">
                            <h5><i class="fas fa-info-circle me-2"></i> CRM Registration Required</h5>
                            <p>To register your purchase code, please submit your license in our CRM.</p>
                            <p class="mb-0">Visit our <a href="https://crm.rocket-soft.org/index.php/licenses" target="_blank">CRM Portal</a> to submit your license.</p>
                        </div>
                    @endif
                    
                    <form action="@yield('form_action', route('purchase.code.store'))" method="POST">
                        @csrf
                        @yield('additional_form_fields')
                        
                        <div>
                            <label for="purchase_code" class="form-label">@yield('form_label', 'Purchase Code')</label>
                            <input 
                                type="text" 
                                class="form-control @error('purchase_code') is-invalid @enderror" 
                                id="purchase_code" 
                                name="purchase_code" 
                                value="{{ old('purchase_code', $purchaseCode) }}"
                                placeholder="Enter your Envato purchase code"
                                required
                            >
                            @error('purchase_code')
                                <div class="invalid-feedback text-start">
                                    {{ $message }}
                                </div>
                            @else
                                <div class="text-danger text-start mt-1" id="character-error" style="display: none; margin-top: 0.25rem !important;">
                                    The purchase code must be 36 characters.
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                @yield('submit_button_text', 'Verify License')
                            </button>
                        </div>
                    </form>
                    
                    <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank" class="login-link">Need help?</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <div class="footer-left">
            <div class="help-icon">
                <i class="fas fa-question text-muted"></i>
            </div>
            <div class="help-text">
                <p>Have a question?</p>
                <p>Contact our team in <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank">CRM</a> to get resolved</p>
            </div>
        </div>
        
        <div class="footer-right">
            <a href="https://codecanyon.net/item/rocket-lms-learning-management-academy-script/33120735" target="_blank" class="footer-link">Product Page</a>
            <a href="https://codecanyon.net/user/rocketsoft" target="_blank" class="footer-link">About</a>
            <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank" class="footer-link">Contact us</a>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Notification JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Purchase code validation
            const purchaseCodeInput = document.getElementById('purchase_code');
            const characterError = document.getElementById('character-error');
            
            if (purchaseCodeInput) {
                purchaseCodeInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    if (value.length > 0 && value.length !== 36) {
                        characterError.style.display = 'block';
                    } else {
                        characterError.style.display = 'none';
                    }
                });
                
                // Check on page load
                const value = purchaseCodeInput.value.trim();
                if (value.length > 0 && value.length !== 36) {
                    characterError.style.display = 'block';
                }
            }
            
            // Add animation for toast appearance
            const toast = document.querySelector('.toast-notification');
            if (toast) {
                // Initial state - hidden
                toast.style.opacity = '0';
                
                // Trigger animation after 1 second delay (fade in)
                setTimeout(function() {
                    toast.style.transition = 'opacity 0.5s ease';
                    toast.style.opacity = '1';
                }, 1000);
                
                // Auto-hide toast after 15 seconds
                setTimeout(function() {
                    toast.style.opacity = '0';
                    // Remove transform for exit animation (fade only)
                    setTimeout(function() {
                        toast.remove();
                    }, 500);
                }, 16000); // 1 second delay + 15 seconds display time
            }
            
            // Close toast on button click
            const closeBtn = document.querySelector('.toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    const toast = document.querySelector('.toast-notification');
                    toast.style.opacity = '0';
                    // Remove transform for exit animation (fade only)
                    setTimeout(function() {
                        toast.remove();
                    }, 500);
                });
            }
            
            // Animate progress bar
            const progressBar = document.querySelector('.toast-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                
                // Delay progress bar start to match the toast appearance
                setTimeout(function() {
                    progressBar.style.transition = 'width 15s linear';
                    progressBar.style.width = '0';
                }, 1000);
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html> 