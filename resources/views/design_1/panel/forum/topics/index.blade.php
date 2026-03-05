@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.forum.topics.top_stats')

    @if(!empty($topics) and !$topics->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.my_topics') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.forum.topics.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/forums/topics">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('public.title') }}</th>
                        <th class="text-center">{{ trans('update.forum') }}</th>
                        <th class="text-center">{{ trans('site.posts') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('public.publish_date') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($topics as $topicRow)
                        @include('design_1.panel.forum.topics.table_items', ['topic' => $topicRow])
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
            'title' => trans('update.panel_topics_no_result'),
            'hint' => nl2br(trans('update.panel_topics_no_result_hint')),
            'btn' => ['url' => '/forums','text' => trans('update.forums')]
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
