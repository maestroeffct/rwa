@extends('design_1.panel.layouts.panel')

@section('content')
    <div class="bg-white pt-16 rounded-24">
        <div class="row">
            <div class="col-12 col-lg-6">
                <h3 class="font-16 px-16 font-weight-bold">{{ trans('update.followers') }} ({{ $followerCount }})</h3>

                @if(!empty($followers) and $followers->isNotEmpty())
                    <div id="tableListContainer" class="table-responsive-lg px-16" data-view-data-path="/panel/upcoming_courses//{{ $upcomingCourse->id }}/followers">
                        <div class="js-table-body-lists row position-relative z-index-2 mt-16">
                            @foreach($followers as $followerRow)
                                @include('design_1.panel.upcoming_courses.followers.item_card', ['follower' => $followerRow])
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                            {!! $pagination !!}
                        </div>
                    </div>
                @else
                    @include('design_1.panel.includes.no-result',[
                        'file_name' => 'upcoming_no_followers.svg',
                        'title' => trans('update.no_followers'),
                        'hint' =>  trans('update.this_course_doesnt_have_any_followers') ,
                        'extraClass' => 'mt-0',
                    ])
                @endif

            </div>


            <div class="col-12 col-lg-6">
                <div class="p-12 rounded-16 border-dashed border-gray-200">
                    <div class="d-flex-center flex-column text-center pt-56 px-32">
                        <div class="">
                            <img src="/assets/design_1/img/no-result/upcoming_followers.svg" alt="{{ trans('update.followers') }}" width="405px" height="301px">
                        </div>

                        <h4 class="mt-16 font-14 font-weight-bold">{{ trans('update.send_a_notification') }}</h4>
                        @if(!empty($upcomingCourse->webinar_id))
                            <p class="mt-8 font-14 text-gray-500">{{ trans('update.published_upcoming_course_send_a_notification_hint') }}</p>
                        @else
                            <p class="mt-8 font-14 text-gray-500">{{ trans('update.upcoming_course_send_a_notification_hint') }}</p>
                        @endif
                    </div>


                    <div class="d-flex align-items-center justify-content-between flex-column flex-lg-row p-16 rounded-12 border-gray-300 bg-gray-100 mt-56">
                        @if(!empty($upcomingCourse->webinar_id))
                            <div class="flex-1">
                                <h5 class="font-14 font-weight-bold">{{ trans('update.course_published') }}</h5>
                                <p class="font-12 text-gray-500 mt-4">{{ trans('update.his_course_already_published') }}</p>
                            </div>

                            <a href="{{ $upcomingCourse->webinar->getUrl() }}" target="_blank" class="btn btn-primary btn-sm mt-16 mt-lg-0">{{ trans('update.view_course') }}</a>
                        @else
                            <div class="flex-1">
                                <h5 class="font-14 font-weight-bold">{{ trans('update.notify_followers') }}</h5>
                                <p class="font-12 text-gray-500 mt-4">{{ trans('update.send_a_notifications_to_all_followers_and_let_them_know_course_publishing') }}</p>
                            </div>

                            <button type="button" data-id="{{ $upcomingCourse->id }}" class="js-mark-as-released btn btn-primary btn-sm mt-16 mt-lg-0">{{ trans('update.assign_a_course') }}</button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        var assignPublishedCourseLang = '{{ trans('update.assign_published_course') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var selectChapterLang = '{{ trans('update.select_chapter') }}';
        var liveSessionInfoLang = '{{ trans('update.live_session_info') }}';
        var joinTheSessionLang = '{{ trans('update.join_the_session') }}';
    </script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="/assets/design_1/js/panel/upcoming_course.min.js"></script>
@endpush
