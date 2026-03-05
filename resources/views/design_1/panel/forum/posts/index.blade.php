@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    @if(!empty($posts) and !$posts->isEmpty())
        <div class="bg-white pt-16 rounded-24">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.my_posts') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.forum.posts.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/forums/posts">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.topic') }}</th>
                        <th class="text-center">{{ trans('update.forum') }}</th>
                        <th class="text-center">{{ trans('update.replies') }}</th>
                        <th class="text-center">{{ trans('public.publish_date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($posts as $postRow)
                        @include('design_1.panel.forum.posts.table_items', ['post' => $postRow])
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
            'file_name' => 'forum_posts.svg',
            'title' => trans('update.panel_topics_posts_no_result'),
            'hint' => nl2br(trans('update.panel_topics_posts_no_result_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
