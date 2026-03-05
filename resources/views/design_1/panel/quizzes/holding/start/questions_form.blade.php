@foreach($quizQuestions as $key => $question)
    <fieldset class="question-step question-step-{{ $key + 1 }}">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="font-weight-bold font-16">{{ $question->title }}</h3>

            <div class="d-flex-center text-gray-500 font-12">
                <x-iconsax-lin-verify class="icons" width="16px" height="16px"/>
                <span class="ml-4">{{ $question->grade }}</span>
            </div>
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
            <div class="form-group mt-24 mb-0">
                <label class="form-group-label">{{ trans('update.your_answer') }}</label>
                <textarea name="question[{{ $question->id }}][answer]" rows="15" class="form-control"></textarea>
            </div>
        @else
            <div class="question-multi-answers mt-24">
                @foreach($question->quizzesQuestionsAnswers as $key => $answer)
                    <div class="answer-item">
                        <input id="asw-{{ $answer->id }}" type="radio" name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}">

                        <label for="asw-{{ $answer->id }}" class="answer-label d-flex-center text-center p-16 rounded-16 border-gray-200 cursor-pointer w-100 h-100">
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

