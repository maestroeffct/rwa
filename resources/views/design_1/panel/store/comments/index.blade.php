@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    {{-- Top Stats --}}
    @include('design_1.panel.store.comments.top_stats')

    @if(!empty($comments) and !$comments->isEmpty())
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16">{{ trans('update.product_comments_list') }}</h3>

                </div>
            </div>

            {{-- Filters --}}
            @include('design_1.panel.store.comments.filters')


            {{-- List Table --}}
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/store/products/comments">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('panel.user') }}</th>
                        <th class="text-left">{{ trans('update.product') }}</th>
                        <th class="text-center">{{ trans('panel.comment') }}</th>
                        <th class="text-center">{{ trans('public.status') }}</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-right">{{ trans('update.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    @foreach($comments as $commentRow)
                        @include('design_1.panel.store.comments.table_items', ['comment' => $commentRow])
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
            'file_name' => 'store_product_comments.svg',
            'title' => trans('panel.comments_no_result'),
            'hint' =>  nl2br(trans('panel.comments_no_result_hint')) ,
        ])
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var commentLang = '{{ trans('panel.comment') }}';
        var replyToCommentLang = '{{ trans('panel.reply_to_the_comment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/comments.min.js"></script>
@endpush
