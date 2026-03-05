(function ($) {
    "use strict"

    var $mainContent = $('#mainContent');

    $(document).ready(function () {
        handleDefaultItemLoaded();

        handleTrackSpentTime();
    });

    function handleTrackSpentTime() {
        const path = `${courseLearningUrl}/track-time`;

        setInterval(function () {
            $.post(path, {}, function (result) {
                if (result && result.force_reload) {
                    window.location.reload();
                }
            })
        }, 10000)
    }

    function contentEmptyStateHtml() {
        const html = `<div class="bg-white rounded-24 p-16">
            <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160">
                <div class="">
                    <img src="/assets/design_1/img/courses/learning_page/empty_state.svg" alt="" class="img-fluid" width="285px" height="212px">
                </div>

                <h3 class="mt-12 font-16">${learningPageEmptyContentTitleLang}</h3>
                <div class="mt-8 text-gray-500">${learningPageEmptyContentHintLang}</div>
            </div>
        </div>`

        $mainContent.html(html)
    }

    function handleDefaultItemLoaded() {
        const allItems = $('.js-content-tab-item');

        if (allItems && allItems.length && defaultItemType && defaultItemType !== '' && defaultItemId && defaultItemId !== '') {
            for (const item of allItems) {
                const $item = $(item);
                const type = $item.attr('data-type');
                const id = $item.attr('data-id');

                if (type === defaultItemType && id === defaultItemId) {
                    $item.trigger('click');

                    activeAccordionByItem($item)
                }
            }
        } else if (allItems && loadFirstContent && loadFirstContent !== 'false') {
            if (allItems.length) {
                const item = allItems[0];
                const $item = $(item);
                $item.trigger('click');

                activeAccordionByItem($item)
            } else {
                contentEmptyStateHtml();
            }
        }
    }

    function activeAccordionByItem($item) {
        const $accordion = $item.closest('.js-accordion-parent');

        if ($accordion.length) {
            const $btn = $accordion.find('.js-accordion-collapse-arrow');
            $btn.trigger('click');

            const $scroller = $('#learningPageSidebar .simplebar-content-wrapper');
            setTimeout(function () {
                $scroller.animate({
                    scrollTop: $item.offset().top - 200
                }, 500);
            }, 1500)
        }
    }

    function handleLoadingHtml() {
        const html = `<div class="bg-white rounded-24 p-16">
            <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160">
                <div class="">
                    <img src="/assets/default/img/loading.svg" alt="" class="img-fluid" width="80px" height="80px">
                </div>

                <h3 class="mt-12 font-16">${pleaseWaitLang}</h3>
                <div class="mt-8 text-gray-500">${pleaseWaitForTheContentLang}</div>
            </div>
        </div>`;

        $mainContent.html(html)
    }

    function addItemToUrlBar(itemId, itemType, extraData = {}) {
        let currentPath = window.location.pathname;
        let newPath = currentPath.replace('/forum', ''); // when i forum page

        const params = new URLSearchParams();
        params.set('type', itemType);
        params.set('item', itemId);

        if (extraData) {
            for (const key in extraData) {
                params.set(key, extraData[key]);
            }
        }

        window.history.pushState({}, '', `${newPath}?${params.toString()}`);
    }

    function handleVideoPlayer() {
        const $players = $('.js-file-player-el');

        if ($players.length) {
            for (const plyr of $players) {
                const player = new Plyr(plyr);

                // Attach moving watermark after player ready
                if (typeof window.initMovingWatermark === 'function' && typeof window.wmUserName !== 'undefined' && window.wmEnabled !== false) {
                    try {
                        window.initMovingWatermark(player, {
                            userName: window.wmUserName,
                            avatarUrl: window.wmUserAvatar,
                            mode: (window.wmMode || 'fade'),
                            opacity: (typeof window.wmOpacity !== 'undefined') ? window.wmOpacity : undefined,
                            size: (window.wmSize || '1'),
                            data: (window.wmData || 'student'),
                        });
                    } catch (e) {}
                }
            }
        }
    }

    function handleContentItemHtml(itemId, itemType, extraData = {}) {
        const path = `/course/learning/${courseSlug}/itemInfo`
        const data = {
            id: itemId,
            type: itemType,
            ...extraData,
        };

        $.post(path, data, function (result) {
            if (result.code === 200) {
                $mainContent.html(result.html);

                tippyTooltip();
                handleVideoPlayer()
            }
        }).fail(err => {
            showToast('error', oopsLang, somethingWentWrongLang);
        })

        addItemToUrlBar(itemId, itemType, extraData)
    }

    $('body').on('click', '.js-content-tab-item', function (e) {
        const $this = $(this);

        if (!$this.hasClass('active')) {
            const type = $this.attr('data-type');
            const id = $this.attr('data-id');
            const extraKey = $this.attr('data-extra-key') ?? null;
            const extraValue = $this.attr('data-extra-value') ?? null;

            let extraData = {};
            if (extraKey && extraValue) {
                extraData[extraKey] = extraValue;
            }

            $('.js-content-tab-item').removeClass('active');
            $this.addClass('active');

            $('#learningPageSidebar').removeClass('show-drawer')

            if (!$this.hasClass('js-sequence-content-error-modal')) {
                handleLoadingHtml();
                handleContentItemHtml(id, type, extraData)
            }
        }
    })

    $('body').on('click', '.js-learning-file-video-player-btn', function (e) {
        e.preventDefault();

        const $this = $(this)
        const $el = $this.closest('.js-learning-file-video-player-box');
        const fileId = $this.attr('data-id');

        handleVideoByFileId(fileId, $el, function () {

        });
    })


    /**
     * I Passed Item Toggle
     * */
    $('body').on('change', '.js-passed-item-toggle', function (e) {
        const $this = $(this);
        const courseSlug = $this.attr("data-course-slug");
        const item = $this.attr("data-item-name");
        const itemId = $this.val();
        const status = this.checked;

        const path = `/course/${courseSlug}/learningStatus`;

        const data = {
            item: item, item_id: itemId, status: status
        };

        const $percentBar = $('.js-course-learning-progress-bar-percent');
        const $percentNum = $('.js-course-learning-progress-percent');

        $.post(path, data, function (result) {
            showToast("success", result.title, result.msg);

            if (result.learning_progress_percent && $percentBar.length) {
                $percentBar.css('width', result.learning_progress_percent + '%');
                $percentNum.text(`${result.learning_progress_percent}%`)
            }

            if (result.learning_progress_percent && result.learning_progress_percent >= 100) {
                handleCourseCompletedModal()
            }

        }).fail(err => {
            $this.prop('checked', !status);
            showToast('error', oopsLang, somethingWentWrongLang);
        });
    });

    function handleCourseCompletedModal() {
        const path = `/course/${courseSlug}/learning-status-completed-modal`;

        handleBasicModal(path, courseCompletedLang, function (result, $body, $footer) {
            $footer.remove()
        }, '', '37rem')
    }

    /******
     * Personal Note
     * ****/
    $('body').on('click', '.js-add-personal-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemType = $this.attr('data-item-type')
        const itemId = $this.attr('data-item-id')

        const path = `${courseLearningUrl}/personal-note/get-form?item_id=${itemId}&item_type=${itemType}`;

        handleBasicModal(path, newCourseNoteLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="js-save-personal-note btn btn-primary">${saveNoteLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '42rem')
    })

    $('body').on('click', '.js-save-personal-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-personal-note-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path)
    })

    $('body').on('click', '.js-edit-personal-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemType = $this.attr('data-item-type')
        const itemId = $this.attr('data-item-id')

        const path = `${courseLearningUrl}/personal-note/get-details?item_id=${itemId}&item_type=${itemType}`;

        handleBasicModal(path, courseNoteLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <span class="d-block font-12 text-gray-500">${submittedOnLang}</span>
                            <span class="d-block font-12 text-gray-500 font-weight-bold mt-2">${result.submitted_on}</span>
                        </div>

                        <div class="d-flex align-items-center gap-24">
                            <a href="/course/personal-notes/${result.note_id}/delete" class="delete-action text-danger">${deleteNoteLang}</a>

                            <button type="button" class="js-add-personal-note btn btn-primary"
                                    data-item-id="${result.item_id}"
                                    data-item-type="${result.item_type}"
                            >${editLang}</button>
                        </div>
                    </div>`;

            $footer.html(footerHtml);

        }, '', '42rem')
    })

    /************
     * Session
     *
     * */
    $('body').on('click', '.js-check-again-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemId = $this.attr('data-id')
        const itemType = "session";

        handleLoadingHtml();
        handleContentItemHtml(itemId, itemType)
    })


    /************
     * Sequence Content
     * */
    $('body').on('click', '.js-sequence-content-error-modal', function (e) {
        e.preventDefault();
        const $this = $(this);
        const type = $this.attr('data-type');
        const id = $this.attr('data-id');

        const path = `/course/learning/${courseSlug}/itemSequenceContentInfo?type=${type}&item=${id}`;

        handleBasicModal(path, accessDeniedLang, function (result, $body, $footer) {
            const footerHtml = `<div class="">
                <h5 class="font-14 text-black">${noteLang}</h5>
                <p class="mt-4 font-12 text-gray-500">${accessDeniedModalFooterHintLang}</p>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });


    /************
     * Assignment Conversation
     * */
    $('body').on('click', '.js-send-assignment-conversation', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-assignment-conversation-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path);
    });

    $('body').on('click', '.js-show-submit-rate', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, rateAssignmentLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <div class="font-weight-bold text-black">${result.pass_grade}</div>
                    <div class="mt-4 font-12 text-gray-500">${passGradeLang}</div>
                </div>
                <button type="button" class="js-submit-rate-btn btn btn-primary btn-lg">${submitGradeLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-submit-rate-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-assigment-submit-grade-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path);
    });


    /************
     * Forum
     * */
    $('body').on('click', '.js-forum-pin-toggle', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');

        if ($this.hasClass('text-gray-500')) {
            $this.removeClass('text-gray-500').addClass('text-warning');
        } else {
            $this.removeClass('text-warning').addClass('text-gray-500');
        }

        $.post(path, {}, function (result) {
            if (result.code === 200) {
                showToast('success', result.title, result.msg);
            }
        }).fail(function () {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    });

    $('body').on('click', '.js-forum-question-action', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');
        const title = $this.attr('data-title');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">

                <button type="button" class="js-submit-forum-question-btn btn btn-primary">${submitQuestionLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-submit-forum-question-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-forum-question-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path);
    });

    $('body').on('click', '.js-mark-as-resolved-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');
        const title = $this.attr('data-title');
        const confirm = $this.attr('data-confirm');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">

                <button type="button" class="js-confirm-mark-as-resolved btn btn-primary" data-action="${path}">${confirm}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-confirm-mark-as-resolved', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');

        $this.addClass('loadingbar').prop('disabled', true);

        $.post(path, {}, function (result) {
            if (result.code === 200) {
                showToast('success', result.title, result.msg);

                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        }).fail(() => {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    })

    $('body').on('click', '.js-answer-action-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');

        loadingSwl();
        const data = {};

        $.post(path, data, function (result) {
            if (result.code === 200) {
                const html = `<div class="d-flex-center flex-column text-center my-24">
                    <h4 class="font-14">${result.title}</h4>
                    <div class="mt-8 font-12 text-gray-500">${result.msg}</div>
                </div>`;

                Swal.fire({
                    html: html,
                    showConfirmButton: false,
                    icon: 'success',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        }).fail(() => {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    })

    $('body').on('click', '.js-reply-forum-question', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form')
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path);
    })

    $('body').on('click', '.js-edit-forum-answer', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');
        const title = $this.attr('data-title');
        const confirm = $this.attr('data-confirm');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">

                <button type="button" class="js-submit-answer-update btn btn-primary">${confirm}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-submit-answer-update', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-forum-answer-form');
        const path = $form.attr('data-action');

        handleSendRequestItemForm($form, $this, path);
    })


    $('body').on('click', '.js-toggle-show-learning-page-sidebar-drawer', function (e) {
        e.preventDefault();

        const $sidebar = $('#learningPageSidebar');
        $sidebar.toggleClass('show-drawer')
    })

})(jQuery)
