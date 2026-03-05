@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{!empty($event) ?trans('update.edit_event'): trans('update.new_event') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="{{ getAdminPanelUrl("/events") }}">{{ trans('update.events') }}</a>
                </div>
                <div class="breadcrumb-item">{{!empty($event) ?trans('/admin/main.edit'): trans('admin/main.new') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="card">
                <div class="card-body">

                    <form method="post" action="{{ getAdminPanelUrl("/events/") }}{{ !empty($event) ? $event->id.'/update' : 'store' }}" enctype="multipart/form-data" class="webinar-form">
                        {{ csrf_field() }}
                        <input type="hidden" name="status" value="{{ !empty($event) ? $event->status : 'draft' }}"/>

                        {{-- Basic Information --}}
                        @include('admin.events.create.includes.basic_information')

                        {{-- Additional Information --}}
                        @include('admin.events.create.includes.additional_information')

                        {{-- Categories --}}
                        @include('admin.events.create.includes.categories')

                        @if(!empty($event))

                            {{-- Tickets --}}
                            @can('admin_events_tickets')
                                @include('admin.events.create.includes.tickets')
                            @endcan

                            {{-- Speakers --}}
                            @can('admin_events_speakers')
                                @include('admin.events.create.includes.speakers')
                            @endcan

                            {{-- Prerequisites --}}
                            @include('admin.events.create.includes.prerequisites')

                            {{-- Related Course --}}
                            @include('admin.webinars.relatedCourse.add_related_course', [
                                    'relatedCourseItemId' => $event->id,
                                     'relatedCourseItemType' => 'event',
                                     'relatedCourses' => $event->relatedCourses
                                ])

                            {{-- FAQs --}}
                            @include('admin.events.create.includes.faqs')

                            {{-- Extra Descriptions --}}
                            @include('admin.events.create.includes.extra_descriptions')

                            {{-- Location --}}
                            @if($event->type == "in_person")
                                @include('admin.components.location', ['specificLocation' => $event->specificLocation])
                            @endif

                        @endif


                        <section class="mt-3">
                            <h2 class="section-title after-line">{{ trans('public.message_to_reviewer') }}</h2>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mt-15">
                                        <textarea name="message_for_reviewer" rows="10" class="form-control">{{ (!empty($event) && $event->message_for_reviewer) ? $event->message_for_reviewer : old('message_for_reviewer') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="js-form-action-btn btn btn-success" data-status="{{ !empty($event) ? 'publish' : 'draft' }}">{{ !empty($event) ? trans('admin/main.save_and_publish') : trans('admin/main.save_and_continue') }}</button>

                                @if(!empty($event))
                                    <button type="button" class="js-form-action-btn btn btn-warning" data-status="draft">{{ trans('public.draft') }}</button>

                                    <button type="button" class="js-form-action-btn btn btn-danger" data-status="unpublish">{{ ($event->status == "publish") ? trans('update.unpublish') : trans('public.reject') }}</button>

                                    @include('admin.includes.delete_button',[
                                            'url' => getAdminPanelUrl().'/events/'. $event->id .'/delete',
                                            'btnText' => trans('public.delete'),
                                            'hideDefaultClass' => true,
                                            'btnClass' => 'btn btn-danger'
                                            ])
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script>
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/default/vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>

    <script src="/assets/admin/js/parts/create_event.min.js"></script>
@endpush
