<div class="accordion bg-gray-100 border-gray-200 p-16 rounded-12 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center cursor-pointer" href="#quizCertificateCollapse{{ $quiz->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <div class="d-flex mr-8">
                <x-iconsax-lin-medal class="icons text-gray-500" width="20px" height="20px"/>
            </div>

            <div class="font-14 font-weight-bold d-block">{{ trans('public.certificate') }}</div>
        </div>

        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#quizCertificateCollapse{{ $quiz->id }}" data-parent="#{{ $accordionParent }}" role="button" data-toggle="collapse">
            <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
        </div>
    </div>

    <div id="quizCertificateCollapse{{ $quiz->id }}" class="accordion__collapse border-0 " role="tabpanel">

        <div class="d-flex align-items-center p-16 rounded-12 border-gray-200 bg-gray-100 mt-16">
            <div class="d-flex-center size-52 rounded-8 bg-primary-20">
                <x-iconsax-bul-clipboard-tick class="icons text-primary" width="28px" height="28px"/>
            </div>
            <div class="ml-14">
                <h6 class="font-14 font-weight-bold">{{ trans('update.quiz_certificate') }}</h6>
                <div class="mt-4 text-gray-500">{{ trans('update.you_will_get_this_certificate_after_passing_the_quiz', ['title' => $quiz->title]) }}</div>
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
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ trans('update.quiz_certificate') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                        <x-iconsax-lin-verify class="icons text-gray-500" width="16px" height="16px"/>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400">{{ trans('update.pass_grade') }}</span>
                        <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ $quiz->pass_mark }}/{{ $quiz->quizQuestions->sum('grade') }}</span>
                    </div>
                </div>

                @if(!empty($quiz->result))
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-32 rounded-circle bg-gray-100">
                            <x-iconsax-lin-calendar-2 class="icons text-gray-500" width="16px" height="16px"/>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400">{{ trans('update.receive_date') }}</span>
                            <span class="d-block font-12 font-weight-bold text-gray-500 mt-4">{{ dateTimeFormat($quiz->result->created_at, 'j M Y') }}</span>
                        </div>
                    </div>
                @endif

            </div>

            <div class="mt-16 mt-lg-0">
                @if(!empty($authUser) and $quiz->can_download_certificate and $hasBought)
                    <a href="/panel/quizzes/results/{{ $quiz->result->id }}/showCertificate" target="_blank" class="btn btn-primary btn-lg">
                        <x-iconsax-lin-direct-inbox class="icons text-white" width="16px" height="16px"/>
                        <span class="ml-4 text-white">{{ trans('home.download') }}</span>
                    </a>
                @else
                    <button type="button" class="btn btn-lg bg-gray-300 disabled {{ ((empty($authUser)) ? 'not-login-toast' : (!$hasBought ? 'not-access-toast' : (!$quiz->can_download_certificate ? 'can-not-download-certificate-toast' : ''))) }}">
                        <x-iconsax-lin-direct-inbox class="icons text-gray-500" width="16px" height="16px"/>
                        <span class="ml-4 text-gray-500">{{ trans('home.download') }}</span>
                    </button>
                @endif
            </div>

        </div>

    </div>
</div>
