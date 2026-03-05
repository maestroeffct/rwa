@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.76563rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Licenses</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Licenses</a></div>
                <div class="breadcrumb-item">List</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th>Product</th>
                                        <th>Purchase Code</th>
                                        <th>License Type</th>
                                        <th>Status</th>
                                        <th width="120">Action</th>
                                    </tr>

                                    <tr>
                                        <td>Rocket LMS</td>
                                        <td>{{ !empty($mainLicense['code']) ? substr($mainLicense['code'], 0, 32).'****' : '-' }}</td>
                                        <td>{{ !empty($mainLicense['license_type']) ? $mainLicense['license_type'] : '-' }}</td>
                                        <td>
                                            @if($mainLicense['status'] === true)
                                                <div class="badge-status text-success bg-success-30">Valid</div>
                                            @elseif($mainLicense['status'] === false)
                                                <div class="badge-status text-danger bg-danger-30">Invalid</div>
                                                @if(!empty($mainLicense['message']))
                                                    <div class="mt-1 text-danger font-12">{{ $mainLicense['message'] }}</div>
                                                @endif
                                            @else
                                                <div class="badge-status text-warning bg-warning-30">Not Submitted</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                </button>
                                                
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('purchase.code.show') }}" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-edit class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Edit Purchase Code</span>
                                                    </a>
                                                    
                                                    <a href="https://codecanyon.net/item/rocket-lms-learning-management-academy-script/33120735" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-global class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Product Page</span>
                                                    </a>
                                                    
                                                    @if(empty($mainLicense['code']))
                                                        <a href="{{ route('purchase.code.show') }}" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                                            <x-iconsax-lin-key class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Submit Purchase Code</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Rocket LMS Plugins Bundle</td>
                                        <td>{{ !empty($pluginBundleLicense['code']) ? substr($pluginBundleLicense['code'], 0, 32).'****' : '-' }}</td>
                                        <td>{{ !empty($pluginBundleLicense['license_type']) ? $pluginBundleLicense['license_type'] : '-' }}</td>
                                        <td>
                                            @if($pluginBundleLicense['status'] === true)
                                                <div class="badge-status text-success bg-success-30">Valid</div>
                                            @elseif($pluginBundleLicense['status'] === false)
                                                <div class="badge-status text-danger bg-danger-30">Invalid</div>
                                                @if(!empty($pluginBundleLicense['message']))
                                                    <div class="mt-1 text-danger font-12">{{ $pluginBundleLicense['message'] }}</div>
                                                @endif
                                            @else
                                                <div class="badge-status text-warning bg-warning-30">Not Submitted</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                </button>
                                                
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.plugin.license') }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-edit class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Edit Purchase Code</span>
                                                    </a>
                                                    
                                                    <a href="https://codecanyon.net/item/universal-plugins-bundle-for-rocket-lms/33297004" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-global class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Product Page</span>
                                                    </a>
                                                    
                                                    @if(empty($pluginBundleLicense['code']))
                                                        <a href="{{ route('admin.plugin.license') }}" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                                            <x-iconsax-lin-key class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Submit Purchase Code</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Rocket LMS Theme and Landing Builder</td>
                                        <td>{{ !empty($themeBuilderLicense['code']) ? substr($themeBuilderLicense['code'], 0, 32).'****' : '-' }}</td>
                                        <td>{{ !empty($themeBuilderLicense['license_type']) ? $themeBuilderLicense['license_type'] : '-' }}</td>
                                        <td>
                                            @if($themeBuilderLicense['status'] === true)
                                                <div class="badge-status text-success bg-success-30">Valid</div>
                                            @elseif($themeBuilderLicense['status'] === false)
                                                <div class="badge-status text-danger bg-danger-30">Invalid</div>
                                                @if(!empty($themeBuilderLicense['message']))
                                                    <div class="mt-1 text-danger font-12">{{ $themeBuilderLicense['message'] }}</div>
                                                @endif
                                            @else
                                                <div class="badge-status text-warning bg-warning-30">Not Submitted</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                </button>
                                                
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.theme-builder.license') }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-edit class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Edit Purchase Code</span>
                                                    </a>
                                                    
                                                    <a href="https://codecanyon.net/item/rocket-lms-theme-and-landing-page-builder/59174209" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-global class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Product Page</span>
                                                    </a>
                                                    
                                                    @if(empty($themeBuilderLicense['code']))
                                                        <a href="{{ route('admin.theme-builder.license') }}" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                                            <x-iconsax-lin-key class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Submit Purchase Code</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Rocket LMS Mobile App</td>
                                        <td>{{ !empty($mobileAppLicense['code']) ? substr($mobileAppLicense['code'], 0, 32).'****' : '-' }}</td>
                                        <td>{{ !empty($mobileAppLicense['license_type']) ? $mobileAppLicense['license_type'] : '-' }}</td>
                                        <td>
                                            @if($mobileAppLicense['status'] === true)
                                                <div class="badge-status text-success bg-success-30">Valid</div>
                                            @elseif($mobileAppLicense['status'] === false)
                                                <div class="badge-status text-danger bg-danger-30">Invalid</div>
                                                @if(!empty($mobileAppLicense['message']))
                                                    <div class="mt-1 text-danger font-12">{{ $mobileAppLicense['message'] }}</div>
                                                @endif
                                            @else
                                                <div class="badge-status text-warning bg-warning-30">Not Submitted</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group dropdown table-actions position-relative">
                                                <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                    <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                                </button>
                                                
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.mobile_app.license') }}" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                    <x-iconsax-lin-edit class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Edit Purchase Code</span>
                                                    </a>
                                                    
                                                    <a href="https://codecanyon.net/item/rocket-lms-mobile-app-learning-management-system-app/36329581" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-global class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">Product Page</span>
                                                    </a>
                                                    
                                                    @if(empty($mobileAppLicense['code']))
                                                        <a href="{{ route('admin.mobile_app.license') }}" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                                            <x-iconsax-lin-key class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                            <span class="text-gray-500 font-14">Submit Purchase Code</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
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