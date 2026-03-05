@extends('design_1.panel.layouts.panel')

@push('styles_top')

@endpush

@section('content')

    @if(!empty($topics) and !$topics->isEmpty())
        <div class="bg-white pt-16 rounded-24">

            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.bookmarks') }}</h3>

                </div>
            </div>

            {{-- Filters --}}

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/forums/bookmarks">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left" width="400px">{{ trans('public.topic') }}</th>
                        <th class="text-center">{{ trans('update.forum') }}</th>
                        <th class="text-center">{{ trans('update.replies') }}</th>
                        <th class="text-center">{{ trans('public.publish_date') }}</th>
                        <th class="text-center" width="60px">{{ trans('admin/main.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($topics as $topicRow)
                        @include('design_1.panel.forum.bookmarks.table_items', ['topic' => $topicRow])
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
            'file_name' => 'forum_bookmarks.svg',
            'title' => trans('update.panel_topics_bookmark_no_result'),
            'hint' => nl2br(trans('update.panel_topics_bookmark_no_result_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
@endpush
