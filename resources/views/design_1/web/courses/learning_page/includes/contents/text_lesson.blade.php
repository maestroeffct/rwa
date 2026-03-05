<div class="bg-white p-16 rounded-24">
    @if(!empty($textLesson->image))
        <div class="learning-page__file-player-card mb-16 bg-gray-100">
            <img src="{{ $textLesson->image }}" alt="" class="img-cover">
        </div>
    @endif

    {{-- Footer Actions And Desc --}}
    @include('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $textLesson,
        'itemType' => 'text_lesson',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote,
    ])

</div>
