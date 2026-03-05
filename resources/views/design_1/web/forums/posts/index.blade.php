@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">

    <link rel="stylesheet" href="{{ getDesign1StylePath("forum") }}">
@endpush


@section('content')
    <div class="container mt-88 mb-64">

        {{-- Hero --}}
        @include('design_1.web.forums.posts.includes.hero')

        {{-- Topic --}}
        @include('design_1.web.forums.posts.includes.post_card')

        {{-- Topic Posts --}}
        <div id="listsContainer" class="" data-body=".js-lists-body" data-view-data-path="{{ $topic->getPostsUrl() }}">
            <div class="js-lists-body">
                @if(!empty($posts) and count($posts))
                    @foreach($posts as $postRow)
                        @include('design_1.web.forums.posts.includes.post_card',['post' => $postRow])
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            <div id="pagination" class="js-ajax-pagination" data-container-id="listsContainer" data-container-items=".js-lists-body">
                {!! $pagination !!}
            </div>
        </div>

        @if(!auth()->check())
            @include('design_1.web.forums.posts.includes.login_to_reply')
        @elseif($topic->close or $forum->close)
            @include('design_1.web.forums.posts.includes.closed_topic')
        @else
            @include('design_1.web.forums.posts.includes.reply_form')
        @endif
    </div>
@endsection


@push('scripts_bottom')
    <script>
        var notLoginToastTitleLang = '{{ trans('public.not_login_toast_lang') }}';
        var notLoginToastMsgLang = '{{ trans('public.not_login_toast_msg_lang') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var editPostLang = '{{ trans('update.edit_post') }}';
        var saveLang = '{{ trans('public.save') }}';
    </script>

    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="{{ getDesign1ScriptPath("topic_posts") }}"></script>
@endpush
