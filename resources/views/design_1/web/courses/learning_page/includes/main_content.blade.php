<div class="learning-page__main-content" data-simplebar @if((!empty($isRtl))) data-simplebar-direction="rtl" @endif>
    <div id="mainContent" class="w-100 h-100">
        @if(!empty($isForumPage))
            @include('design_1.web.courses.learning_page.includes.contents.forum.index')
        @elseif(!empty($isForumAnswersPage))
            @include('design_1.web.courses.learning_page.includes.contents.forum.answers')
        @endif
    </div>
</div>
