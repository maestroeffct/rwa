@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    @if(empty($isCourseNotice))
        @include('design_1.panel.noticeboard.lists.top_stats')
    @endif

    @if(!empty($noticeboards) and !$noticeboards->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('panel.noticeboards') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.noticeboard.lists.filters')

            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/{{ !empty($isCourseNotice) ? 'course-noticeboard' : 'noticeboard' }}">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('webinars.title') }}</th>
                        <th class="text-center">{{ trans('site.message') }}</th>

                        @if(!empty($isCourseNotice) and $isCourseNotice)
                            <th class="text-center">{{ trans('update.color') }}</th>
                        @else
                            <th class="text-center">{{ trans('public.type') }}</th>
                        @endif

                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($noticeboards as $noticeboardRow)
                        @include('design_1.panel.noticeboard.lists.table_items', ['noticeboard' => $noticeboardRow])
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
          'file_name' => 'noticeboard.svg',
            'title' => trans('update.noticeboard_no_result'),
            'hint' =>  nl2br(trans('update.noticeboard_no_result_hint')) ,
        ])
    @endif

    <div class="d-none" id="noticeboardMessageModal">
        <div class="text-center py-20 px-16">
            <h3 class="modal-title font-16 font-weight-bold"></h3>
            <span class="modal-time d-block font-12 text-gray-500 mt-16"></span>
            <div class="modal-message mt-8"></div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/noticeboard.min.js"></script>
@endpush
