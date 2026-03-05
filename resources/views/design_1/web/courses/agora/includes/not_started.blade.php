<div class="d-flex-center flex-column text-center py-160 border-gray-200 rounded-12 h-100 w-100">
    @if($notStarted)
        <div class="">
            <img src="/assets/design_1/img/courses/learning_page/empty_state.svg" alt="" class="img-fluid" width="285px" height="212px">
        </div>

        <h3 class="mt-12 font-16">{{ trans('update.this_live_has_not_started_yet') }}</h3>
        <div class="mt-8 text-gray-500">{{ trans('update.this_live_has_not_started_yet_hint') }}</div>
    @else
        <div class="js-agora-stream-loading">
            <img src="/assets/default/img/loading.gif" alt="" width="80px" height="80px">

            <p class="mt-12 text-gray-500">{{ trans('update.wait_to_join_the_channel') }}</p>
        </div>
    @endif
</div>
