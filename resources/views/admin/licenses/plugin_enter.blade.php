@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <style>
        .license-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .license-card .card-header {
            padding: 20px 25px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        .license-card .card-header h4 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .license-card .card-body {
            padding: 25px;
        }
        .custom-input {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e8eaed;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .custom-input:focus {
            background-color: #fff;
            border-color: #43d477;
        }
        .custom-btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
       
        .info-card {
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
        }
       
        .info-card-header h4 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        .info-card-body {
            padding: 25px;
            background-color: #fff;
        }
        .feature-item {
            display: flex;
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .feature-item:hover {
            transform: translateY(-3px);
        }
        .feature-item:last-child {
            margin-bottom: 0;
        }
        .feature-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            min-width: 50px;
            border-radius: 12px;
            margin-right: 15px;
        }
        .feature-content h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 5px;
            color: #343a40;
        }
        .feature-content p {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
            line-height: 1.6;
        }
        .alert-custom {
            border-radius: 12px;
            padding: 15px 20px;
            margin-top: 25px;
          
        }
        .alert-custom h5 {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 10px;
        }
  
        .purchase-link {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 25px;
            border-radius: 10px;
            background-color: #43d477;
            color: #fff;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .purchase-link:hover {
            background-color: #3ac569;
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
        }
        .purchase-link i {
            margin-right: 8px;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('admin.licenses') }}">Licenses</a></div>
                <div class="breadcrumb-item">Enter Plugins Bundle License</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-lg-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4>Enter a Valid Purchase Code</h4>
                        </div>
                        <div class="card-body">
                            <form class="mb-0" action="{{ route('admin.plugin.license.store') }}" method="post">
                                @csrf
                                
                                @if(session('plugin_bundle_error'))
                                    <div class="alert-custom rounded-16 text-danger bg-danger-20 mt-0 mb-3  d-flex align-items-center" role="alert">
                                       <x-iconsax-bul-info-circle class="icons text-danger mr-4" width="24px" height="24px"/>
                                        {{ session('plugin_bundle_error') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="input-label font-14 font-weight-500">Purchase Code</label>
                                    <input type="text" name="purchase_code" id="purchase_code" 
                                        value="{{ old('purchase_code', $purchaseCode) }}" 
                                        class="form-control custom-input {{ session('plugin_error_type') ? 'is-invalid' : '' }}"
                                        placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX"
                                        pattern=".{36,36}" required>
                                    <div class="text-muted font-12 mt-2">Please enter your Rocket LMS Plugins Bundle purchase code. This code has 32 characters.</div>
                                </div>

            <div class="d-flex align-items-center mt-4 text-end justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <x-iconsax-bul-key class="icons text-white mr-4" width="18px" height="18px"/>
                                        <span class="text-white">Validate</span>
                                    </button>
                                  
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-lg-7 mt-4 mt-lg-0">
                    <div class="card rounded-24">
                         <div class="card-header d-flex align-items-center justify-content-between">
                            <h4>Rocket LMS Plugins Bundle Information</h4>
                        </div>
                         <div class="info-card-body rounded-24">
                            <p>The Plugins Bundle is a separate Product that allows you to add additional features to your website.</p>
                            
                            <div class="mt-4">

                                 <div class="feature-item rounded-16 border-gray-300">
                                    <div class="feature-icon bg-primary-30 text-primary">
                                      <x-iconsax-bul-bucket class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                    <div class="feature-content">
                                       <h5>What is Plugins Bundle?</h5>
                                        <p class="text-gray-500">The Rocket LMS Plugins Bundle extends the capabilities of your main Rocket LMS installation with additional features and integrations.</p>
                                    </div>
                                </div>

                                 <div class="feature-item rounded-16 border-gray-300">
                                    <div class="feature-icon bg-primary-30 text-primary">
                                      <x-iconsax-bul-key class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                    <div class="feature-content">
                                        <h5>Separate License</h5>
                                        <p class="text-gray-500">Plugins Bundle requires a separate purchase code from your main Rocket LMS license. Make sure you're entering the correct purchase code.</p>
                                    </div>
                                </div>
                                

                                 <div class="feature-item rounded-16 border-gray-300">
                                    <div class="feature-icon bg-primary-30 text-primary">
                                     <x-iconsax-bul-message-question class="icons text-primary" width="24px" height="24px"/>
                                    </div>
                                    <div class="feature-content">
                                        <h5>Need Help?</h5>
                                        <p class="text-gray-500">For support and further guidance, you can connect with our technical experts through our CRM. <a href="https://crm.rocket-soft.org/index.php/tickets" target="_blank">Submit a ticket</a></p>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="alert-custom bg-warning-20">
                                <h5 class="text-warning"> <x-iconsax-bul-danger class="icons text-warning" width="32px" height="32px"/> Important Note:</h5>
                                <p class="text-warning">Each purchase code is valid for a single domain only. If you want to use Plugins Bundle on multiple domains or installations, you must obtain a separate license for each one.</p>
                            </div>

                            <div class=" align-items-center text-center mt-24">
                            <a href="https://codecanyon.net/item/universal-plugins-bundle-for-rocket-lms/33297004" target="_blank" class="btn w-100 rounded-16 btn-primary">
                                        <x-iconsax-bul-bag class="icons text-white" width="18px" height="18px"/>
                                        <span class="ml-4 font-14">Purchase Rocket LMS Plugins Bundle</span>
                                    </a>
                            </div>
                                                        
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
@endpush 