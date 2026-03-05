@extends('design_1.panel.layouts.panel')

@section('content')

    @if(!empty($supports) and !$supports->isEmpty())
        <div class="row">
            <div class="col-12 col-lg-4">
                @include('design_1.panel.support.conversations.lists')
            </div>

            <div class="col-12 col-lg-8 mt-20 mt-lg-0">
                @include('design_1.panel.support.conversations.messages')
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'support_tickets.svg',
            'title' => trans('panel.support_no_result'),
            'hint' => nl2br(trans('panel.support_no_result_hint')),
            'extraClass' => 'mt-0',
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script src="/assets/design_1/js/panel/conversations.min.js"></script>
@endpush
