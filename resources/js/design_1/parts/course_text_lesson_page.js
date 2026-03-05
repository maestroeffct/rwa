(function ($) {
    "use strict"

    $(document).ready(function () {

        const $bottomFixedCard = $('.text-lesson-bottom-fixed-card');
        const $bottomFixedProgress = $('.text-lesson-bottom-fixed-card__progress');

        $(document).scroll(function () {
            const scrollTop = $(this).scrollTop();

            if (scrollTop > 150) {
                $bottomFixedCard.addClass('show');
            } else {
                $bottomFixedCard.removeClass('show');
            }

            updateProgressBar()
        });


        function updateProgressBar() {
            const scrollTop = $(window).scrollTop();
            const scrollHeight = $(document).height();
            const clientHeight = $(window).height();

            // Calculate the scroll percentage
            const scrollPercentage = (scrollTop / (scrollHeight - clientHeight)) * 100;

            // Update the width of the progress bar
            $bottomFixedProgress.find('.progress-line').css('width', scrollPercentage + '%');
        }
    });



    /*$('body').on('change', '#readLessonSwitch', function (e) {
        e.preventDefault();

        const $this = $(this);
        const course_id = $this.attr('data-course-id');
        const lesson_id = $this.attr('data-lesson-id');
        const status = this.checked;

        $this.addClass('loadingbar primary').prop('disabled', true);

        const data = {
            item: 'text_lesson_id',
            item_id: lesson_id,
            status: status
        };

        $.post('/course/' + course_id + '/learningStatus', data, function (result) {

        }).catch(err => {

        });
    })*/

})(jQuery)
