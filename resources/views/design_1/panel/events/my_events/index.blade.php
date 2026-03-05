@extends('design_1.panel.layouts.panel')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
@endpush

@section('content')
    {{-- Top Stats --}}
    @include('design_1.panel.events.my_events.top_stats')

    {{-- List Table --}}
    @if(!empty($events) and $events->isNotEmpty())
        <div id="tableListContainer" class="" data-view-data-path="/panel/events">
            <div class="js-page-events-lists row mt-20">
                @foreach($events as $eventItem)
                    <div class="col-12 col-lg-6 mb-32">
                        @include("design_1.panel.events.my_events.event_card.index", ['event' => $eventItem])
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-events-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'event_list.svg',
            'title' => trans('update.my_events_lists_no_result_title'),
            'hint' =>  trans('update.my_events_lists_no_result_hint') ,
            'btn' => ['url' => '/panel/events/new','text' => trans('update.create_a_event') ]
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script>
        var createASessionLang = '{{ trans('update.create_a_session') }}';
        var createSessionLang = '{{ trans('update.create_session') }}';
        var saveLang = '{{ trans('public.save') }}';
        var cancelLang = '{{ trans('public.cancel') }}';
        var joinTheEventLang = '{{ trans('update.join_the_event') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
        var passwordLang = '{{ trans('auth.password') }}';
    </script>

    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/my_events_lists.min.js"></script>
@endpush
