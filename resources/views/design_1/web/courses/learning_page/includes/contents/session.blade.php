<div class="bg-white p-16 rounded-24">
    <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 mb-20 px-16">

        @if($isFinished)
            @include('design_1.web.courses.learning_page.includes.contents.session.finished')
        @elseif($isStarted)
            @include('design_1.web.courses.learning_page.includes.contents.session.in_progress')
        @else
            {{-- session_not_started --}}
            @include('design_1.web.courses.learning_page.includes.contents.session.not_started')
        @endif

    </div>

    {{-- Footer Actions And Desc --}}
    @include('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $session,
        'itemType' => 'session',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote,
    ])

</div>
