<div class="position-relative courses-lists-filters">
    <div class="courses-lists-filters__mask"></div>

    <div id="leftFiltersAccordion" class="position-relative bg-white py-16 rounded-24 z-index-2">

        {{-- Types --}}
        <div class="accordion card-before-line card-before-line__4-12 pb-16 px-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersTypes" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('public.type') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersTypes" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersTypes" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                @foreach(['webinar','course','text_lesson'] as $typeOption)
                    <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                        <input type="checkbox" name="type[]" value="{{ $typeOption }}" id="filter_type_{{ $typeOption }}" class="custom-control-input">
                        <label class="custom-control__label cursor-pointer" for="filter_type_{{ $typeOption }}">{{ trans('webinars.'.$typeOption) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Category Filters And Options --}}
        @if(!empty($category) and !empty($category->filters))
            @foreach($category->filters as $filter)
                <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
                    <div class="accordion__title d-flex align-items-center justify-content-between">
                        <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#filters_id_{{ $filter->id }}" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                            {{ $filter->title }}
                        </div>

                        <span class="collapse-arrow-icon d-flex cursor-pointer" href="#filters_id_{{ $filter->id }}" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                            <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                        </span>
                    </div>

                    <div id="filters_id_{{ $filter->id }}" class="accordion__collapse pt-0 mt-0 border-0" role="tabpanel">
                        @foreach($filter->options as $option)
                            <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                                <input type="checkbox" name="filter_option[]" value="{{ $option->id }}" id="filter_option_id_{{ $option->id }}" class="custom-control-input">
                                <label class="custom-control__label cursor-pointer" for="filter_option_id_{{ $option->id }}">{{ $option->title }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        {{-- Prices Filters --}}
        <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
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

        {{-- Instructor --}}
        <div class="accordion card-before-line card-before-line__4-12 p-16 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersInstructor" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('public.instructor') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersInstructor" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersInstructor" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <div class="form-group mb-0  mt-24">
                    <label class="form-group-label">{{ trans('update.course_instructor') }}</label>
                    <select name="instructor" class="form-control searchable-select bg-white" data-allow-clear="true" data-placeholder="{{ trans('update.search_and_select_instructor') }}"
                            data-api-path="/users/search"
                            data-item-column-name="full_name"
                            data-option="just_teachers"
                    >

                    </select>
                </div>
            </div>
        </div>

        {{-- Rating --}}
        <div class="accordion card-before-line card-before-line__4-12 pt-16 px-16 pb-4">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersRatings" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('update.rating') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersRatings" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersRatings" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                @foreach([5,4,3,2,1] as $rateNum)
                    <div class="d-flex align-items-center justify-content-between {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="rating" id="rating_{{ $rateNum }}" value="{{ $rateNum }}" class="custom-control-input">
                            <label class="custom-control__label cursor-pointer pl-0" for="rating_{{ $rateNum }}">
                                @include('design_1.web.components.rate', [
                                     'rate' => $rateNum,
                                     'rateCount' => false,
                                     'rateClassName' => ''
                                 ])
                            </label>
                        </div>

                        <span class="font-12 text-gray-400">{{ !empty($coursesRatingsCount[$rateNum]) ? $coursesRatingsCount[$rateNum] : 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
</div>
