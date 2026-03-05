@extends('design_1.panel.layouts.panel')

@section('content')

    @if(!empty($notifications) and !$notifications->isEmpty())
        <div class="card-with-dashed-mask d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-white p-16 rounded-16">
            <div class="">
                <h4 class="font-14 font-weight-bold">{{ trans('update.manage_notifications') }}</h4>
                <p class="mt-4 font-12 text-gray-500">{{ trans('update.simply_mark_all_available_notifications_as_read_or_clear_them') }}</p>
            </div>

            <div class="d-flex align-items-center mt-16 mt-lg-0">

                <a href="/panel/notifications/mark-all-as-read" class="delete-action cursor-pointer text-primary font-14 font-weight-bold" data-msg="{{ trans('update.convert_unread_messages_to_read') }}" data-confirm="{{ trans('update.yes_convert') }}">
                    {{ trans('update.mark_all_as_read') }}
                </a>

            </div>
        </div>

        {{-- Notifications Lists --}}
        <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/notifications">
            <div class="js-notifications-lists">
                @foreach($notifications as $notificationRow)
                    @include('design_1.panel.notifications.notif_card', ['notification' => $notificationRow])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-notifications-lists">
                {!! $pagination !!}
            </div>
        </div>
    @else
        @include('design_1.panel.includes.no-result',[
            'file_name' => 'notifications.svg',
           'title' => trans('panel.notification_no_result'),
           'hint' => nl2br(trans('panel.notification_no_result_hint')),
           'extraClass' => 'mt-0',
        ])
    @endif
@endsection

@push('scripts_bottom')
    <script>
        (function ($) {
            "use strict";

            @if(!empty(request()->get('notification')))
            setTimeout(() => {
                $('body #showNotificationMessage{{ request()->get('notification') }}').trigger('click');

                let url = window.location.href;
                url = url.split('?')[0];
                window.history.pushState("object or string", "Title", url);
            }, 400);
            @endif
        })(jQuery)
    </script>

    <script>
        var viewNotificationLang = '{{ trans('update.view_notification') }}';
        var closeLang = '{{ trans('public.close') }}';
    </script>

    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/notifications.min.js"></script>
@endpush
