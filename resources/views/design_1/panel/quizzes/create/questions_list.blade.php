@if(!empty($quiz))
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between p-12 rounded-16 border-dashed border-gray-200">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                <x-iconsax-bul-stickynote class="icons text-primary" width="24px" height="24px"/>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold">{{ trans('public.questions') }}</h5>
                <p class="mt-4 text-gray-500 font-12">{{ trans('update.add_different_questions_to_the_quiz') }}</p>
            </div>
        </div>

        <div class="d-flex align-items-center mt-16 mt-lg-0">
            <div data-path="/panel/quizzes-questions/get-form?quiz={{ $quiz->id }}&type=multiple" class="js-create-new-question d-flex align-items-center text-primary cursor-pointer">
                <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                <span class="">{{ trans('quiz.add_multiple_choice') }}</span>
            </div>

            <div data-path="/panel/quizzes-questions/get-form?quiz={{ $quiz->id }}&type=descriptive" class="js-create-new-question d-flex align-items-center text-primary cursor-pointer ml-8">
                <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                <span class="">{{ trans('quiz.add_descriptive') }}</span>
            </div>
        </div>
    </div>


    {{-- Questions --}}
    @if($quizQuestions)
        <ul class="draggable-content-lists draggable-questions-lists-{{ $quiz->id }}" data-path="/panel/quizzes/{{ $quiz->id }}/order-items" data-drag-class="draggable-questions-lists-{{ $quiz->id }}">

            @foreach($quizQuestions as $question)
                <li data-id="{{ $question->id }}" class="d-flex align-items-center justify-content-between p-16 rounded-16 border-gray-200 mt-16">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48">
                            <x-iconsax-lin-task-square class="icons text-primary" width="20px" height="20px"/>
                        </div>

                        <div class="ml-8">
                            <h5 class="font-14 font-weight-bold">{{ $question->title }}</h5>
                            <p class="mt-4 text-gray-500 font-12">{{ $question->type === App\Models\QuizzesQuestion::$multiple ? trans('quiz.multiple_choice') : trans('quiz.descriptive') }} | {{ trans('quiz.grade') }} {{ $question->grade }}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">

                        <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                            <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                <x-iconsax-lin-more class="icons text-gray-500" width="20px" height="20px"/>
                            </button>

                            <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                                <ul class="my-8">

                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <button type="button" data-question-id="{{ $question->id }}" class="edit_question">{{ trans('public.edit') }}</button>
                                    </li>


                                    <li class="actions-dropdown__dropdown-menu-item">
                                        <a href="/panel/quizzes-questions/{{ $question->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div class="ml-16">
                            <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                        </div>

                    </div>
                </li>
            @endforeach

        </ul>
    @endif
@endif

@if(empty($quiz) or (empty($quizQuestions) or count($quizQuestions) < 1))
    <div class="d-flex-center flex-column border-gray-200 rounded-16 text-center w-100 h-100 p-32">
        <div class="d-flex-center size-64 rounded-12 bg-warning-30">
            <x-iconsax-bul-note-remove class="icons text-warning" width="32px" height="32px"/>
        </div>

        <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.quiz_questions') }}</h3>
        <p class="font-12 text-gray-500 mt-4">{{ trans('update.you_can_define_quiz_questions_after_saving_quiz') }}</p>
    </div>
@endif
