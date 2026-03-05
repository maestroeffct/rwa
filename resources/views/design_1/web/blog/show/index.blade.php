@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("swiperjs") }}">
    <link rel="stylesheet" href="{{ getDesign1StylePath("show_blog") }}">
@endpush


@section("content")
    <div class="blog-show-hero position-relative">
        <div class="blog-show-hero__mask"></div>
        <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-cover">
    </div>

    <div class="container position-relative blog-show-body pb-120">
        <div class="blog-show-cover-image position-relative rounded-32">
            <img src="{{ $post->image }}" alt="{{ $post->title }}" class="img-cover rounded-32">
        </div>

        {{-- Header --}}
        @include('design_1.web.blog.show.includes.header')

        {{-- Short Description --}}
        <div class="mt-28 p-16 rounded-16 border-gray-200 bg-gray-100">
            {!! nl2br($post->description) !!}
        </div>

        {{-- Post content --}}
        <div class="mt-24">
            {!! nl2br($post->content) !!}
        </div>

        {{-- Author Info --}}
        @include('design_1.web.blog.show.includes.author_info')

        {{-- Suggested Post --}}
        @include('design_1.web.blog.show.includes.suggested_post')

        {{-- Comments --}}
        @if($post->enable_comment)
            @include('design_1.web.blog.show.includes.comments')
        @endif

    </div>

    {{-- Fixed Bottom --}}
    @include('design_1.web.blog.show.includes.fixed_bottom')

@endsection

@push('scripts_bottom')
    <script>
        var closeLang = '{{ trans('public.close') }}';
        var shareLang = '{{ trans('public.share') }}';
        var reportCommentLang = '{{ trans('update.report_comment') }}';
        var reportLang = '{{ trans('panel.report') }}';
    </script>

    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>

    <script src="{{ getDesign1ScriptPath("comments") }}"></script>
    <script src="{{ getDesign1ScriptPath("show_blog") }}"></script>
@endpush
