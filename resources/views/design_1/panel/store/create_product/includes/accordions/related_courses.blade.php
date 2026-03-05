<li data-id="{{ !empty($relatedCourse) ? $relatedCourse->id :'' }}" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="relatedCourse_{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" data-parent="#relatedCoursesAccordion" role="button" data-toggle="collapse">
            <span>{{ (!empty($relatedCourse) and !empty($relatedCourse->course)) ? $relatedCourse->course->title .' - '. $relatedCourse->course->teacher->full_name : trans('update.add_new_related_courses') }}</span>
        </div>

        @if(!empty($relatedCourse))
            <div class="d-flex align-items-center">
                <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 class="icons" width="18"/></span>

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/relatedCourses/{{ $relatedCourse->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" data-parent="#relatedCoursesAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="18"/>
                </span>
            </div>
        @endif

    </div>

    <div id="collapseRelatedCourse{{ !empty($relatedCourse) ? $relatedCourse->id :'record' }}" class="accordion__collapse {{ empty($relatedCourse) ? 'show' : '' }}" role="tabpanel">
        <div class="js-content-form js-relatedCourse-form" data-action="/panel/relatedCourses/{{ !empty($relatedCourse) ? $relatedCourse->id . '/update' : 'store' }}">
            <input type="hidden" name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_id]" value="{{ $product->id }}">
            <input type="hidden" name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][item_type]" value="product">

            <div class="form-group mt-20">
                <label class="form-group-label">{{ trans('update.select_related_courses') }}</label>

                <select name="ajax[{{ !empty($relatedCourse) ? $relatedCourse->id : 'new' }}][course_id]" class="js-ajax-course_id form-control searchable-select bg-white" data-allow-clear="false" data-placeholder="{{ trans('update.search_courses') }}"
                        data-api-path="/panel/courses/search"
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


            <div class="mt-30 d-flex align-items-center">
                <button type="button" class="js-save-course-content btn btn-primary">{{ trans('public.save') }}</button>

                @if(!empty($relatedCourse))
                    <a href="/panel/relatedCourses/{{ $relatedCourse->id }}/delete" class="delete-action btn btn-outline-danger ml-8 cancel-accordion">{{ trans('delete') }}</a>
                @endif
            </div>
        </div>
    </div>
</li>
