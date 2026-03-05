(function ($) {
    "use strict";

    $(document).ready(function () {

        const $countryCodeSelect2 = $('.country-code-select2');
        $countryCodeSelect2.select2({
            dropdownCssClass: "country-code-select2-dropdown",
            dropdownParent: 'body',
        })
        $countryCodeSelect2.on('select2:open', function (e) {
            const width = $('.register-mobile-form-group').width();

            $('.country-code-select2-dropdown').css('min-width', (width - 6) + 'px');
        });

    })


    $('body').on('click', '#saveData', function (e) {
        e.preventDefault();

        $('#userSettingForm input[name="next_step"]').val(0);
        $(this).addClass('loadingbar primary').prop('disabled', true);

        $('#userSettingForm').trigger('submit');
    });

    function submitMetas($this, $input, name, user_id = null, metaId = null) {
        var val = $input.val();
        $input.removeClass('is-invalid');

        if (val !== '' && val !== null) {
            var data = {
                name: name,
                value: val,
                user_id: user_id
            };

            let path = `/panel/setting/metas`;
            if (metaId) {
                path = `/panel/setting/metas/${metaId}/update`;
            }

            $.post(path, data, function (result) {
                if (result && result.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        html: '<h3 class="font-16 text-center py-20">' + saveSuccessLang + '</h3>',
                        showConfirmButton: false,
                        width: '25rem',
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 500)
                }
            }).fail(err => {
                Swal.fire({
                    icon: 'error',
                    html: '<h3 class="font-16 text-center py-20">' + saveErrorLang + '</h3>',
                    showConfirmButton: false,
                    width: '25rem',
                });

                $this.removeClass('loadingbar primary').prop('disabled', false);
            });
        } else {
            $input.addClass('is-invalid');
            $this.removeClass('loadingbar primary').prop('disabled', false);
        }
    }

    $('body').on('change', '.js-user-bank-input', function (e) {
        e.preventDefault();

        const $optionSelected = $(this).find("option:selected");
        const specifications = $optionSelected.attr('data-specifications')

        const $card = $('.js-bank-specifications-card');
        let html = '';

        if (specifications) {
            Object.entries(JSON.parse(specifications)).forEach(([index, item], key) => {

                html += '<div class="form-group">\n' +
                    '         <label class="form-group-label bg-white">' + item + '</label>\n' +
                    '         <input type="text" name="bank_specifications[' + index + ']" value="" class="form-control bg-white"/>\n' +
                    ' </div>'
            })
        }

        $card.html(html);
    });

    $('body').on('change', '#profileImage', function (event) {
        const $this = $(this);

        const [file] = event.target.files;

        if (file) {
            $('#userProfileImage').attr('src', URL.createObjectURL(file));
        }
    })

    $('body').on('change', '#profileSecondaryImageInput', function (event) {
        const $this = $(this);

        const [file] = event.target.files;

        if (file) {
            const path = URL.createObjectURL(file);
            const img = `<img src="${path}" alt="" class="img-cover rounded-15">`
            $('#profileSecondaryImageBox').html(img)

        }
    })

    $('body').on('change', '#signatureImageInput', function (event) {
        const $this = $(this);

        const [file] = event.target.files;

        if (file) {
            const path = URL.createObjectURL(file);
            const img = `<img src="${path}" alt="" class="img-cover rounded-15">`
            $('#signatureImageBox').html(img)
        }
    })

    $('body').on('change', '#coverImageInput', function (event) {
        const $this = $(this);

        const [file] = event.target.files;

        if (file) {
            const path = URL.createObjectURL(file);
            const img = `<img src="${path}" alt="" class="img-cover rounded-15">`
            $('#profileCoverBox').html(img)
        }
    })

    $('body').on('change', '#identity_scan, #certificate', function (event) {
        const $this = $(this);

        const [file] = event.target.files;

        if (file) {
            const value = this.value;
            const splited = value.split('\\');

            $this.closest('.custom-input-file').find('.js-file-name-span').text(splited[splited.length - 1])
        }
    })

    /* Education */
    function handleEducationModal(title, userId = null, educationId = null, educationValue = null) {
        const html = $('#newEducationModal').html();

        const footer = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-store-education btn btn-primary" data-user-id="${userId}" data-education-id="${educationId}">${submitRequestLang}</button>
        </div>`;

        const body = makeModalHtml(title, closeIcon, html, footer)

        Swal.fire({
            html: body,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            didOpen: function () {
                $('.js-custom-modal #new_education_val').val(educationValue ?? '')
            },
        })
    }

    $('body').on('click', '.js-add-education', function (e) {
        e.preventDefault();

        let user_id = null;

        if ($('input#userId').length) {
            user_id = $('input#userId').val();
        }

        handleEducationModal(newEducationLang, user_id);
    })

    $('body').on('click', '.js-edit-education', function (e) {
        e.preventDefault();
        const $this = $(this);
        const userId = $this.attr('data-user-id');
        const educationId = $this.attr('data-education-id');
        const educationValue = $this.closest('.js-education-card').find('.js-education-value').text();

        handleEducationModal(editEducationLang, userId, educationId, educationValue);
    })

    $('body').on('click', '.js-store-education', function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.addClass('loadingbar primary').prop('disabled', true);
        const $input = $('.js-custom-modal #new_education_val');

        let userId = $this.attr('data-user-id');
        let educationId = $this.attr('data-education-id');

        if (!userId || userId === "null") {
            userId = null;
        }

        if (!educationId || educationId === "null") {
            educationId = null;
        }

        submitMetas($this, $input, 'education', userId, educationId)
    });

    /* Experiences */
    function handleExperienceModal(title, userId = null, experienceId = null, experienceValue = null) {
        const html = $('#newExperienceModal').html();

        const footer = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-store-experience btn btn-primary" data-user-id="${userId}" data-experience-id="${experienceId}">${submitRequestLang}</button>
        </div>`;

        const body = makeModalHtml(title, closeIcon, html, footer)

        Swal.fire({
            html: body,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            didOpen: function () {
                $('.js-custom-modal #new_experience_val').val(experienceValue ?? '')
            },
        })
    }

    $('body').on('click', '.js-add-experience', function (e) {
        e.preventDefault();

        let user_id = null;

        if ($('input#userId').length) {
            user_id = $('input#userId').val();
        }

        handleExperienceModal(newExperienceLang, user_id);
    })

    $('body').on('click', '.js-edit-experience', function (e) {
        e.preventDefault();
        const $this = $(this);
        const userId = $this.attr('data-user-id');
        const experienceId = $this.attr('data-experience-id');
        const experienceValue = $this.closest('.js-experience-card').find('.js-experience-value').text();

        handleExperienceModal(editExperienceLang, userId, experienceId, experienceValue);
    })

    $('body').on('click', '.js-store-experience', function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.addClass('loadingbar primary').prop('disabled', true);
        const $input = $('.js-custom-modal #new_experience_val');

        let userId = $this.attr('data-user-id');
        let experienceId = $this.attr('data-experience-id');

        if (!userId || userId === "null") {
            userId = null;
        }

        if (!experienceId || experienceId === "null") {
            experienceId = null;
        }

        submitMetas($this, $input, 'experience', userId, experienceId)
    });

    /* files & Attachments */
    function handleAttachmentModal(path, title) {
        handleBasicModal(path, title, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="js-save-attachment btn btn-sm btn-primary">${saveLang}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-8">${closeLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '40rem')
    }

    $('body').on('click', '.js-add-attachment', function (e) {
        e.preventDefault();
        const userId = $(this).attr("data-user") ?? null;
        const path = "/panel/setting/attachments/get-form" + (userId ? `?user_id=${userId}` : '');

        handleAttachmentModal(path, newAttachmentLang);
    })

    $('body').on('click', '.js-edit-attachment', function (e) {
        e.preventDefault();
        const path = $(this).attr('data-path');

        handleAttachmentModal(path, editAttachmentLang);
    })

    $('body').on('click', '.js-save-attachment', function (e) {
        e.preventDefault();

        const $this = $(this);
        let form = $this.closest('.js-custom-modal').find('.js-attachment-form');

        handleSendRequestItemForm(form, $this)
    });

})(jQuery)
