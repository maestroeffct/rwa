@if(!empty($pendingCertificates) and count($pendingCertificates))
    <div class="mt-28">
        <h3 class="font-16 font-weight-bold">{{ trans('update.potential_certificates') }}</h3>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.you_have_potential_to_get_the_following_certificates_according_to_your_activities') }}</p>

        <div class="position-relative mt-16">
            <div class="swiper-container js-make-swiper pending-certificates-swiper pb-24"
                 data-item="pending-certificates-swiper"
                 data-autoplay="false"
                 data-breakpoints="1440:4.2,769:3.4,320:1.4"
            >
                <div class="swiper-wrapper py-8">
                    @foreach($pendingCertificates as $pendingCertificate)
                        <div class="swiper-slide">
                            @if($pendingCertificate->type == 'quiz')
                            <a href="/panel/quizzes/{{ $pendingCertificate->id }}/start" target="_blank" class="d-block text-decoration-none">
                            @else
                            <a href="{{ $pendingCertificate->getLearningPageUrl() }}" target="_blank" class="d-block text-decoration-none">
                            @endif
                                <div class="bg-white p-20 rounded-24">
                                    <div class="d-flex align-items-center justify-content-between">

                                        @if($pendingCertificate->type == 'quiz')
                                                @if(!empty($pendingCertificate->icon))
                                                 <div class="d-flex-center size-64 rounded-12">
                                                    <img src="{{ $pendingCertificate->icon }}" class="img-cover rounded-12">
                                                @else
                                                 <div class="d-flex-center size-64 bg-primary-30 rounded-12">
                                                    <x-iconsax-bul-clipboard-tick class="icons text-white" width="32px" height="32px"/>
                                                @endif
                                            </div>
                                        @else

                                            @php
                                                $percent = $pendingCertificate->getProgress(true);
                                            @endphp

                                            <div class="js-pending-certificate-chart d-flex-center size-64" data-id="courseChart_{{ $pendingCertificate->id }}" data-percent="{{ round($percent, 1) }}">
                                                <canvas id="courseChart_{{ $pendingCertificate->id }}" width="64px" height="64px"></canvas>
                                            </div>
                                        @endif

                                        @if($pendingCertificate->type == 'quiz')
                                            <div class="d-flex align-items-center ml-8 cursor-pointer">
                                                <span class="font-12 text-primary mr-4">{{ trans('update.take_quiz') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center ml-8 cursor-pointer">
                                                <span class="font-12 text-primary mr-4">{{ trans('update.continue_learning') }}</span>
                                                <x-iconsax-lin-arrow-right class="icons text-primary" width="16px" height="16px"/>
                                            </div>
                                        @endif
                                    </div>

                                    <h5 class="mt-12 font-14 font-weight-bold text-ellipsis text-dark">{{ $pendingCertificate->title }}</h5>

                                    <p class="mt-4 font-12 text-gray-500 text-ellipsis">
                                        @if($pendingCertificate->type == 'quiz')
                                            {{ trans('update.take_this_quiz_to_get_the_certificate') }}
                                        @else
                                            {{ trans('update.complete_the_course_to_get_certificate') }}
                                        @endif
                                    </p>

                                    @if($pendingCertificate->type == 'quiz')
                                        <span class="mt-4 font-12 text-gray-500 text-ellipsis d-block">{{ $pendingCertificate->webinar->title }}</span>
                                    @else
                                        <span class="mt-4 font-12 text-gray-500 text-ellipsis d-block">{{ $pendingCertificate->title }}</span>
                                    @endif

                                    <div class="d-flex align-items-center mt-12 flex-wrap">

                                        @if($pendingCertificate->type == 'quiz')
                                            <div class="d-flex align-items-center mr-16">
                                                <x-iconsax-lin-note-2 class="icons text-gray-400" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-gray-400">{{ $pendingCertificate->quizQuestions->sum('grade') }}</span>
                                            </div>

                                            <div class="d-flex align-items-center mr-16">
                                                <x-iconsax-lin-timer-1 class="icons text-gray-400" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-gray-400">{{ $pendingCertificate->time }} {{ trans('public.min') }}.</span>
                                            </div>

                                            @if(!empty($pendingCertificate->expiry_days) and !empty($pendingCertificate->expiry_timestamp))
                                                <div class="d-flex align-items-center mr-16">
                                                    <x-iconsax-lin-calendar-2 class="icons text-gray-400" width="16px" height="16px"/>
                                                    <span class="ml-4 font-12 text-gray-400">{{ trans('update.expired_on_date', ['date' => dateTimeFormat($pendingCertificate->expiry_timestamp, 'j M Y H:i')]) }}</span>
                                                </div>
                                            @endif

                                        @else
                                            <div class="d-flex align-items-center mr-16">
                                                <x-iconsax-lin-note-2 class="icons text-gray-400" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-gray-400">{{ $pendingCertificate->getAllLessonsCount() }}</span>
                                            </div>

                                            <div class="d-flex align-items-center mr-16">
                                                <x-iconsax-lin-clock-1 class="icons text-gray-400" width="16px" height="16px"/>
                                                <span class="ml-4 font-12 text-gray-400">{{ convertMinutesToHourAndMinute($pendingCertificate->duration) }} {{ trans('home.hours') }}</span>
                                            </div>

                                        @endif

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endif
