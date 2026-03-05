<div class="instructor-finder__filters-card position-relative bg-white p-16 rounded-24 mt-28">

    {{-- Filter Instructors --}}
    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFilterInstructors" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.filter_instructors') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFilterInstructors" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFilterInstructors" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

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

    {{-- Rating --}}
    {{--<div class="accordion pt-16">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersPrices" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.rating') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersPrices" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFiltersPrices" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <div class="mt-8">
                @foreach([5,4,3,2,1] as $rateFilter)
                    <div class="custom-control custom-radio mt-12">
                        <input type="radio" name="rating" value="{{ $rateFilter }}" id="rating_{{ $rateFilter }}" class="custom-control-input" {{ (request()->get('rating') == $rateFilter) ? 'checked' : '' }}>
                        <label class="custom-control__label cursor-pointer" for="rating_{{ $rateFilter }}">
                            @include('design_1.web.components.rate', ['rate' => $rateFilter])
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>--}}

</div>
