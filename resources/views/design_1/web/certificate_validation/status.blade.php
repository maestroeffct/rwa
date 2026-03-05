@if(!empty($certificate))
    <div class="d-flex-center flex-column text-center mt-36">
        <div class="d-flex-center size-72 rounded-16 bg-success border-gray-4">
            <x-iconsax-bul-tick-circle class="icons text-white" width="32px" height="32px"/>
        </div>

        <h4 class="mt-12 font-14 font-weight-bold">{{ trans('update.valid_certificate') }}</h4>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.this_certificate_is_valid_with_the_following_information') }}</p>
    </div>

    <div class="w-100 mt-16 rounded-12 border-gray-200 p-16 mb-16">
        {{-- Certificate ID --}}
        <div class="d-flex align-items-center justify-content-between gap-32">
            <span class="text-gray-500">{{ trans('update.certificate_id') }}</span>
            <span class="flex-1 text-ellipsis text-right">{{ $certificate->id }}</span>
        </div>

        {{-- Type --}}{{--
        <div class="d-flex align-items-center justify-content-between mt-12">
            <span class="text-gray-500">{{ trans('public.type') }}</span>
            <span class="">{{ $certificate->id }}</span>
        </div>--}}

        {{-- Student --}}
        <div class="d-flex align-items-center justify-content-between gap-32 mt-12">
            <span class="text-gray-500">{{ trans('quiz.student') }}</span>
            <span class="flex-1 text-ellipsis text-right">{{ $certificate->student->full_name }}</span>
        </div>

        {{-- Achieve Date --}}
        <div class="d-flex align-items-center justify-content-between gap-32 mt-12">
            <span class="text-gray-500">{{ trans('update.achieve_date') }}</span>
            <span class="flex-1 text-ellipsis text-right">{{ dateTimeFormat($certificate->created_at, 'j F Y') }}</span>
        </div>

        {{-- Course --}}
        <div class="d-flex align-items-center justify-content-between gap-32 mt-12">
            <span class="text-gray-500">{{ trans('update.course') }}</span>
            <span class="flex-1 text-ellipsis text-right">{{ $webinarTitle }}</span>
        </div>

    </div>
@else
    <div class="d-flex-center flex-column text-center py-32">
        <div class="d-flex-center size-72 rounded-16 bg-danger border-gray-4">
            <x-iconsax-bul-close-circle class="icons text-white" width="32px" height="32px"/>
        </div>

        <h4 class="mt-12 font-14 font-weight-bold">{{ trans('update.invalid_certificate') }}</h4>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.this_certificate_is_not_valid._please_try_a_new_one...') }}</p>
    </div>
@endif
