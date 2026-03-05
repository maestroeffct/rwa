@extends('design_1.panel.layouts.panel')


@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush


@section('content')
    <div class="row pb-60">
        <div class="col-12 col-lg-6">
            <form action="/panel/{{ (!empty($isCourseNotice)) ? 'course-noticeboard' : 'noticeboard' }}/{{ !empty($noticeboard) ? $noticeboard->id.'/update' : 'store' }}" method="post">
                {{ csrf_field() }}

                <div class="bg-white p-16 rounded-24">
                    <h3 class="font-16 mb-28">{{ trans('update.notice_content') }}</h3>

                    <div class="form-group">
                        <label class="form-group-label">{{ trans('public.title') }}</label>
                        <input type="text" name="title" class="js-ajax-title form-control " value="{{ !empty($noticeboard) ? $noticeboard->title : old('title') }}"/>

                        <div class="invalid-feedback d-block"></div>
                    </div>

                    @if(!empty($isCourseNotice))
                        {{-- Course --}}
                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('admin/main.courses') }}</label>
                            <select name="webinar_id" class="js-ajax-webinar_id form-control select2" data-placeholder="{{ trans('update.select_a_course') }}">
                                <option value="">{{ trans('update.select_a_course') }}</option>

                                @if(!empty($webinars))
                                    @foreach($webinars as $webinar)
                                        <option value="{{ $webinar->id }}" {{ (!empty($noticeboard) and $noticeboard->webinar_id == $webinar->id) ? 'selected' : '' }}>{{ $webinar->title }}</option>
                                    @endforeach
                                @endif
                            </select>

                            <div class="invalid-feedback d-block"></div>
                        </div>

                        {{-- Color --}}
                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('update.color') }}</label>
                            <select name="color" class="js-ajax-color form-control select2" data-minimum-results-for-search="Infinity">
                                <option value="">{{ trans('update.select_a_color') }}</option>

                                @foreach(\App\Models\CourseNoticeboard::$colors as $color)
                                    <option value="{{ $color }}" {{ (!empty($noticeboard) and $noticeboard->color == $color) ? 'selected' : '' }}>{{ trans('update.course_noticeboard_color_'.$color) }}</option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback d-block"></div>
                        </div>

                    @else

                        {{-- Types --}}
                        <div class="form-group ">
                            <label class="form-group-label">{{ trans('admin/main.type') }}</label>
                            <select name="type" class="js-ajax-type js-noticeboard-type-select form-control select2" data-minimum-results-for-search="Infinity">
                                <option value="">{{ trans('admin/main.select_type') }}</option>

                                @if($authUser->isOrganization())
                                    @foreach(\App\Models\Noticeboard::$types as $type)
                                        <option value="{{ $type }}" {{ (!empty($noticeboard) and $noticeboard->type == $type) ? 'selected' : '' }} >{{ trans('public.'.$type) }}</option>
                                    @endforeach
                                @else
                                    <option value="students" {{ (!empty($noticeboard) and empty($noticeboard->webinar_id)) ? 'selected' : '' }}>{{ trans('update.all_students') }}</option>
                                    <option value="course" {{ (!empty($noticeboard) and !empty($noticeboard->webinar_id)) ? 'selected' : '' }}>{{ trans('update.course_students') }}</option>
                                @endif
                            </select>

                            <div class="invalid-feedback d-block"></div>
                        </div>

                        {{-- Course --}}
                        @if($authUser->isTeacher())
                            <div class="js-instructor-courses-fields form-group  {{ (!empty($noticeboard) and !empty($noticeboard->webinar_id)) ? '' : 'd-none' }}">
                                <label class="form-group-label">{{ trans('admin/main.courses') }}</label>
                                <select name="webinar_id" class="js-ajax-webinar_id form-control select2" data-placeholder="{{ trans('update.select_a_course') }}">
                                    <option value="">{{ trans('update.select_a_course') }}</option>

                                    @if(!empty($webinars))
                                        @foreach($webinars as $webinar)
                                            <option value="{{ $webinar->id }}" {{ (!empty($noticeboard) and $noticeboard->webinar_id == $webinar->id) ? 'selected' : '' }}>{{ $webinar->title }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <div class="invalid-feedback d-block"></div>
                            </div>
                        @endif

                    @endif

                    <div class="form-group bg-white-editor">
                        <label class="form-group-label">{{ trans('site.message') }}</label>
                        <textarea name="message" class="js-ajax-message summernote form-control text-left" data-height="350" rows="6">{{ (!empty($noticeboard)) ? $noticeboard->message :'' }}</textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                     <div class="d-flex align-items-lg-center justify-content-lg-between flex-column flex-lg-row border-top-gray-100 pt-16 mt-16">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                                <x-iconsax-bol-info-circle class="icons text-gray-400" width="24px" height="24px"/>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14">{{ trans('update.notice') }}</h5>
                                <p class="mt-2 font-12 text-gray-500">{{ trans('update.the_notice_will_be_send_on_the_selected_target') }}</p>
                            </div>
                        </div>

                        <button type="button" class="js-submit-noticeboard-form btn btn-lg btn-primary mt-16 mt-lg-0">{{ trans('notification.post_notice') }}</button>
                    </div>

                </div>



            </form>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>

    <script src="/assets/design_1/js/panel/noticeboard.min.js"></script>
@endpush
