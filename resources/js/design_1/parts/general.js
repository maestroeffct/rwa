(function () {
    "use strict"

    if (jQuery().summernote) {
        const $mainSummernote = $(".main-summernote");

        if ($mainSummernote.length) {
            makeSummernote($mainSummernote)
        }
    }

    // date & time piker
    resetDatePickers();

})(jQuery)
