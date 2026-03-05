@extends('design_1.panel.layouts.panel')

@section('content')
    {{-- Top Stats --}}
    @include('design_1.panel.blog.posts.lists.top_stats')

    {{-- Lists --}}
    @if(!empty($posts) and count($posts))
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.articles') }}</h3>
                    <p class="font-14 text-gray-500 mt-4">{{ trans('update.view_blog_posts_and_related_statistics') }}</p>
                </div>

                @can('panel_blog_new_article')
                    <a href="/panel/blog/new" class="btn btn-primary">{{ trans('update.new_post') }}</a>
                @endcan
            </div>

            {{-- Filters --}}


            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/blog">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.title') }}</th>
                        <th class="text-center">{{ trans('public.category') }}</th>
                        <th class="text-center">{{ trans('panel.comments') }}</th>
                        <th class="text-center">{{ trans('update.visit_count') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('public.date_created') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($posts as $postRow)
                        @include('design_1.panel.blog.posts.lists.table_items', ['post' => $postRow])
                    @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    {!! $pagination !!}
                </div>
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'blog_posts.svg',
            'title' => trans('update.blog_post_no_result'),
            'hint' => nl2br(trans('update.blog_post_no_result_hint')),
            'btn' => ['url' => '/panel/blog/new','text' => trans('update.create_a_post')]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
