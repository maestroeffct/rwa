@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("installments") }}">
@endpush

@section("content")
    <div class="container mt-80 mb-120">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32 font-weight-bold">{{ trans('update.verify_your_installments') }}</h1>
            <p class="mt-8 font-16 text-gray-500">{{ trans('update.verify_your_installments_hint') }}</p>
        </div>

        <div class="position-relative">
            <div class="installment-verify__mask bg-white"></div>

            <div class="position-relative bg-white rounded-32 py-16 mt-24 z-index-2">
                {{-- Installment Overview --}}
                <div class="px-16">
                    @include("design_1.web.installments.verify.overview")
                </div>

                {{-- Installment Form --}}
                @include("design_1.web.installments.verify.verify_form")

            </div>
        </div>
    </div>
@endsection

@push("scripts_bottom")
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>

    <script>
        var titleLang = '{{ trans('public.title') }}';
        var attachmentLang = '{{ trans('update.attachment') }}';
        var uploadIcon = `<x-iconsax-lin-export class="icons text-gray-border" width="24px" height="24px"/>`;
        var closeIcon = `<x-iconsax-lin-add class="close-icon text-white" width="25px" height="25px"/>`;
    </script>

    <script src="{{ getDesign1ScriptPath("installment_verify") }}"></script>
@endpush
