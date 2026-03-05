<tr>
    <td class="text-left">
        <span class="d-block">{{ $quiz->title }}</span>
        <span class="font-12 text-gray-500 d-block">
                                                @if(!empty($quiz->webinar))
                {{ $quiz->webinar->title }}
            @else
                {{ trans('panel.not_assign_any_webinar') }}
            @endif
                                </span>
    </td>

    <td class="text-center align-middle">
        {{ $quiz->quizQuestions->count() }}
        @if(($quiz->display_limited_questions and !empty($quiz->display_number_of_questions)))
            <span class="font-12 text-gray-500">({{ trans('public.active') }}: {{ $quiz->display_number_of_questions }})</span>
        @endif
    </td>

    <td class="text-center align-middle">{{ $quiz->time }}</td>

    <td class="text-center align-middle">{{ $quiz->quizQuestions->sum('grade') }}</td>

    <td class="text-center align-middle">{{ $quiz->pass_mark }}</td>

    <td class="text-center align-middle">
        <span class="d-block">{{ $quiz->quizResults->pluck('user_id')->count() }}</span>

        @if(!empty($quiz->userSuccessRate) and $quiz->userSuccessRate > 0)
            <span class="font-12 text-gray-500 d-block">{{ $quiz->userSuccessRate }}% {{ trans('quiz.passed')  }}</span>
        @endif
    </td>

    <td class="text-center">
        @if($quiz->status === \App\Models\Quiz::ACTIVE)
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('admin/main.active') }}</span>
        @else
            <span class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('admin/main.inactive') }}</span>
        @endif
    </td>

    <td class="text-center align-middle">{{ dateTimeFormat($quiz->created_at, 'j M Y H:i') }}</td>

    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    @can('panel_quizzes_create')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/{{ $quiz->id }}/edit" class="">{{ trans('public.edit') }}</a>
                        </li>
                    @endcan

                    @can('panel_quizzes_delete')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/{{ $quiz->id }}/delete" data-item-id="1" class="d-flex align-items-center w-100 px-16 py-8 btn-transparent text-danger delete-action">{{ trans('public.delete') }}</a>
                        </li>
                    @endcan

                </ul>
            </div>
        </div>

    </td>

</tr>
