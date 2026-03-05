<div class="d-flex-center flex-column text-center my-16">
    <div class="">
        <img src="/assets/design_1/img/courses/learning_page/access_denied.svg" alt="" class="img-fluid" height="160px">
    </div>

    <h4 class="mt-12 font-14 text-dark">{{ trans('update.restricted_content') }}</h4>
</div>

@if(!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error']))
    <div class="position-relative ">
        @if(!empty($checkSequenceContent['all_passed_items_error']))
            <div class="d-flex align-items-center mb-32">
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-shield-tick class="icons text-primary" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h4 class="font-12 text-dark">{{ trans('update.pass_previous_part') }}</h4>
                    <div class="mt-4 font-12 text-gray-500">{{ $checkSequenceContent['all_passed_items_error'] }}</div>
                </div>
            </div>
        @endif

        @if(!empty($checkSequenceContent['all_passed_items_error']) and !empty($checkSequenceContent['access_after_day_error']))
            <div class="restricted-content-separator-dots"></div>
        @endif

        @if(!empty($checkSequenceContent['access_after_day_error']))
            <div class="d-flex align-items-center mb-32">
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-calendar-tick class="icons text-primary" width="24px" height="24px"/>
                </div>
                <div class="ml-8">
                    <h4 class="font-12 text-dark">{{ trans('update.wait_for_content_release') }}</h4>
                    <div class="mt-4 font-12 text-gray-500">{{ $checkSequenceContent['access_after_day_error'] }}</div>
                </div>
            </div>
        @endif
    </div>
@endif
