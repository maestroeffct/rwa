<div class="position-relative instructors-lists-filters mt-12">
    <div class="instructors-lists-filters__mask"></div>

    <div id="leftFiltersAccordion" class="position-relative bg-white py-16 rounded-24 z-index-2">

        {{-- Types --}}
        <div class="accordion card-before-line card-before-line__4-12 py-16 px-16 pb-4 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersSkills" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    {{ trans('product.instructor_skills') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersSkills" data-parent="#leftFiltersAccordion" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersSkills" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <div class="form-group mb-0 mt-24 ">
                    <label class="form-group-label">{{ trans('update.skill_category') }}</label>
                    <select class="js-skills-select form-control select2">
                        <option value="">{{ trans('update.select_a_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="js-selected-category-filters d-flex flex-wrap gap-12 mt-12">

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

                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<div class="position-relative instructors-lists-filters mt-28">
    <div class="instructors-lists-filters__mask"></div>

    <div id="leftFiltersAccordion2" class="position-relative bg-white py-16 rounded-24 z-index-2">
        {{-- Meeting Options --}}
        <div class="accordion card-before-line card-before-line__4-12 py-16 px-16 pb-4 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersMeetingOptions" data-parent="#leftFiltersAccordion2" role="button" data-toggle="collapse">
                    {{ trans('update.meeting_options') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersMeetingOptions" data-parent="#leftFiltersAccordion2" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersMeetingOptions" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                @foreach(['available_for_meetings', 'free_meetings', 'meetings_discount'] as $meetingOption)
                    <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                        <input type="checkbox" name="meeting_options[]" value="{{ $meetingOption }}" id="filter_meeting_options_{{ $meetingOption }}" class="custom-control-input">
                        <label class="custom-control__label cursor-pointer" for="filter_meeting_options_{{ $meetingOption }}">{{ trans('update.'.$meetingOption) }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Other Options --}}
        <div class="accordion card-before-line card-before-line__4-12 py-16 px-16 pb-4 border-bottom-gray-100">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersOtherOptions" data-parent="#leftFiltersAccordion2" role="button" data-toggle="collapse">
                    {{ trans('update.other_options') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersOtherOptions" data-parent="#leftFiltersAccordion2" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersOtherOptions" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                @foreach(['instructor_with_courses', 'verified_instructors_only'] as $otherOption)
                    <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                        <input type="checkbox" name="meeting_options[]" value="{{ $otherOption }}" id="filter_meeting_options_{{ $otherOption }}" class="custom-control-input">
                        <label class="custom-control__label cursor-pointer" for="filter_meeting_options_{{ $otherOption }}">{{ trans('update.'.$otherOption) }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- Organization --}}
        <div class="accordion card-before-line card-before-line__4-12 pt-16 px-16 pb-4">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="font-14 font-weight-bold text-dark cursor-pointer" href="#leftFiltersOrganization" data-parent="#leftFiltersAccordion2" role="button" data-toggle="collapse">
                    {{ trans('home.organization') }}
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#leftFiltersOrganization" data-parent="#leftFiltersAccordion2" role="button" data-toggle="collapse">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="16"/>
                </span>
            </div>

            <div id="leftFiltersOrganization" class="accordion__collapse show pt-0 mt-0 border-0" role="tabpanel">
                <div class="form-group mb-0 mt-24">
                    <label class="form-group-label">{{ trans('update.instructor_organization') }}</label>
                    <select name="organizations[]" class="form-control select2">
                        <option value=""></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
