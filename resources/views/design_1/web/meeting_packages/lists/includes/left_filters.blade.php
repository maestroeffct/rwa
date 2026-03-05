<div class="position-relative meeting-packages-lists-filters position-relative bg-white rounded-24">
    <div class="meeting-packages-lists-filters__mask"></div>

    {{-- Filter Instructors --}}
    <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100 z-index-3">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFilterInstructors" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.filter_instructors') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFilterInstructors" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFilterInstructors" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            {{-- Instructors --}}
            <div class="form-group mb-0  mt-24">
                <label class="form-group-label">{{ trans('update.instructor') }}</label>
                <select name="instructor" class="form-control searchable-select bg-white" data-allow-clear="true" data-placeholder="{{ trans('update.search_and_select_instructor') }}"
                        data-api-path="/users/search"
                        data-item-column-name="full_name"
                        data-option="except_user"
                >

                </select>
            </div>



            <div class="form-group  mt-24">
                <label class="form-group-label" for="category_id">{{ trans('public.category') }}</label>

                <select name="category_id" id="category_id" class="form-control select2">
                    <option value="">{{ trans('webinars.select_category') }}</option>

                    @if(!empty($categories))
                        @foreach($categories as $category)
                            @if(!empty($category->subCategories) and count($category->subCategories))
                                <optgroup label="{{  $category->title }}">
                                    @foreach($category->subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" @if(request()->get('category_id') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{ $category->id }}" @if(request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group ">
                <label class="form-group-label" for="level_of_training">{{ trans('update.student_level') }}</label>

                <select name="level_of_training" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('update.not_preferenced') }}</option>
                    <option value="beginner" {{ (request()->get('level_of_training') == 'beginner') ? 'selected' : '' }}>{{ trans('update.beginner') }}</option>
                    <option value="middle" {{ (request()->get('level_of_training') == 'middle') ? 'selected' : '' }}>{{ trans('update.middle') }}</option>
                    <option value="expert" {{ (request()->get('level_of_training') == 'expert') ? 'selected' : '' }}>{{ trans('update.expert') }}</option>
                </select>
            </div>

            <div class="form-group ">
                <label class="form-group-label" for="gender">{{ trans('update.instructor_gender') }}</label>

                <select name="gender" id="gender" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('update.not_preferenced') }}</option>

                    <option value="man" {{ (request()->get('gender') == 'man') ? 'selected' : '' }}>{{ trans('update.man') }}</option>
                    <option value="woman" {{ (request()->get('gender') == 'woman') ? 'selected' : '' }}>{{ trans('update.woman') }}</option>
                </select>
            </div>

            <div class="form-group mb-0 ">
                <label class="form-group-label" for="instructor_type">{{ trans('update.instructor_type') }}</label>

                <select name="role" id="instructor_type" class="form-control select2" data-minimum-results-for-search="Infinity">
                    <option value="">{{ trans('update.not_preferenced') }}</option>
                    <option value="{{ \App\Models\Role::$teacher }}" {{ (request()->get('role') == \App\Models\Role::$teacher) ? 'selected' : '' }}>{{ trans('public.instructor') }}</option>
                    <option value="{{ \App\Models\Role::$organization }}" {{ (request()->get('role') == \App\Models\Role::$organization) ? 'selected' : '' }}>{{ trans('home.organization') }}</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Prices Filters --}}
    <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100 z-index-3">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersPrices" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('public.price') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersPrices" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
        </div>

        <div id="leftFiltersPrices" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">

            <div class="d-flex align-items-center mt-16">
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ trans('update.free') }}" class="js-filters-min-price form-control input-xs bg-white text-center text-gray-500">
                </div>
                <div class="mx-4"></div>
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ handlePrice($filterMaxPrice) }}" class="js-filters-max-price form-control input-xs bg-white text-center text-gray-500">
                </div>
            </div>

            <div
                class="course-list-price-range range wrunner-value-bottom no-bottom-value-note mt-8"
                id="priceRange"
                data-minLimit="0"
                data-maxLimit="{{ $filterMaxPrice }}"
                data-step="{{ ($filterMaxPrice < 100) ? 2 : (($filterMaxPrice < 500) ? 50 : 100) }}"
            >
                <input type="hidden" name="min_price" value="" class="js-range-input-view-data">
                <input type="hidden" name="max_price" value="" class="js-range-input-view-data">
            </div>

        </div>
    </div>


    {{-- Meeting Duration Range Filters --}}
    <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100 z-index-3">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersMeetingDurationRange" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.meeting_duration_range') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersMeetingDurationRange" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
            </span>
        </div>

        <div id="leftFiltersMeetingDurationRange" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">

            <div class="d-flex align-items-center mt-16">
                <div class="form-group mb-0">
                    <input type="tel" readonly value="0" class="js-filters-min-duration form-control input-xs bg-white text-center text-gray-500">
                </div>
                <div class="mx-4"></div>
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ $filterMaxDuration }}" class="js-filters-max-duration form-control input-xs bg-white text-center text-gray-500">
                </div>
            </div>

            <div
                class="course-list-duration-range range wrunner-value-bottom no-bottom-value-note mt-8"
                id="durationRange"
                data-minLimit="0"
                data-maxLimit="{{ $filterMaxDuration }}"
                data-step="{{ ($filterMaxDuration < 100) ? 2 : (($filterMaxDuration < 500) ? 5 : 10) }}"
            >
                <input type="hidden" name="min_duration" value="" class="js-range-input-view-data">
                <input type="hidden" name="max_duration" value="" class="js-range-input-view-data">
            </div>

        </div>
    </div>

</div>
