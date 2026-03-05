(function ($) {
    "use strict";

    window.makeSummernote = function ($content, cardHeight = null, onChange = undefined) {
        const height = cardHeight ? cardHeight : ($content.attr('data-height') ? $content.attr('data-height') : 300);

        $content.summernote({
            dialogsInBody: true,
            tabsize: 2,
            height: height,
            placeholder: $content.attr('placeholder'),
            fontNames: [],
            callbacks: {
                onChange: onChange
            },
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['paperSize', ['paperSize']], // The Button
            ]
        });
    }

})(jQuery);
