@extends('purchase_code.enter')

@section('title', 'Enter Rocket LMS Purchase Code')

@section('heading', 'Rocket LMS License Verification')

@section('form_action', route('purchase.code.store'))

@section('form_label', 'Enter Rocket LMS Purchase Code')

@section('form_help_text', 'Get your Rocket LMS purchase code from Codecanyon panel.')

@section('submit_button_text', 'Verify Rocket LMS Purchase Code')

@section('error_session_key', 'purchase_code_error')
@section('error_type_session_key', 'error_type')

@section('hint_cards')
    <div class="hint-card">
        <div class="hint-card-icon">
            <i class="fas fa-rocket fa-lg"></i>
        </div>
        <h4>Rocket LMS Platform</h4>
        <p>Rocket LMS is a comprehensive learning management system, designed to create and manage online courses, webinars, and educational content.</p>
    </div>

    <div class="hint-card">
        <div class="hint-card-icon">
            <i class="fas fa-key fa-lg"></i>
        </div>
        <h4>Main License</h4>
        <p>This is the main Rocket LMS license. For Plugins Bundle or Theme Builder, use their separate license pages in the admin panel.</p>
    </div>
    
    <div class="hint-card">
        <div class="hint-card-icon">
            <i class="fas fa-question-circle fa-lg"></i>
        </div>
        <h4>Need Help?</h4>
        <p>For support and further guidance, you can connect with our technical experts through our CRM. <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank">Submit a ticket.</a></p>
    </div>
@endsection 