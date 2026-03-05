<div class="row">
    {{-- Average Rating --}}
    <div class="col-12 col-lg-3 mt-24">
        <div class="bg-white rounded-24 w-100">
            <div class="d-flex-center flex-column text-center pt-64 pb-28">
                <div class="d-flex-center size-64 rounded-12 bg-primary-40">
                    <x-iconsax-bul-star-1 class="icons text-primary" width="32px" height="32px"/>
                </div>
                <h4 class="font-24 mt-12">{{ round($courseRate, 2) }}</h4>
                <p class="mt-4 text-gray-500">{{ trans('update.average_rating') }}</p>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-16 pb-20 px-16 border-top-gray-100">
                <span class="text-gray-500">{{ trans('update.total_rates') }}</span>
                <span class="text-dark font-weight-bold">{{ round($courseRateCount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Quizzes --}}
    <div class="col-12 col-lg-3 mt-24">
        <div class="bg-white rounded-24 w-100">
            <div class="d-flex-center flex-column text-center pt-64 pb-28">
                <div class="d-flex-center size-64 rounded-12 bg-primary-40">
                    <x-iconsax-bul-message-question class="icons text-primary" width="32px" height="32px"/>
                </div>
                <h4 class="font-24 mt-12">{{ $course->quizzes->count() }}</h4>
                <p class="mt-4 text-gray-500">{{ trans('quiz.quizzes') }}</p>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-16 pb-20 px-16 border-top-gray-100">
                <span class="text-gray-500">{{ trans('quiz.average_grade') }}</span>
                <span class="text-dark font-weight-bold">{{ round($quizzesAverageGrade, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Assignments --}}
    <div class="col-12 col-lg-3 mt-24">
        <div class="bg-white rounded-24 w-100">
            <div class="d-flex-center flex-column text-center pt-64 pb-28">
                <div class="d-flex-center size-64 rounded-12 bg-primary-40">
                    <x-iconsax-bul-clipboard-text class="icons text-primary" width="32px" height="32px"/>
                </div>
                <h4 class="font-24 mt-12">{{ $course->assignments->count() }}</h4>
                <p class="mt-4 text-gray-500">{{ trans('update.assignments') }}</p>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-16 pb-20 px-16 border-top-gray-100">
                <span class="text-gray-500">{{ trans('quiz.average_grade') }}</span>
                <span class="text-dark font-weight-bold">{{ round($assignmentsAverageGrade, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Forum Messages --}}
    <div class="col-12 col-lg-3 mt-24">
        <div class="bg-white rounded-24 w-100">
            <div class="d-flex-center flex-column text-center pt-64 pb-28">
                <div class="d-flex-center size-64 rounded-12 bg-primary-40">
                    <x-iconsax-bul-messages-1 class="icons text-primary" width="32px" height="32px"/>
                </div>
                <h4 class="font-24 mt-12">{{ $courseForumsMessagesCount }}</h4>
                <p class="mt-4 text-gray-500">{{ trans('update.forum_messages') }}</p>
            </div>

            <div class="d-flex align-items-center justify-content-between pt-16 pb-20 px-16 border-top-gray-100">
                <span class="text-gray-500">{{ trans('update.forum_students') }}</span>
                <span class="text-dark font-weight-bold">{{ $courseForumsStudentsCount }}</span>
            </div>
        </div>
    </div>

</div>
