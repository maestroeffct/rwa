@extends("design_1.web.layouts.app", ['appFooter' => false, 'appHeader' => false, 'justMobileApp' => true])

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("system_status_pages") }}">
@endpush

@php
    $maintenanceSettings = getMaintenanceSettings();
    $endDate = !empty($maintenanceSettings['end_date']) ? $maintenanceSettings['end_date'] : null;

    $remainingTimes = null;

    if (!empty($endDate) and is_numeric($endDate)) {
        $remainingTimes = time2string($endDate -  time());
    }
@endphp

@section("content")
    <section class="container maintenance-page-container position-relative">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-lg-8">
                <div class="system-status-page-section position-relative">
                    <div class="system-status-page-section__mask"></div>

                    <div class="position-relative d-flex-center flex-column bg-white rounded-32 p-24 pt-64 p-lg-40 text-center z-index-2">

                        @if(!empty($maintenanceSettings['right_float_image']))
                            <div class="system-status-page-right-float-image">
                                <img src="{{ $maintenanceSettings['right_float_image'] }}" alt="{{ trans('update.right_float_image') }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($maintenanceSettings['image']))
                            <div class="system-status-page-image">
                                <img src="{{ $maintenanceSettings['image'] }}" alt="{{ $maintenanceSettings['title'] ?? '' }}" class="img-cover">
                            </div>
                        @endif

                        @if(!empty($maintenanceSettings['title']))
                            <h1 class="font-16 font-weight-bold mt-16">{{ $maintenanceSettings['title'] }}</h1>
                        @endif

                        @if(!empty($maintenanceSettings['description']))
                            <p class="font-14 text-gray-500 mt-4">{!! nl2br($maintenanceSettings['description']) !!}</p>
                        @endif

                        @if(!empty($remainingTimes))
                            <div class="position-relative maintenance-count-down mt-20">
                                <div class="maintenance-count-down__mask"></div>

                                <div id="maintenanceCountDown" class="d-flex align-items-center time-counter-down position-relative bg-white rounded-16 border-gray-200 py-16 px-24 z-index-2"
                                     data-day="{{ $remainingTimes['day'] }}"
                                     data-hour="{{ $remainingTimes['hour'] }}"
                                     data-minute="{{ $remainingTimes['minute'] }}"
                                     data-second="{{ $remainingTimes['second'] }}">

                                    <div class="d-flex align-items-center flex-column mr-10">
                                        <span class="time-item days font-16 font-weight-bold"></span>
                                        <span class="font-12 mt-1 text-gray-400">{{ trans('public.day') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-column mr-10">
                                        <span class="time-item hours font-16 font-weight-bold"></span>
                                        <span class="font-12 mt-1 text-gray-400">{{ trans('update.hour') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-column mr-10">
                                        <span class="time-item minutes font-16 font-weight-bold"></span>
                                        <span class="font-12 mt-1 text-gray-400">{{ trans('public.min') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-column">
                                        <span class="time-item seconds font-16 font-weight-bold"></span>
                                        <span class="font-12 mt-1 text-gray-400">{{ trans('public.sec') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(!empty($maintenanceSettings['maintenance_button']) and !empty($maintenanceSettings['maintenance_button']['title']) and !empty($maintenanceSettings['maintenance_button']['link']))
                            <a href="{{ $maintenanceSettings['maintenance_button']['link'] }}" class="btn btn-primary btn-lg mt-24">{{ $maintenanceSettings['maintenance_button']['title'] }}</a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/js/parts/time-counter-down.min.js"></script>
    <script src="{{ getDesign1ScriptPath("maintenance") }}"></script>
@endpush
