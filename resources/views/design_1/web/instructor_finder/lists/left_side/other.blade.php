<div class="instructor-finder__filters-card position-relative bg-white p-16 rounded-24 mt-28">

    @php
        $meetingTypes = request()->get('meeting_type', []);
        $selectedPopulations = request()->get('population', []);

        if (!is_array($meetingTypes)) {
            $meetingTypes = [];
        }

        if (!is_array($selectedPopulations)) {
            $selectedPopulations = [];
        }
    @endphp

    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersMeetingType" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.meeting_type') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersMeetingType" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFiltersMeetingType" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            @foreach(['in_person', 'online'] as $meetingType)
                <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                    <input type="checkbox" name="meeting_type[]" value="{{ $meetingType }}" id="meeting_type_{{ $meetingType }}" class="custom-control-input" {{ (in_array($meetingType, $meetingTypes)) ? 'checked' : '' }}>
                    <label class="custom-control__label cursor-pointer" for="meeting_type_{{ $meetingType }}">{{ trans("update.{$meetingType}") }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersPopulation" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.population') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersPopulation" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFiltersPopulation" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            @foreach(['single', 'group'] as $population)
                <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                    <input type="checkbox" name="population[]" value="{{ $population }}" id="population_{{ $population }}" class="custom-control-input" {{ (in_array($population, $selectedPopulations)) ? 'checked' : '' }}>
                    <label class="custom-control__label cursor-pointer" for="population_{{ $population }}">{{ trans("update.{$population}") }}</label>
                </div>
            @endforeach
        </div>
    </div>


    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersPrices" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('price') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersPrices" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFiltersPrices" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            <div class="d-flex align-items-center mt-16">
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ $currency.removeCurrencyFromPrice(request()->get('min_price', 0)) }}" class="js-filters-min-price form-control input-xs bg-white text-center text-gray-500">
                </div>
                <div class="mx-4"></div>
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ $currency.removeCurrencyFromPrice(request()->get('max_price', $filterMaxPrice)) }}" class="js-filters-max-price form-control input-xs bg-white text-center text-gray-500">
                </div>
            </div>

            <div
                    class="range wrunner-value-bottom mt-16"
                    id="priceRange"
                    data-minLimit="{{ removeCurrencyFromPrice(request()->get('min_price', 0)) }}"
                    data-maxLimit="{{ removeCurrencyFromPrice(request()->get('max_price', $filterMaxPrice)) }}"
                    data-step="{{ ($filterMaxPrice < 100) ? 2 : (($filterMaxPrice < 500) ? 50 : 100) }}"
            >
                <input type="hidden" name="min_price" value="{{ removeCurrencyFromPrice(request()->get('min_price')) }}">
                <input type="hidden" name="max_price" value="{{ removeCurrencyFromPrice(request()->get('max_price')) }}">
            </div>

        </div>
    </div>

    {{-- Days --}}
    <div class="accordion py-16 border-bottom-gray-100">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersDays" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.meeting_time') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersDays" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        @php
            $days = ['saturday', 'sunday','monday','tuesday','wednesday','thursday','friday'];

            $requestDays = request()->get('day');
            if (!is_array($requestDays)) {
                $requestDays = [$requestDays];
            }
        @endphp

        <div id="sidebarFiltersDays" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">

            @foreach($days as $day)
                <div class="custom-control custom-checkbox {{ $loop->first ? 'mt-16' : 'mt-12' }}">
                    <input type="checkbox" name="day[]" value="{{ $day }}" id="day_{{ $day }}" class="custom-control-input" {{ (in_array($day, $requestDays)) ? 'checked' : '' }}>
                    <label class="custom-control__label cursor-pointer" for="day_{{ $day }}">{{ trans('panel.'.$day) }}</label>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Time Range --}}
    <div class="accordion pt-16">
        <div class="accordion__title d-flex align-items-center justify-content-between">
            <div class="instructor-finder__filters-title font-14 font-weight-bold text-dark cursor-pointer" href="#sidebarFiltersTimeRange" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                {{ trans('update.time_range') }}
            </div>

            <span class="collapse-arrow-icon d-flex cursor-pointer" href="#sidebarFiltersTimeRange" data-parent="#sidebarFiltersAccordion" role="button" data-toggle="collapse">
                <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
            </span>
        </div>

        <div id="sidebarFiltersTimeRange" class="accordion__collapse show pt-0 mt-0 border-0 " role="tabpanel">
            <div class="d-flex align-items-center mt-16">
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ request()->get('min_time', 0) }}" class="js-filters-min-time form-control input-xs bg-white text-center text-gray-500">
                </div>
                <div class="mx-4"></div>
                <div class="form-group mb-0">
                    <input type="tel" readonly value="{{ request()->get('min_time', 23) }}" class="js-filters-max-time form-control input-xs bg-white text-center text-gray-500">
                </div>
            </div>

            <div
                    class="range wrunner-value-bottom mt-16"
                    id="timeRange"
                    data-minLimit="{{ request()->get('min_time', 0) }}"
                    data-maxLimit="{{ request()->get('max_time', 23) }}"
                    data-step="1"
            >
                <input type="hidden" name="min_time" value="{{ request()->get('min_time') }}">
                <input type="hidden" name="max_time" value="{{ request()->get('max_time') }}">
            </div>
        </div>
    </div>

</div>
