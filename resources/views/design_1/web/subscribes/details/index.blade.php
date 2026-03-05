@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("subscribe_details") }}">
@endpush

@section('content')
    <div class="container mt-56 mb-120">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32 font-weight-bold">{{ trans('update.subscription_package_details') }}</h1>
            <p class="mt-8 font-16 text-gray-500">{{ trans('update.view_all_content_you_can_access_through_this_subscription_plan') }}</p>
        </div>

        {{-- Plan Info --}}
        @include('design_1.web.subscribes.details.plan_info')

        {{-- Recommended Courses --}}
        @if(!empty($recommendedCourses) and $recommendedCourses->isNotEmpty())
            <div class="mt-40">
                <h2 class="font-16">{{ trans('update.recommended_courses') }}</h2>
                <p class="mt-4 text-gray-500">{{ trans('update.subscribe_to_the_following_content_or_explore_more_based_on_your_plan_access') }}</p>
            </div>

            <div class="row">
                @include('design_1.web.courses.components.cards.grids.index',['courses' => $recommendedCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-24"])
            </div>
        @endif

        {{-- Recommended Bundles --}}
        @if(!empty($recommendedBundles) and $recommendedBundles->isNotEmpty())
            <div class="mt-40">
                <h2 class="font-16">{{ trans('update.recommended_bundles') }}</h2>
                <p class="mt-4 text-gray-500">{{ trans('update.subscribe_to_the_following_content_or_explore_more_based_on_your_plan_access') }}</p>
            </div>

            <div class="row">
                @include('design_1.web.bundles.components.cards.grids.index',['bundles' => $recommendedBundles, 'gridCardClassName' => "col-12 col-lg-4 mt-24"])
            </div>
        @endif

    </div>
@endsection
