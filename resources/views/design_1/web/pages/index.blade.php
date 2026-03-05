@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("other_pages") }}">
@endpush

@section("content")
    <main class="pb-56">
        <section class="pages-hero position-relative">
            <div class="pages-hero__mask"></div>
            <img src="{{ $page->cover }}" class="img-cover" alt="{{ $page->title }} cover"/>
        </section>


        <div class="container">
            {{-- Header --}}
            @include('design_1.web.pages.includes.header')


            {{-- Content --}}
            <div class="pages-content bg-white p-16 rounded-32 mt-28">
                {!! nl2br($page->content) !!}
            </div>
        </div>
    </main>

@endsection
