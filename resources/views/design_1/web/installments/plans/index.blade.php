@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("installments") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("installment_card") }}">
@endpush

@section("content")
    <div class="container mt-56 mb-120">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32 font-weight-bold">{{ trans('update.select_an_installment_plan') }}</h1>
            <p class="mt-8 font-16 text-gray-500">{{ trans('update.please_select_an_installment_plan_in_order_to_finalize_your_purchase') }}</p>
        </div>

        <div class="position-relative mt-24">

            {{-- Installment Overview --}}
            @include("design_1.web.installments.plans.overview")

            @foreach($installments as $installmentRow)
                @include('design_1.web.installments.includes.card',[
                   'installment' => $installmentRow,
                   'itemPrice' => $itemPrice,
                   'itemId' => $itemId,
                   'itemType' => $itemType,
                   'className' => ($loop->first ? 'mt-28' : '')
                  ])
            @endforeach
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
