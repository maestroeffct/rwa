@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("product_show") }}">
@endpush

@section('content')
    <div class="container mt-160 mb-160">
        <div class="bg-white py-16 rounded-24">
            <div class="d-flex align-items-start px-16">
                <div class="product-files-thumbnail size-126 rounded-24 bg-gray-100">
                    <img src="{{ $product->thumbnail }}" alt="{{ $product->title }}" class="img-cover rounded-24">
                </div>
                <div class="ml-16">
                    <a href="{{ $product->getUrl() }}" class="">
                        <h1 class="font-16 text-dark">{{ $product->title }}</h1>
                    </a>

                    {{-- Rate --}}
                    @include('design_1.web.components.rate', [
                         'rate' => $product->getRate(),
                         'rateCount' => $product->getRateCount(),
                         'rateClassName' => 'mt-8',
                         'rateCountFont' => 'font-12',
                     ])

                    <div class="d-flex align-items-center mt-16 gap-24">
                        <a href="{{ $product->creator->getProfileUrl() }}" target="_blank" class="d-flex align-items-center text-gray-500">
                            <x-iconsax-lin-profile class="icons text-gray-500" width="16px" height="16px"/>
                            <span class="ml-4 font-12 font-weight-bold">{{ $product->creator->full_name }}</span>
                        </a>

                        <div class="d-flex align-items-center text-gray-500">
                            <x-iconsax-lin-document class="icons text-gray-500" width="16px" height="16px"/>
                            <span class="mx-4 font-12 font-weight-bold">{{ $product->files->count() }}</span>
                            <span class="font-12 ">{{ trans('public.files') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-16 mt-16 border-top-gray-200">

                {{-- Files --}}
                @include("design_1.web.products.show.includes.files_accordion", ['files' => $product->files])

            </div>

        </div>
    </div>
@endsection
