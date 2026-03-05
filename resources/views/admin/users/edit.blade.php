@extends('admin.layouts.app')

@push('styles_top')


@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('/admin/main.edit') }} {{ trans('admin/main.user') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl('/all-users') }}">{{ trans('admin/main.users') }}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('/admin/main.edit') }}</div>
            </div>
        </div>

        @if(!empty(session()->has('msg')))
            <div class="alert alert-success my-25">
                {{ session()->get('msg') }}
            </div>
        @endif


        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row mb-0">
                        <div class="col-md-3">
                            <div class="card mb-3">
                                <div class="card-body text-center position-relative">
                                    <div class="position-absolute" style="top: 15px; right: 15px;">

                                        <div class="btn-group dropdown table-actions position-relative">
                                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" style="outline: none !important; box-shadow: none !important;">
                                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                                            </button>


                                            <div class="dropdown-menu dropdown-menu-right">

                                                @can('admin_users_impersonate')
                                                    <a href="{{ getAdminPanelUrl() }}/users/{{ $user->id }}/impersonate" target="_blank" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                        <x-iconsax-lin-login class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                        <span class="text-gray-500 font-14">{{ trans('admin/main.login') }}</span>
                                                    </a>
                                                @endcan


                                                <a href="{{ $user->getProfileUrl() }}" target="_blank" class="dropdown-item d-flex align-items-center py-3 px-0 gap-4">
                                                    <x-iconsax-lin-user-square class="icons text-gray-500 mr-2" width="18px" height="18px"/>
                                                    <span class="text-gray-500 font-14">{{ trans('public.profile') }}</span>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="avatar-container mb-3">
                                        <img src="{{ $user->getAvatar() }}" class="img-fluid rounded-circle" width="120" height="120" alt="{{ $user->full_name }}" style="border: 3px solid #f1f1f1; padding: 3px;">
                                    </div>

                                    <h4 class="mt-2">{{ $user->full_name }}</h4>

                                    @if($user->isTeacher() || $user->isOrganization() || $user->isAdmin())
                                        <div class="d-inline-flex align-items-center mt-1">
                                            @php
                                                $userRates = $user->rates();
                                            @endphp

                                            @include('admin.webinars.includes.rate',['rate' => $userRates, 'className' => 'mt-2', 'showRateStars' => true])
                                        </div>
                                    @endif

                                    <p class="text-muted">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="row mt-0 mb-0">
                                <div class="col-4">
                                    <div class="card mb-3">
                                        <div class="card-body pt-10 pb-10 text-center">
                                            <x-iconsax-bul-dollar-square class="icons mb-1 text-gray-500" width="32px" height="32px"/>
                                            <h6 class="font-14 text-muted">{{ trans('financial.total_income') }}</h6>
                                            <p class="mt-2 font-20">{{ handlePrice($user->getIncome()) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($user) and ($user->isTeacher() || $user->isOrganization() || $user->isAdmin()))
                                    <div class="col-4 mb-3">
                                        <div class="card mb-0">
                                            <div class="card-body pt-10 pb-10 text-center">
                                                <x-iconsax-bul-shopping-cart class="icons mb-1 text-gray-500" width="32px" height="32px"/>
                                                <h6 class="font-14 text-muted">Sales</h6>
                                                <p class="mt-2 font-20">{{ $user->salesCount() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($user) and ($user->isUser()))
                                    <div class="col-4 mb-3">
                                        <div class="card mb-0">
                                            <div class="card-body pt-10 pb-10 text-center">
                                                <x-iconsax-bul-video-play class="icons mb-1 text-gray-500" width="32px" height="32px"/>
                                                <h6 class="font-14 text-muted">{{ trans('update.enrolled_courses') }}</h6>
                                                <p class="mt-2 font-20">{{ App\Models\Sale::where('buyer_id', $user->id)->whereNotNull('webinar_id')->whereNull('refund_at')->count() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-4 mb-0">
                                    <div class="card mb-0">
                                        <div class="card-body pt-10 pb-10 text-center">
                                            <x-iconsax-bul-calendar class="icons mb-1 text-gray-500" width="32px" height="32px"/>
                                            <h6 class="font-14 text-muted">{{ trans('panel.meetings') }}</h6>
                                            <p class="mt-2 font-20">{{ $user->reserveMeetings()->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-0">
                                <div class="card-header">
                                    <h4 class="text-dark">{{ trans('update.user_statistics') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        @if(!empty($user) and ($user->isOrganization() or $user->isTeacher()))
                                            <div class="col-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <x-iconsax-bul-video-play class="icons mb-3 mr-3 text-gray-500" width="26px" height="26px"/>
                                                    <div class="flex-grow-1">
                                                        <span class="font-14 text-gray-500">{{ trans('update.enrolled_courses') }}</span>
                                                        <span class="font-16 font-weight-bold d-block">{{ App\Models\Sale::where('buyer_id', $user->id)->whereNotNull('webinar_id')->whereNull('refund_at')->count() }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <x-iconsax-bul-video-tick class="icons mb-3 mr-3 text-gray-500" width="26px" height="26px"/>
                                                    <div class="flex-grow-1">
                                                        <span class="font-14 text-gray-500">{{ trans('update.published_courses') }}</span>
                                                        <span class="font-16 font-weight-bold d-block">{{ ($user->isTeacher() || $user->isOrganization()) ? $user->getActiveWebinars(true) : 0 }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif

                                        <div class="col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <x-iconsax-bul-shopping-cart class="icons mb-3 mr-3 text-gray-500" width="26px" height="26px"/>
                                                <div class="flex-grow-1">
                                                    <span class="font-14 text-gray-500">{{ trans('update.total_purchases') }}</span>
                                                    @php
                                                        $totalPurchases = App\Models\Sale::where('buyer_id', $user->id)
                                                            ->whereNull('refund_at')
                                                            ->sum('total_amount');
                                                    @endphp
                                                    <span class="font-16 font-weight-bold d-block">{{ handlePrice($totalPurchases) }}</span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <x-iconsax-bul-sms class="icons mb-3 mr-3 text-gray-500" width="26px" height="26px"/>
                                                <div class="flex-grow-1">
                                                    <span class="font-14 text-gray-500">{{ trans('panel.support_tickets') }}</span>
                                                    <span class="font-16 font-weight-bold d-block">{{ $user->supports()->count() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <x-iconsax-bul-archive-book class="icons mb-3 mr-3 text-gray-500" width="26px" height="26px"/>
                                                <div class="flex-grow-1">
                                                    <span class="font-14 text-gray-500">{{ trans('panel.certificates') }}</span>
                                                    <span class="font-16 font-weight-bold d-block">{{ $user->certificates()->count() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">

                                    <ul class="nav nav-pills" id="myTab3" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link @if(empty($becomeInstructor) and empty(request()->get('tab'))) active @endif" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">{{ trans('admin/main.main_general') }}</a>
                                        </li>

                                        @if(!empty($formFieldsHtml))
                                            <li class="nav-item">
                                                <a class="nav-link" id="extra_information-tab" data-toggle="tab" href="#extra_information" role="tab" aria-controls="extra_information" aria-selected="true">{{ trans('site.extra_information') }}</a>
                                            </li>
                                        @endif

                                        <li class="nav-item">
                                            <a class="nav-link" id="images-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="true">{{ trans('auth.images') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial" role="tab" aria-controls="financial" aria-selected="true">{{ trans('admin/main.financial') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="occupations-tab" data-toggle="tab" href="#occupations" role="tab" aria-controls="occupations" aria-selected="true">{{ trans('site.occupations') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="badges-tab" data-toggle="tab" href="#badges" role="tab" aria-controls="badges" aria-selected="true">{{ trans('admin/main.badges') }}</a>
                                        </li>

                                        @if(!empty($user) and ($user->isOrganization() or $user->isTeacher()))
                                            @can('admin_update_user_registration_package')
                                                <li class="nav-item">
                                                    <a class="nav-link" id="registrationPackage-tab" data-toggle="tab" href="#registrationPackage" role="tab" aria-controls="registrationPackage" aria-selected="true">{{ trans('update.registration_package') }}</a>
                                                </li>
                                            @endcan
                                        @endif

                                        @if(!empty($user) and ($user->isOrganization() or $user->isTeacher()))
                                            @can('admin_update_user_meeting_settings')
                                                <li class="nav-item">
                                                    <a class="nav-link" id="meetingSettings-tab" data-toggle="tab" href="#meetingSettings" role="tab" aria-controls="meetingSettings" aria-selected="true">{{ trans('update.meeting_settings') }}</a>
                                                </li>
                                            @endcan
                                        @endif

                                        @if(!empty($becomeInstructor))
                                            <li class="nav-item">
                                                <a class="nav-link @if(!empty($becomeInstructor)) active @endif" id="become_instructor-tab" data-toggle="tab" href="#become_instructor" role="tab" aria-controls="become_instructor" aria-selected="true">{{ trans('admin/main.become_instructor_info') }}</a>
                                            </li>
                                        @endif


                                        <li class="nav-item">
                                            <a class="nav-link" id="purchased_courses-tab" data-toggle="tab" href="#purchased_courses" role="tab" aria-controls="purchased_courses" aria-selected="true">{{ trans('update.purchased_courses') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="purchased_bundles-tab" data-toggle="tab" href="#purchased_bundles" role="tab" aria-controls="purchased_bundles" aria-selected="true">{{ trans('update.purchased_bundles') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="purchased_products-tab" data-toggle="tab" href="#purchased_products" role="tab" aria-controls="purchased_products" aria-selected="true">{{ trans('update.purchased_products') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="topics-tab" data-toggle="tab" href="#topics" role="tab" aria-controls="topics" aria-selected="true">{{ trans('update.forum_topics') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="support_tickets-tab" data-toggle="tab" href="#support_tickets" role="tab" aria-controls="support_tickets" aria-selected="true">{{ trans('admin/main.tickets') }}</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link {{ (request()->get('tab') == "loginHistory") ? 'active' : '' }}" href="{{ getAdminPanelUrl("/users/{$user->id}/edit?tab=loginHistory") }}" role="tab" aria-controls="loginHistory" aria-selected="true">{{ trans('update.login_history') }}</a>
                                        </li>

                                    </ul>

                                    <div class="tab-content" id="myTabContent2">

                                        @include('admin.users.editTabs.general')

                                        @if(!empty($formFieldsHtml))
                                            @include('admin.users.editTabs.extra_information')
                                        @endif

                                        @include('admin.users.editTabs.images')

                                        @include('admin.users.editTabs.financial')

                                        @include('admin.users.editTabs.occupations')

                                        @include('admin.users.editTabs.badges')

                                        @if(!empty($user) and ($user->isOrganization() or $user->isTeacher()))
                                            @can('admin_update_user_registration_package')
                                                @include('admin.users.editTabs.registration_package')
                                            @endcan
                                        @endif

                                        @if(!empty($user) and ($user->isOrganization() or $user->isTeacher()))
                                            @can('admin_update_user_meeting_settings')
                                                @include('admin.users.editTabs.meeting_settings')
                                            @endcan
                                        @endif

                                        @if(!empty($becomeInstructor))
                                            @include('admin.users.editTabs.become_instructor')
                                        @endif

                                        @include('admin.users.editTabs.purchased_courses')

                                        @include('admin.users.editTabs.purchased_bundles')

                                        @include('admin.users.editTabs.purchased_products')

                                        @include('admin.users.editTabs.topics')

                                        @include('admin.users.editTabs.support_tickets')

                                        @include('admin.users.editTabs.login_history')

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')



    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    </script>

    <script src="/assets/admin/js/parts/webinar_students.min.js"></script>
    <script src="/assets/admin/js/parts/user_edit.min.js"></script>
@endpush
