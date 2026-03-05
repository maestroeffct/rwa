(function ($) {
    "use strict"


    var fileVideoPlayer;

    window.convertVimeoLinkToPlay = function (path) {
        path = path.trim();

        if (path.includes('player.vimeo.com/video')) return path;

        if (!/^https?:\/\//i.test(path)) path = 'https://' + path;

        try {
            const url = new URL(path);
            if (url.hostname.replace(/^www\./, '') === 'vimeo.com') {
                const id = url.pathname.split('/').filter(Boolean).pop();
                if (/^\d+$/.test(id)) return `https://player.vimeo.com/video/${id}`;
            }
        } catch {
        }

        return path;
    }

    window.makeVideoPlayerHtml = function (path, storage, height, tagId, thumbnail = null) {
        let html = '';
        let options = {
            autoplay: false,
            preload: 'auto',
            previewThumbnails: {
                enabled: !!thumbnail,
                src: thumbnail ?? ''
            }
        };

        if (storage === 'youtube') {
            html = `<div class="plyr__video-embed w-100 h-100" id="${tagId}" data-poster="${thumbnail ?? ''}">
              <iframe
                src="${path}?origin=${siteDomain}&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=0&amp;controls=0"
                allowfullscreen
                allowtransparency
                allow="autoplay"
                class="img-cover rounded-16"
                data-poster="${thumbnail ?? ''}"
              ></iframe>
            </div>`;
            // Tighten Plyr options for YouTube
            options.clickToPlay = false;
            options.disableContextMenu = true;
            options.youtube = {
                rel: 0,
                modestbranding: 1,
                iv_load_policy: 3,
                fs: 0,
                disablekb: 1,
                playsinline: 1,
                controls: 0
            };
        } else if (storage === "vimeo") {
            let vimeoPath = convertVimeoLinkToPlay(path);

            html = `<div class="plyr__video-embed w-100 h-100" id="${tagId}" data-poster="${thumbnail ?? ''}">
              <iframe
                src="${vimeoPath}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                allowfullscreen
                allowtransparency
                allow="autoplay"
                class="img-cover rounded-16"
                data-poster="${thumbnail ?? ''}"
              ></iframe>
            </div>`;

        } else if (storage === "secure_host") {
            html = '<iframe src="' + path + '" class="img-cover bg-gray-200" frameborder="0" allowfullscreen="true" ></iframe>';
        } else if (storage === "iframe" || storage === "google_drive") {
            html = path;
        } else {
            html = `<video id="${tagId}" class="plyr-io-video" controls preload="auto" width="100%" height="${height}" data-poster="${thumbnail ?? ''}">
                <source src="${path}" type="video/mp4"/>
            </video>`;
        }

        return {
            html: html,
            options: options,
        };
    };

    window.handleVideoByFileId = function (fileId, $contentEl, callback) {

        closeVideoPlayer();

        const height = $(window).width() > 991 ? 426 : 264;

        $.post('/course/getFilePath', {file_id: fileId}, function (result) {

            if (result && result.code === 200) {
                const storage = result.storage;

                const videoTagId = 'videoPlayer' + fileId;

                const {html, options} = makeVideoPlayerHtml(result.path, storage, height, videoTagId);

                if ($contentEl) {
                    $contentEl.html(html);
                }

                if (storage !== "secure_host") {
                    fileVideoPlayer = new Plyr(`#${videoTagId}`, options);

                    // Initialize moving watermark for learning page players
                    if (typeof window.initMovingWatermark === 'function' && typeof window.wmUserName !== 'undefined' && window.wmEnabled !== false) {
                        try {
                            window.initMovingWatermark(fileVideoPlayer, {
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

                callback();
            } else {
                showToast("error", notAccessToastTitleLang, notAccessToastMsgLang);
            }
        }).fail(err => {
            showToast("error", notAccessToastTitleLang, notAccessToastMsgLang);
        });
    };

    window.closeVideoPlayer = function () {
        if (fileVideoPlayer !== undefined) {
            fileVideoPlayer.stop();
        }
    };

    window.pauseVideoPlayer = function () {
        if (fileVideoPlayer !== undefined) {
            fileVideoPlayer.pause();
        }
    };


    // ---------------------------
    // Moving Watermark Utilities
    // ---------------------------
    function ensureWatermarkStylesInjected() {
        if (document.getElementById('moving-watermark-styles')) return;

        var css = '' +
            '.moving-watermark{position:absolute;top:0;left:0;z-index:9999;pointer-events:none;opacity:.25;transition:opacity 400ms ease;}'+
            '.moving-watermark__content{display:flex;align-items:center;gap:8px;background:rgba(0,0,0,.35);color:#fff;border-radius:999px;padding:6px 10px;backdrop-filter:blur(2px);}'+
            '.moving-watermark__avatar{width:22px;height:22px;border-radius:50%;object-fit:cover;display:block}'+
            '.moving-watermark__name{font-size:12px;line-height:1;white-space:nowrap;max-width:220px;overflow:hidden;text-overflow:ellipsis;}'+
            /* Platform logo only mode overrides */
            '.moving-watermark--logo-only .moving-watermark__content{padding:4px 6px;border-radius:8px;background:rgba(0,0,0,.25);}'+
            '.moving-watermark--logo-only .moving-watermark__avatar{width:auto;height:22px;border-radius:4px;object-fit:contain;}'+
            '.moving-watermark--logo-only .moving-watermark__name{display:none;}';

        var style = document.createElement('style');
        style.id = 'moving-watermark-styles';
        style.type = 'text/css';
        style.appendChild(document.createTextNode(css));
        document.head.appendChild(style);
    }

    function getWrapperElementFromTarget(target) {
        // Accepts Plyr instance, element, or selector
        if (target && target.elements && (target.elements.container || target.elements.wrapper)) {
            return target.elements.wrapper || target.elements.container;
        }

        var $el = $(target);
        if (!$el.length && typeof target === 'string') {
            $el = $(target);
        }

        var $wrapper = $el.closest('.plyr');
        if ($wrapper.length) return $wrapper[0];
        if ($el.length) return $el[0];
        return null;
    }

    function getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    function scheduleNextMove(wmEl, wrapperEl) {
        if (!wmEl || !wrapperEl) return;

        var mode = wmEl.getAttribute('data-wm-mode') || 'fade';
        var baseOpacity = parseFloat(wmEl.getAttribute('data-wm-opacity') || '0.28');
        var scale = parseFloat(wmEl.getAttribute('data-wm-scale') || '1');

        if (mode === 'move') {
            // Move smoothly without rotation
            var wrapperRect = wrapperEl.getBoundingClientRect();
            var wmRect = wmEl.getBoundingClientRect();
            var maxX = Math.max(8, wrapperRect.width - wmRect.width - 12);
            var maxY = Math.max(8, wrapperRect.height - wmRect.height - 12);
            var x = getRandomInt(8, Math.max(8, maxX));
            var y = getRandomInt(8, Math.max(8, maxY));

            wmEl.style.transition = 'transform 800ms ease,opacity 400ms ease';
            wmEl.style.opacity = String(baseOpacity);
            wmEl.style.transform = 'translate(' + x + 'px,' + y + 'px) scale(' + scale + ')';

            var nextInMove = getRandomInt(1800, 3500);
            var idMove = window.setTimeout(function () {
                scheduleNextMove(wmEl, wrapperEl);
            }, nextInMove);
            wmEl.setAttribute('data-wm-move-timeout', String(idMove));
            return;
        }

        // Fade mode: fade out then reappear elsewhere
        wmEl.style.transition = 'opacity 400ms ease';
        wmEl.style.opacity = '0';

        var hideDelay = 420; // allow opacity transition to complete
        var id = window.setTimeout(function () {
            var wrapperRect = wrapperEl.getBoundingClientRect();
            var wmRect = wmEl.getBoundingClientRect();

            var maxX = Math.max(8, wrapperRect.width - wmRect.width - 12);
            var maxY = Math.max(8, wrapperRect.height - wmRect.height - 12);

            var x = getRandomInt(8, Math.max(8, maxX));
            var y = getRandomInt(8, Math.max(8, maxY));
            var opacity = isNaN(baseOpacity) ? Math.max(0.22, Math.min(0.38, (Math.random() * 0.16) + 0.22)) : baseOpacity;

            // Reposition instantly while hidden (no transform transition applied)
            wmEl.style.transform = 'translate(' + x + 'px,' + y + 'px) scale(' + scale + ')';

            // Fade in to configured visibility
            wmEl.style.opacity = String(opacity);

            // Schedule next cycle
            var nextIn = getRandomInt(1800, 3500);
            var id2 = window.setTimeout(function () {
                scheduleNextMove(wmEl, wrapperEl);
            }, nextIn);
            wmEl.setAttribute('data-wm-move-timeout', String(id2));
        }, hideDelay);

        wmEl.setAttribute('data-wm-move-timeout', String(id));
    }

    function cancelExistingMoveTimer(wmEl) {
        var existing = wmEl && wmEl.getAttribute('data-wm-move-timeout');
        if (existing) {
            try { window.clearTimeout(Number(existing)); } catch (e) {}
        }
    }

    function buildWatermarkElement(userName, avatarUrl) {
        var container = document.createElement('div');
        container.className = 'moving-watermark';

        var content = document.createElement('div');
        content.className = 'moving-watermark__content';

        if (avatarUrl) {
            var img = document.createElement('img');
            img.className = 'moving-watermark__avatar';
            img.src = avatarUrl;
            img.alt = 'avatar';
            content.appendChild(img);
        }

        var nameEl = document.createElement('div');
        nameEl.className = 'moving-watermark__name';
        nameEl.textContent = userName || '';
        content.appendChild(nameEl);

        var idEl = document.createElement('div');
        idEl.className = 'moving-watermark__id';
        idEl.style.fontSize = '11px';
        idEl.style.lineHeight = '1';
        idEl.style.opacity = '0.9';
        idEl.style.marginTop = '2px';
        content.appendChild(idEl);

        container.appendChild(content);
        return container;
    }

    window.initMovingWatermark = function (target, opts) {
        try {
            ensureWatermarkStylesInjected();

            var options = opts || {};
            var userName = options.userName || '';
            var avatarUrl = options.avatarUrl || '';
            var mode = options.mode || 'fade';
            var opacity = (typeof options.opacity !== 'undefined') ? Number(options.opacity) : undefined;
            var dataType = options.data || 'student';

            var wrapperEl = getWrapperElementFromTarget(target);
            if (!wrapperEl) return;

            // Avoid duplicates
            if (wrapperEl.querySelector('.moving-watermark')) return;

            // Ensure positioning context
            var computed = window.getComputedStyle(wrapperEl);
            if (computed.position === 'static') {
                wrapperEl.style.position = 'relative';
            }

            // Override content for platform selection
            if (dataType === 'platform') {
                try {
                    if (typeof window.platformName !== 'undefined' && window.platformName) {
                        userName = window.platformName;
                    }
                    // Prefer favicon over logo
                    if (typeof window.platformFavicon !== 'undefined' && window.platformFavicon) {
                        avatarUrl = window.platformFavicon;
                    } else if (typeof window.platformLogo !== 'undefined' && window.platformLogo) {
                        avatarUrl = window.platformLogo;
                    }
                } catch (e) {}
            }
            if (dataType === 'platform_logo') {
                try {
                    userName = '';
                    if (typeof window.platformLogo !== 'undefined' && window.platformLogo) {
                        avatarUrl = window.platformLogo;
                    } else if (typeof window.platformFavicon !== 'undefined' && window.platformFavicon) {
                        avatarUrl = window.platformFavicon;
                    }
                } catch (e) {}
            }
            if (dataType === 'instructor') {
                try {
                    if (typeof window.instructorName !== 'undefined' && window.instructorName) {
                        userName = window.instructorName;
                    }
                    if (typeof window.instructorAvatar !== 'undefined' && window.instructorAvatar) {
                        avatarUrl = window.instructorAvatar;
                    }
                } catch (e) {}
            }
            if (dataType === 'student_phone') {
                try {
                    var val = '';
                    if (typeof window.studentPhone !== 'undefined' && window.studentPhone) {
                        val = window.studentPhone;
                    } else if (typeof window.studentEmail !== 'undefined' && window.studentEmail) {
                        val = window.studentEmail;
                    }
                    if (val) {
                        userName = String(val);
                    }
                } catch (e) {}
            }
            if (dataType === 'student_email') {
                try {
                    var val2 = '';
                    if (typeof window.studentEmail !== 'undefined' && window.studentEmail) {
                        val2 = window.studentEmail;
                    } else if (typeof window.studentPhone !== 'undefined' && window.studentPhone) {
                        val2 = window.studentPhone;
                    }
                    if (val2) {
                        userName = String(val2);
                    }
                } catch (e) {}
            }

            var wmEl = buildWatermarkElement(userName, avatarUrl);
            // persist behavior
            wmEl.setAttribute('data-wm-mode', mode);
            if (typeof opacity === 'number' && !isNaN(opacity)) {
                wmEl.setAttribute('data-wm-opacity', String(Math.max(0, Math.min(1, opacity))));
                wmEl.style.opacity = String(Math.max(0, Math.min(1, opacity)));
            }
            // Handle size scaling
            var scale = 1;
            try {
                if (String(options.size || '1') === '2') scale = 1.25;
                if (String(options.size || '1') === '3') scale = 1.5;
            } catch (e) {}
            wmEl.setAttribute('data-wm-scale', String(scale));
            try { wmEl.style.transform = 'translate(8px,8px) scale(' + scale + ')'; } catch (e) {}
            // Add logo-only class for proper rectangular logo rendering
            if (dataType === 'platform_logo') {
                try { wmEl.classList.add('moving-watermark--logo-only'); } catch (e) {}
            }
            // Fill ID line only for student_name_id mode
            if (dataType === 'student_name_id') {
                try {
                    var idEl = wmEl.querySelector('.moving-watermark__id');
                    if (idEl) {
                        var sid = (typeof window.authUserId !== 'undefined' && window.authUserId) ? String(window.authUserId) : '';
                        idEl.textContent = sid ? ('ID: ' + sid) : '';
                    }
                } catch (e) {}
            }

            wrapperEl.appendChild(wmEl);

            // Start moving
            cancelExistingMoveTimer(wmEl);
            // Initial slight delay to ensure sizes are computed
            window.setTimeout(function () {
                scheduleNextMove(wmEl, wrapperEl);
            }, 500);
        } catch (e) {
            // noop
        }
    }

})(jQuery)
