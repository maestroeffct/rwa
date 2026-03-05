<tr>
    {{-- Student --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            <div class="d-flex-center rounded-circle bg-gray-100  size-48 ">
                <img src="{{ $quizResult->user->getAvatar(48) }}" alt="" class="img-fluid rounded-circle">
            </div>

            <div class="ml-12">
                <div class="">{{ $quizResult->user->full_name }}</div>

                @if(!empty($quizResult->user->email))
                    <div class="mt-4 font-12 text-gray-500">{{ $quizResult->user->email }}</div>
                @endif
            </div>
        </div>
    </td>

    {{-- Quiz --}}
    <td class="text-left">
        <div class="d-flex align-items-center">
            @if(!empty($quizResult->quiz->icon))
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <img src="{{ $quizResult->quiz->icon }}" alt="" class="img-fluid">
                </div>
            @else
                <div class="d-flex-center size-48 rounded-circle bg-gray-100">
                    <x-iconsax-bul-clipboard-tick class="icons text-primary" width="24px" height="24px"/>
                </div>
            @endif

            <div class="ml-12">
                <div class="">{{ $quizResult->quiz->title }}</div>

                @if(!empty($quizResult->quiz->webinar))
                    <div class="mt-4 font-12 text-gray-500">{{ $quizResult->quiz->webinar->title }}</div>
                @endif
            </div>
        </div>
    </td>

    {{-- Total Grade --}}
    <td class="text-center">
        <span class="">{{ $quizResult->quiz->total_mark ?? '-' }}</span>
    </td>

    {{-- Pass Grade --}}
    <td class="text-center">
        <span class="">{{ $quizResult->quiz->pass_mark ?? '-' }}</span>
    </td>

    {{-- Student Grade --}}
    <td class="text-center">
        <span class="">{{ $quizResult->user_grade ?? '-' }}</span>
    </td>

    {{-- Attempts --}}
    <td class="text-center">
        <span class="">{{ !empty($quizResult->quiz->attempt) ? $quizResult->quiz->attempt : '-' }}</span>
    </td>

    {{-- Date --}}
    <td class="text-center">
        <div class="text-center">
            <span class="d-block">{{ dateTimeFormat($quizResult->created_at, 'j M Y H:i') }}</span>
            <span class="d-block mt-2 font-12 text-gray-500">{{ dateTimeFormat($quizResult->created_at, 'j M Y H:i', 1) }}</span>
        </div>
    </td>

    {{-- Status --}}
    <td class="text-center">
        @if($quizResult->status == \App\Models\QuizzesResult::$passed)
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-success-30 font-12 text-success">{{ trans('quiz.passed') }}</div>
        @elseif($quizResult->status == \App\Models\QuizzesResult::$failed)
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-danger-30 font-12 text-danger">{{ trans('quiz.failed') }}</div>
        @else
            <div class="d-inline-flex-center px-8 py-6 rounded-8 bg-warning-30 font-12 text-warning">{{ trans('update.pending_review') }}</div>
        @endif
    </td>

    {{-- Actions --}}
    <td class="text-right">

        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
            </button>

            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                <ul class="my-8">

                    @can('panel_quizzes_create')
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/{{ $quizResult->quiz_id }}/edit" class="">{{ trans('quiz.edit_quiz') }}</a>
                        </li>
                    @endcan

                    @if($quizResult->status != \App\Models\QuizzesResult::$waiting)
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/results/{{ $quizResult->id }}/details" target="_blank" class="">{{ trans('public.view') }}</a>
                        </li>
                    @endif

                    @if($quizResult->status == \App\Models\QuizzesResult::$waiting)
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/quizzes/results/{{ $quizResult->id }}/edit" target="_blank" class="">{{ trans('public.review') }}</a>
                        </li>
                    @endif

                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="/panel/quizzes/results/{{ $quizResult->id }}/delete" class="delete-action text-danger ">{{ trans('public.delete') }}</a>
                    </li>

                </ul>
            </div>
        </div>

    </td>

</tr>
