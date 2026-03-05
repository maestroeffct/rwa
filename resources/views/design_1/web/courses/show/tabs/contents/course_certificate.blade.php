<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#courseCertificateCollapse{{ !empty($certificate) ? $certificate->id : '' }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ trans('update.course_certificate') }}</div>
        </div>

        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#courseCertificateCollapse{{ !empty($certificate) ? $certificate->id : '' }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
        </div>
    </div>

    <div id="courseCertificateCollapse{{ !empty($certificate) ? $certificate->id : '' }}" class="accordion__collapse border-0 " role="tabpanel">

        <div class="d-flex align-items-center p-16 rounded-12 border-gray-200 bg-gray-100 mt-16">
            <div class="d-flex-center size-52 rounded-8 bg-primary-20">
                <x-iconsax-bul-clipboard-tick class="icons text-primary" width="28px" height="28px"/>
            </div>
            <div class="ml-14">
                <h6 class="font-14 font-weight-bold">{{ trans('update.course_certificate') }}</h6>
                <div class="mt-4 text-gray-500">{{ !empty($certificate) ? trans('update.you_passed_all_the_doors_and_received_this_certificate') : trans('update.if_you_pass_all_the_lessons_in_this_course_you_will_receive_this_certificate') }}</div>
            </div>
        </div>

        <div class="position-relative d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 p-16 bg-white border-gray-200 rounded-12">
            <div class="course-content-separator-with-circles">
                <span class="circle-top"></span>
                <span class="circle-bottom"></span>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-20 gap-lg-40">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-clipboard-tick class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('public.type') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ trans('update.course_certificate') }}</span>
                    </div>
                </div>

                @if(!empty($certificate))
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                            <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="16px" height="16px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('update.receive_date') }}</span>
                            <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ dateTimeFormat($certificate->created_at, 'j M Y') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-16 mt-lg-0">
                @if(!empty($certificate))
                    <a href="/panel/certificates/webinars/{{ $certificate->id }}/show" target="_blank" class="btn btn-primary btn-lg">
                        <x-iconsax-lin-direct-inbox class="icons text-white" width="16px" height="16px"/>
                        <span class="ml-4 text-white">{{ trans('home.download') }}</span>
                    </a>
                @else
                    <button type="button" class="btn btn-lg bg-gray-300 disabled can-not-download-certificate-toast">
                        <x-iconsax-lin-direct-inbox class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('home.download') }}</span>
                    </button>
                @endif
            </div>

        </div>

    </div>
</div>
