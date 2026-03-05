(function ($) {
    "use strict";

    $("#conversationsCard").animate({scrollTop: $('#conversationsCard').height()}, "slow");

    $('body').on('change', '#userRole', function (e) {
        e.preventDefault();

        const studentSelectInput = $('#studentSelectInput');
        const teacherSelectInput = $('#teacherSelectInput');
        const val = $(this).val();

        studentSelectInput.find('select').val('');
        teacherSelectInput.find('select').val('');

        if (val === 'student') {
            studentSelectInput.removeClass('d-none');
            teacherSelectInput.addClass('d-none');
        } else if (val === 'teacher') {
            teacherSelectInput.removeClass('d-none');
            studentSelectInput.addClass('d-none');
        } else {
            teacherSelectInput.addClass('d-none');
            studentSelectInput.addClass('d-none');
        }
    });

    $('body').on('change', '#supportType', function (e) {
        const value = $(this).val();

        $('#courseInput,#departmentInput').addClass('d-none');

        if (value === 'course_support') {
            $('#courseInput').removeClass('d-none');


        } else if (value === 'platform_support') {
            $('#departmentInput').removeClass('d-none');
        }
    })


    $('body').on('click', '.js-conversation-status', function (e) {
        const status = $(this).attr("data-status");

        if (!status || status === "all") {
            $('.js-conversation-lists').removeClass('d-none');
        } else {
            $('.js-conversation-lists').addClass('d-none');

            $(`.js-conversation-status-${status}`).removeClass('d-none');

            if (status === "replied") {
                $(`.js-conversation-status-supporter_replied`).removeClass('d-none');
            }
        }
    })

    $('body').on('click', '.js-conversation-message-attach-btn', function (e) {
        e.preventDefault();
        const $input = $(this).parent().find('input[name="attach"]');

        $input.trigger("click")
    })

})(jQuery);
