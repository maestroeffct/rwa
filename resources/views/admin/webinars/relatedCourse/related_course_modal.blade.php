<div class="" id="relatedCourseModal">
    <div class="js-related-course-form" data-action="{{ getAdminPanelUrl("/relatedCourses/").(!empty($relatedCourse) ? $relatedCourse->id.'/update' : 'store') }}">
        <input type="hidden" name="item_id" value="{{ $itemId }}">
        <input type="hidden" name="item_type" value="{{ $itemType }}">

        <div class="form-group mt-15">
            <label class="input-label d-block">{{ trans('update.select_related_courses') }}</label>

            <select name="course_id" class="js-ajax-course_id form-control bg-white related-course-select2"
                    data-allow-clear="false"
                    data-placeholder="{{ trans('update.search_courses') }}"
                    data-dropdown-parent=".js-custom-modal"
                    data-api-path="{{ getAdminPanelUrl("/webinars/search") }}"
                    data-item-column-name="title"
                    data-option=""
                    data-webinar-id=""
            >
                @if(!empty($relatedCourse) and !empty($relatedCourse->course))
                    <option selected value="{{ $relatedCourse->course->id }}">{{ $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name }}</option>
                @endif
            </select>

            <div class="invalid-feedback"></div>
        </div>

    </div>
</div>
