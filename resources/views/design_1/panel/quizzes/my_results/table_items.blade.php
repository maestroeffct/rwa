<tr>
    <td class="text-left">
        <div class="user-inline-avatar d-flex align-items-center">
            <div class="size-48 bg-gray-200 rounded-circle">
                <img src="{{ $quizResult->quiz->creator->getAvatar() }}" class="js-avatar-img img-cover rounded-circle" alt="">
            </div>
            <div class=" ml-8">
                <span class="d-block">{{ $quizResult->quiz->creator->full_name }}</span>
                <span class="mt-4 font-12 text-gray-500 d-block">{{ $quizResult->quiz->creator->email }}</span>
            </div>
        </div>
    </td>

    <td class="text-left">
        <span class="d-block">{{ $quizResult->quiz->title }}</span>
        <span class="font-12 text-gray-500 d-block">{{ $quizResult->quiz->webinar->title }}</span>
    </td>

    <td class="text-center">{{ $quizResult->quiz->quizQuestions->sum('grade') }}</td>

    <td class="text-center">{{ $quizResult->user_grade }}</td>

    <td class="text-center">
        <span class="d-inline-flex-center px-8 py-6 rounded-8 font-12 text-{{ ($quizResult->status == 'passed') ? 'success' : ($quizResult->status == 'waiting' ? 'warning' : 'danger') }} bg-{{ ($quizResult->status == 'passed') ? 'success' : ($quizResult->status == 'waiting' ? 'warning' : 'danger') }}-30">
            {{ trans('quiz.'.$quizResult->status) }}
        </span>

        @if($quizResult->status =='failed' and $quizResult->can_try)
            <span class="d-block mt-4 font-12 text-gray-500">{{ trans('quiz.quiz_chance_remained',['count' => $quizResult->count_can_try]) }}</span>
        @endif
    </td>

    <td class="text-center">{{ dateTimeFormat($quizResult->created_at,'j M Y H:i')}}</td>

    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    @if((!$quizResult->can_try and $quizResult->status != 'waiting') or ($quizResult->status == 'passed'))
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/results/{{ $quizResult->id }}/details" target="_blank" class="">{{ trans('public.view_answers') }}</a>
                        </li>
                    @endif

                    @if($quizResult->status != 'passed')
                        @if($quizResult->can_try)
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/quizzes/{{ $quizResult->quiz->id }}/overview" class="">{{ trans('public.try_again') }}</a>
                            </li>
                        @endif
                    @endif

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="{{ $quizResult->quiz->webinar->getUrl() }}" class="">{{ trans('webinars.webinar_page') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
