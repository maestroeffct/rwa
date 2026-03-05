(function ($) {
    "use strict"

    $(document).ready(function () {

        const $bottomFixedCard = $('.post-bottom-fixed-card');
        const $bottomFixedProgress = $('.post-bottom-fixed-card__progress');

        $(document).scroll(function () {
            const scrollTop = $(this).scrollTop();

            if (scrollTop > 500) {
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
    })

    /**
     * Share Modal
     * */
    $('body').on('click', '.js-share-post', function (e) {
        e.preventDefault();

        const path = $(this).attr("data-path");

        handleBasicModal(path, shareLang, function (result, $body, $footer) {
            $footer.addClass('d-none')
        }, '', '40rem')
    });

})(jQuery)
