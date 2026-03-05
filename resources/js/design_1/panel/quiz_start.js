(function ($) {
    "use strict";

    var currentQuizSecond;

    function handleTimerProgress(totalSeconds, $progressBar) {
        const widthIncrement = 100 / totalSeconds;

        // Function to update the progress bar
        function updateProgressBar() {
            const currentTime = parseInt(currentQuizSecond);
            const newWidth = (totalSeconds - currentTime) * widthIncrement;
            $progressBar.css('width', newWidth + '%');
        }

        // Update the progress bar every second
        const interval = setInterval(function () {
            updateProgressBar();
            if (parseInt(currentQuizSecond) <= 0) {
                clearInterval(interval);
            }

            currentQuizSecond = currentQuizSecond - 1;
        }, 1000);
    }

    if (jQuery().startTimer) {
        const $timer = $('.timer');
        const quizSeconds = $timer.attr("data-minutes-left") * 60;
        currentQuizSecond = quizSeconds;
        const $timerProgressBar = $('.js-time-progress-bar');

        handleTimerProgress(quizSeconds, $timerProgressBar)

        $timer.startTimer({
            onComplete: function (element) {
                element.addClass('text-danger');
                $('.quiz-form form').trigger('submit');
            }
        });

    }


    var questionStep = 1;
    var currentFs, nextFs, previousFs; //fieldsets
    var questionCount = $('.js-quiz-question-count').val();

    function handleQuestionsProgress() {
        const percent = (questionStep / questionCount) * 100;

        const $progress = $('.quiz-holding-footer__progressbar');
        $progress.css({
            width: `${percent}%`
        })

        // Handle Question Count
        $('.js-question-count-text').html(`${questionStep}/${questionCount}`)
    }

    function handleButtonDisable() {
        const $next = $('.js-next-btn');
        const $previous = $(".js-previous-btn");

        if (questionStep >= questionCount) {
            $next.removeClass('text-primary cursor-pointer').addClass('text-gray-500')
        } else {
            $next.removeClass('text-gray-500').addClass('text-primary cursor-pointer')
        }

        if (questionStep <= 1) {
            $previous.removeClass('text-primary cursor-pointer').addClass('text-gray-500')
        } else {
            $previous.removeClass('text-gray-500').addClass('text-primary cursor-pointer')
        }

        handleQuestionsProgress();
    }

    $("body").on('click', '.js-next-btn', function () {
        currentFs = $('.question-step-' + questionStep);
        nextFs = $('.question-step-' + (questionStep + 1));

        if (nextFs.length < 1 || (questionStep + 1) > questionCount) {
            return
        }

        nextFs.show(200);
        currentFs.hide(200)

        if (questionStep < questionCount) {
            questionStep += 1;
        }

        handleButtonDisable();
    });

    $("body").on('click', '.js-previous-btn', function () {
        currentFs = $('.question-step-' + questionStep);
        previousFs = $('.question-step-' + (questionStep - 1));

        if (previousFs.length < 1 || (questionStep - 1) < 1) {
            return
        }

        previousFs.show(200);
        currentFs.hide(200);

        if (questionStep > 1) {
            questionStep--;
        }

        handleButtonDisable();

    });



    $('body').on('click', '.js-finish-btn', function (e) {
        e.preventDefault();

        var html = '<div class="px-16 pb-24 pt-16">\n' +
            '    <p class="text-center">' + quizFinishHint + '</p>\n' +
            '    <div class="mt-24 d-flex align-items-center justify-content-center">\n' +
            '        <button type="button" id="jsFinishQuiz" class="btn btn-primary">' + confirmLang + '</button>\n' +
            '        <button type="button" class="btn btn-danger ml-12 close-swl">' + cancelLang + '</button>\n' +
            '    </div>\n' +
            '</div>';

        Swal.fire({
            title: quizFinishTitle,
            html: html,
            icon: 'warning',
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: () => !Swal.isLoading(),
        })
    });

    $('body').on('click', '#jsFinishQuiz', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $('#quizHoldingForm');

        $this.addClass('loadingbar').prop('disabled', true);
        $form.trigger('submit');
    })

    $(document).ready(function () {
        handleQuestionsProgress();
    })

})(jQuery)
