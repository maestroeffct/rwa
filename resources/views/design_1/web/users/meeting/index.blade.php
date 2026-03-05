@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("profile_reserve_meeting") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("meeting_reservation") }}">
@endpush

@php
    $overlayImage = getThemePageBackgroundSettings("meeting_booking_step_{$step}_image");
@endphp

@section('content')
    <section class="container pb-100 position-relative mt-54">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 position-relative">

                <div class="d-flex-center flex-column text-center mt-56">
                    <h1 class="font-32">{{ trans('public.book_a_meeting') }}</h1>
                    <p class="mt-8 font-16 text-gray-500">{{ trans('update.book_a_meeting_with_professional_and_skillful_mentors') }}</p>
                </div>

                <div class="meeting-book position-relative mt-56">
                    <div class="meeting-book__mask"></div>

                    <div class="position-relative bg-white rounded-32 p-32 pt-36 z-index-2">
                        @include("design_1.web.users.meeting.steps.step_{$step}")
                    </div>
                </div>

                @if(!empty($overlayImage))
                    <div class="meeting-book-right-float-image">
                        <img src="{{ $overlayImage }}" alt="{{ trans('update.right_float_image') }}" class="img-cover">
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("meeting_reservation") }}"></script>
@endpush
