<div class="bg-white p-16 rounded-24">
    <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 mb-20 px-16">
        {{-- Not Participated --}}
        @if($quiz->not_participated)
            @include('design_1.web.courses.learning_page.includes.contents.quiz.not_participated')
        @elseif(!empty($quiz->result))
            @include('design_1.web.courses.learning_page.includes.contents.quiz.result_status')
        @endif

    </div>

    {{-- Footer Actions And Desc --}}
    @include('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $quiz,
        'itemType' => 'quiz',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote,
    ])

</div>
