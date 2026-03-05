@foreach($quizQuestions as $key => $question)

    <fieldset class="question-step question-step-{{ $key + 1 }}">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="font-weight-bold font-16">{{ $question->title }}</h3>

            @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
                @php
                    $userGrade = (!empty($userAnswers[$question->id]) and !empty($userAnswers[$question->id]["grade"])) ? $userAnswers[$question->id]["grade"] : 0;
                @endphp

                <div class="d-flex-center font-12 {{ $userGrade == 0 ? 'text-danger' : ($userGrade < $question->grade ? 'text-warning' : 'text-success')  }}">
                    <x-iconsax-lin-verify class="icons" width="16px" height="16px"/>
                    <span class="ml-4">{{ $userGrade }}/{{ $question->grade }}</span>
                </div>
            @else
                @php
                    $userAnswerCorrectly = false;

                    foreach($question->quizzesQuestionsAnswers as $key => $answer) {
                        if($answer->correct and (!empty($userAnswers[$question->id]) and (int)$userAnswers[$question->id]["answer"] ===  $answer->id)) {
                            $userAnswerCorrectly = true;
                        }
                    }
                @endphp

                <div class="d-flex-center font-12 {{ $userAnswerCorrectly ? 'text-success' : 'text-danger' }}">
                    <x-iconsax-lin-verify class="icons" width="16px" height="16px"/>
                    <span class="ml-4">{{ $question->grade }}</span>
                </div>
            @endif

        </div>

        @if(!empty($question->image) or !empty($question->video))
            <div class="quiz-question-media-card rounded-16 my-32">
                @if(!empty($question->image))
                    <img src="{{ $question->image }}" class="img-cover rounded-16" alt="">
                @else
                    <video id="questionVideo{{ $question->id }}" class="js-init-plyr-io plyr-io-video" oncontextmenu="return false;" controlsList="nodownload" controls preload="auto" width="100%" data-setup='{"fluid": true}'>
                        <source src="{{ $question->video }}" type="video/mp4"/>
                    </video>
                @endif
            </div>
        @endif


        @if($question->type === \App\Models\QuizzesQuestion::$descriptive)
            <div class="form-group mt-24">
                <label class="form-group-label">{{ trans('update.your_answer') }}</label>
                <textarea name="question[{{ $question->id }}][answer]" rows="10" disabled class="form-control">{{ (!empty($userAnswers[$question->id]) and !empty($userAnswers[$question->id]["answer"])) ? $userAnswers[$question->id]["answer"] : '' }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-group-label">{{ trans('quiz.correct_answer') }}</label>
                <textarea rows="10" name="question[{{ $question->id }}][correct_answer]" @if(empty($newQuizStart) or $newQuizStart->quiz->creator_id != $authUser->id) disabled @endif class="form-control bg-gray-100">{{ $question->correct }}</textarea>
            </div>

            @if(!empty($newQuizStart) and $newQuizStart->quiz->creator_id == $authUser->id)
                <div class="form-group">
                    <label class="form-group-label">{{ trans('quiz.grade') }}</label>
                    <input type="text" name="question[{{ $question->id }}][grade]" value="{{ (!empty($userAnswers[$question->id]) and !empty($userAnswers[$question->id]["grade"])) ? $userAnswers[$question->id]["grade"] : 0 }}" class="form-control">
                </div>
            @endif
        @else
            <div class="question-multi-answers mt-24">
                @foreach($question->quizzesQuestionsAnswers as $key => $answer)
                    @php
                        $isUserAnswer = (!empty($userAnswers[$question->id]) and (int)$userAnswers[$question->id]["answer"] ===  $answer->id);
                    @endphp

                    <div class="answer-item">
                        @if($answer->correct)
                            <div class="correct-answer d-flex align-items-center">
                                <div class="d-flex-center bg-primary py-4 pr-8 pl-4 rounded-32">
                                    <x-iconsax-bul-tick-circle class="icons text-white" width="20px" height="20px"/>
                                    <span class="font-12 text-white ml-4">{{ trans('quiz.correct') }}</span>
                                </div>

                                @if($isUserAnswer)
                                    <div class="d-flex-center bg-success py-4 pr-8 pl-4 rounded-32 ml-8">
                                        <x-iconsax-bul-tick-circle class="icons text-white" width="20px" height="20px"/>
                                        <span class="font-12 text-white ml-4">{{ !empty($newQuizStart) ? trans('quiz.student_answer') : trans('update.your_answer') }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($isUserAnswer and !$answer->correct)
                            <div class="correct-answer d-flex-center bg-danger py-4 pr-8 pl-4 rounded-32">
                                <x-iconsax-bul-close-circle class="icons text-white" width="20px" height="20px"/>
                                <span class="font-12 text-white ml-4">{{ !empty($newQuizStart) ? trans('quiz.student_answer') : trans('update.your_answer') }}</span>
                            </div>
                        @endif

                        <input id="asw-{{ $answer->id }}" type="radio" disabled name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}" {{ $isUserAnswer ? 'checked' : '' }}>

                        <label for="asw-{{ $answer->id }}" class="answer-label d-flex-center text-center p-16 rounded-16 border-gray-200 cursor-pointer w-100 h-100 {{ ($isUserAnswer and !$answer->correct) ? 'is-wrong-answer' : '' }}">
                            @if(!$answer->image)
                                <span class="font-14 font-weight-bold">{{ $answer->title }}</span>
                            @else
                                <div class="image-container rounded-16">
                                    <img src="{{ url($answer->image) }}" class="img-cover rounded-16" alt="">
                                </div>
                            @endif
                        </label>

                    </div>
                @endforeach
            </div>
        @endif

    </fieldset>
@endforeach

