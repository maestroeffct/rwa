import AgoraRTC from "agora-rtc-sdk-ng";

(function ($) {
    "use strict";

    /** ======================
     * GLOBALS
     * ====================== */
    var $hostLiveStreamEl = $('#hostLiveStream');
    var $remoteLiveStreamEl = $('#remoteLiveStream');
    var $sidebarUsersCards = $('#sidebarUsersCards');
    var $joinedUsersCount = $('.js-joined-users-count');

    var timerInterval = null;
    var remoteUsers = {};
    var getUserInfoCache = window.getUserInfoCache || {};
    var joinedUsers = {}; // Track all joined users

    // Agora Client
    var client = AgoraRTC.createClient({mode: 'live', codec: 'vp8'});

    var options = {
        appid: appId,
        channel: channelName,
        uid: null,
        token: rtcToken,
        role: streamRole,
        audienceLatency: 2
    };

    var localTracks = {
        videoTrack: null,
        audioTrack: null,
        screenTrack: null
    };

    // Professional Whiteboard Variables
    var whiteboardCanvas = null;
    var whiteboardCtx = null;
    var isDrawing = false;
    var currentTool = 'pen';
    var currentColor = '#000000';
    var currentSize = 4;
    var whiteboardActive = false;
    var whiteboardPages = [{ data: null, objects: [], history: [], historyStep: -1 }];
    var currentPage = 0;
    var whiteboardZoom = 1;
    var whiteboardPanX = 0;
    var whiteboardPanY = 0;
    // Global history removed - now each page has its own history
    // var whiteboardHistory = [];
    // var whiteboardHistoryStep = -1;
    var drawingPath = [];
    var startPoint = null;
    var currentPoint = null; // Track current mouse position for shapes
    var tempCanvas = null;

    /** ======================
     * MAIN
     * ====================== */
    $(() => {
        $remoteLiveStreamEl.on('click', '.js-video-item-control', handleVideoItemControls);

        // Initialize whiteboard
        initializeWhiteboard();

        // Initialize when DOM is ready
        if (typeof window.agoraInitialized === 'undefined') {
            joinAndStartStream();
            window.agoraInitialized = true;
        }
    });

    /** ======================
     * INITIAL JOIN
     * ====================== */
    async function joinAndStartStream() {
        try {
            // Setup event listeners for ALL users (both host and audience)
            setupEventListeners(client);

            // Fix role for multiple stream mode
            let clientRole = options.role;
            if (sessionStreamType === 'multiple') {
                // In multiple stream mode, everyone needs publisher role to share video
                clientRole = 'host';
            }

            await client.setClientRole(clientRole);

            options.uid = await client.join(
                options.appid,
                options.channel,
                options.token || null,
                authUserId
            );

            // Add current user to joined users list immediately
            await addUserToJoinedList(authUserId, true);

            // Start local tracks for host or multiple mode
            if (streamRole === "host" || sessionStreamType === 'multiple') {
                await startLocalTracks();
            }

            // Special handling for single mode audience users
            // Since they don't publish, we need to ensure they appear in user list
            if (sessionStreamType === 'single' && streamRole === 'audience') {
                // Force add to user list since user-joined might not trigger properly in single mode
                console.log('[Single Mode] Adding audience user to list manually');
            }

            // Start the timer
            if (typeof streamStartAt !== 'undefined' && streamStartAt) {
                const startAt = Math.floor((Date.now() / 1000) - streamStartAt);
                startTimer(startAt);
            }

            // Set up periodic user list refresh for single mode
            if (sessionStreamType === 'single') {
                // Initial refresh after a short delay
                setTimeout(() => {
                    refreshUsersList();
                }, 2000);

                // Periodic refresh
                setInterval(() => {
                    refreshUsersList();
                }, 10000); // Refresh every 10 seconds (less frequent)

                // Force refresh when RTM connection is established
                if (typeof window.rtm !== 'undefined') {
                    setTimeout(() => {
                        console.log('[Single Mode] Forcing initial user list refresh via RTM');
                        refreshUsersList();
                    }, 3000);
                }
            }

        } catch (error) {
            console.error("[Agora Join Error]", error);
        }
    }

    function setupEventListeners($client) {
        // Setup event listeners for ALL users (both host and audience)
        $client.on("user-published", handleUserPublished);
        $client.on("user-unpublished", handleUserUnpublished);
        $client.on("user-joined", handlePeerJoined);
        $client.on("user-left", handlePeerLeft);

        // Additional listener for connection state changes to catch all users
        $client.on("connection-state-change", (curState, revState, reason) => {
            console.log(`[Connection State] ${revState} -> ${curState}, reason: ${reason}`);

            // When connected, get list of remote users and add them to users list
            if (curState === "CONNECTED") {
                setTimeout(() => {
                    refreshUsersList();
                }, 1000); // Small delay to ensure all users are loaded
            }
        });
    }

    // Refresh users list - particularly useful for single mode
    function refreshUsersList() {
        try {
            // In single mode, we need to get all channel members, not just published users
            if (sessionStreamType === 'single') {
                // Use RTM to get channel members if available
                if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
                    window.rtm.channels[channelName].getMembers().then((members) => {
                        console.log(`[Refresh Users - RTM] Found ${members.length} channel members`);
                        members.forEach(async (member) => {
                            // Extract user ID from member name (format: "user 123")
                            const uid = parseInt(member.replace('user ', ''));
                            if (!isNaN(uid) && !joinedUsers[uid]) {
                                console.log(`[Refresh Users - RTM] Adding member: ${uid}`);
                                await addUserToJoinedList(uid, uid === authUserId);
                            }
                        });
                    }).catch((error) => {
                        console.error('[Refresh Users - RTM] Error getting members:', error);
                        // Fallback: try to use any available user info
                        fallbackRefreshUsers();
                    });
                } else {
                    // RTM not available, use fallback
                    fallbackRefreshUsers();
                }
            } else {
                // For multiple mode, use the original method
                const remoteUsers = client.remoteUsers || [];
                console.log(`[Refresh Users] Found ${remoteUsers.length} remote users`);

                remoteUsers.forEach(async (user) => {
                    const uid = user.uid;
                    if (!joinedUsers[uid]) {
                        console.log(`[Refresh Users] Adding missing user: ${uid}`);
                        await addUserToJoinedList(uid, false);
                    }
                });
            }
        } catch (error) {
            console.error('[Refresh Users] Error:', error);
        }
    }

    // Fallback method for refreshing users when RTM is not available
    function fallbackRefreshUsers() {
        console.log('[Fallback Refresh] Attempting to refresh users list');

        // Try to get all remote users including non-published ones
        try {
            // Check if client has any connection info we can use
            if (client && client._joinInfo && client._joinInfo.channel) {
                // This is a more aggressive approach - check for any users we might have missed
                Object.keys(remoteUsers).forEach(async (uid) => {
                    if (!joinedUsers[uid]) {
                        console.log(`[Fallback Refresh] Adding missed user: ${uid}`);
                        await addUserToJoinedList(parseInt(uid), false);
                    }
                });
            }

            // Also make sure current user is in the list
            if (!joinedUsers[authUserId]) {
                console.log(`[Fallback Refresh] Re-adding current user: ${authUserId}`);
                addUserToJoinedList(authUserId, true);
            }
        } catch (error) {
            console.error('[Fallback Refresh] Error:', error);
        }
    }

    /** ======================
     * WHITEBOARD FUNCTIONS
     * ====================== */
    function initializeWhiteboard() {
        console.log('[Whiteboard] Initializing professional whiteboard');

        whiteboardCanvas = document.getElementById('whiteboardCanvas');
        if (!whiteboardCanvas) {
            console.error('[Whiteboard] Canvas element not found');
            return;
        }

        whiteboardCtx = whiteboardCanvas.getContext('2d');

        // Create temporary canvas for shape preview
        tempCanvas = document.createElement('canvas');

        // Set canvas size to match the host video area
        resizeWhiteboardCanvas();

        // Setup drawing events (only for host)
        if (streamRole === 'host') {
            setupWhiteboardEvents();
            setupUIEvents();
        }

        // Initialize history for first page
        initializePageHistory();

        // Initialize UI
        updatePageInfo();
        updateZoomInfo();

        // Listen for window resize
        $(window).on('resize', resizeWhiteboardCanvas);

        console.log('[Whiteboard] Professional whiteboard initialized successfully');
    }

    function resizeWhiteboardCanvas() {
        if (!whiteboardCanvas) return;

        const container = whiteboardCanvas.parentElement;
        const rect = container.getBoundingClientRect();

        // Set canvas size to match container
        whiteboardCanvas.width = rect.width || 800;
        whiteboardCanvas.height = rect.height || 450;

        // Set canvas style size to prevent distortion
        whiteboardCanvas.style.width = '100%';
        whiteboardCanvas.style.height = '100%';

        // Configure drawing context
        if (whiteboardCtx) {
            whiteboardCtx.lineCap = 'round';
            whiteboardCtx.lineJoin = 'round';
            whiteboardCtx.lineWidth = 3;
            whiteboardCtx.strokeStyle = currentColor;
        }
    }

    function setupWhiteboardEvents() {
        if (!whiteboardCanvas || streamRole !== 'host') return;

        // Mouse events
        whiteboardCanvas.addEventListener('mousedown', startDrawing);
        whiteboardCanvas.addEventListener('mousemove', draw);
        whiteboardCanvas.addEventListener('mouseup', stopDrawing);
        whiteboardCanvas.addEventListener('mouseout', stopDrawing);

        // Touch events for mobile
        whiteboardCanvas.addEventListener('touchstart', handleTouchStart, { passive: false });
        whiteboardCanvas.addEventListener('touchmove', handleTouchMove, { passive: false });
        whiteboardCanvas.addEventListener('touchend', stopDrawing);
    }

    function setupUIEvents() {
        // Tool selection
        $(document).on('click', '.js-whiteboard-tool', function() {
            $('.js-whiteboard-tool').removeClass('active');
            $(this).addClass('active');

            currentTool = $(this).data('tool');
            updateCursor(currentTool);
            console.log(`[Whiteboard] Tool changed to: ${currentTool}`);
        });

        // Toggle color palette
        $(document).on('click', '.js-toggle-colors', function() {
            $('.whiteboard-colors').toggleClass('d-none');
            $('.whiteboard-brush-size').addClass('d-none'); // Hide size picker
        });

        // Toggle size selector
        $(document).on('click', '.js-toggle-sizes', function() {
            $('.whiteboard-brush-size').toggleClass('d-none');
            $('.whiteboard-colors').addClass('d-none'); // Hide color picker
        });

        // Color selection
        $(document).on('click', '.js-color-picker', function() {
            $('.js-color-picker').removeClass('active');
            $(this).addClass('active');

            currentColor = $(this).data('color');

            // Update color indicator
            $('.current-color-indicator').css('background-color', currentColor);

            // Hide color palette after selection
            $('.whiteboard-colors').addClass('d-none');

            console.log(`[Whiteboard] Color changed to: ${currentColor}`);
        });

        // Size selection
        $(document).on('click', '.js-size-picker', function() {
            $('.js-size-picker').removeClass('active');
            $(this).addClass('active');

            currentSize = $(this).data('size');

            // Update size indicator
            const indicatorSize = currentSize * 2; // Scale for visibility
            $('.current-size-indicator').css({
                'width': indicatorSize + 'px',
                'height': indicatorSize + 'px'
            });

            // Hide size picker after selection
            $('.whiteboard-brush-size').addClass('d-none');

            console.log(`[Whiteboard] Size changed to: ${currentSize}`);
        });

        // Hide palettes when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.whiteboard-colors, .js-toggle-colors, .whiteboard-brush-size, .js-toggle-sizes').length) {
                $('.whiteboard-colors, .whiteboard-brush-size').addClass('d-none');
            }
        });

        // Page navigation
        $(document).on('click', '.js-page-prev', function() {
            if (currentPage > 0) {
                savePage();
                currentPage--;
                loadPage();

                // Ensure history is initialized after loading
                setTimeout(() => {
                    initializePageHistory();
                }, 50);

                updatePageInfo();

                // Send page change event
                if (streamRole === 'host') {
                    sendWhiteboardPageEvent('prev', currentPage);
                }
            }
        });

        $(document).on('click', '.js-page-next', function() {
            if (currentPage < whiteboardPages.length - 1) {
                savePage();
                currentPage++;
                loadPage();

                // Ensure history is initialized after loading
                setTimeout(() => {
                    initializePageHistory();
                }, 50);

                updatePageInfo();

                // Send page change event
                if (streamRole === 'host') {
                    sendWhiteboardPageEvent('next', currentPage);
                }
            }
        });

        $(document).on('click', '.js-page-add', function() {
            savePage();
            whiteboardPages.push({ data: null, objects: [], history: [], historyStep: -1 });
            currentPage = whiteboardPages.length - 1;
            clearWhiteboard();

            // Initialize history for new page with empty state
            setTimeout(() => {
                initializePageHistory();
            }, 100); // Small delay to ensure canvas is cleared

            updatePageInfo();

            // Send page add event
            if (streamRole === 'host') {
                sendWhiteboardPageEvent('add', currentPage);
            }
        });

        // Zoom controls
        $(document).on('click', '.js-zoom-in', function() {
            whiteboardZoom = Math.min(whiteboardZoom * 1.2, 3);
            updateZoom();
        });

        $(document).on('click', '.js-zoom-out', function() {
            whiteboardZoom = Math.max(whiteboardZoom / 1.2, 0.3);
            updateZoom();
        });

        $(document).on('click', '.js-zoom-reset', function() {
            whiteboardZoom = 1;
            whiteboardPanX = 0;
            whiteboardPanY = 0;
            updateZoom();
        });

        // History controls
        $(document).on('click', '.js-whiteboard-undo', function() {
            undoWhiteboard();
            // Send undo event to other users
            if (streamRole === 'host') {
                sendWhiteboardHistoryEvent('undo');
            }
        });

        $(document).on('click', '.js-whiteboard-redo', function() {
            redoWhiteboard();
            // Send redo event to other users
            if (streamRole === 'host') {
                sendWhiteboardHistoryEvent('redo');
            }
        });

        // Clear whiteboard
        $(document).on('click', '.js-whiteboard-clear', function() {
            clearWhiteboard();
        });
    }

    function getMousePos(e) {
        const rect = whiteboardCanvas.getBoundingClientRect();
        const scaleX = whiteboardCanvas.width / rect.width;
        const scaleY = whiteboardCanvas.height / rect.height;

        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }

    function getTouchPos(e) {
        const rect = whiteboardCanvas.getBoundingClientRect();
        const scaleX = whiteboardCanvas.width / rect.width;
        const scaleY = whiteboardCanvas.height / rect.height;

        return {
            x: (e.touches[0].clientX - rect.left) * scaleX,
            y: (e.touches[0].clientY - rect.top) * scaleY
        };
    }

    function startDrawing(e) {
        if (!whiteboardActive || streamRole !== 'host') return;

        const pos = e.type === 'touchstart' ? getTouchPos(e) : getMousePos(e);
        startPoint = { x: pos.x, y: pos.y };
        isDrawing = true;
        drawingPath = [{ x: pos.x, y: pos.y }];



        if (['pen', 'pencil', 'marker', 'eraser'].includes(currentTool)) {
            whiteboardCtx.beginPath();
            whiteboardCtx.moveTo(pos.x, pos.y);
            configureDrawingStyle();
        } else if (['line', 'arrow', 'rectangle', 'circle'].includes(currentTool)) {
            // Save current state before shape preview
            shapePreviewState = whiteboardCanvas.toDataURL();
        }

        if (currentTool === 'text') {
            handleTextInput(pos.x, pos.y);
            return;
        }

        if (currentTool === 'laser') {
            handleLaserPointer(pos.x, pos.y);
            return;
        }

        // Send start drawing event
        sendWhiteboardEvent('start', pos.x, pos.y);
    }

    function draw(e) {
        if (!whiteboardActive || streamRole !== 'host') return;

        const pos = e.type === 'touchmove' ? getTouchPos(e) : getMousePos(e);
        currentPoint = pos; // Always track current position

        if (!isDrawing) {
            updateCursor(currentTool);
            return;
        }

        if (['pen', 'pencil', 'marker', 'eraser'].includes(currentTool)) {
            whiteboardCtx.lineTo(pos.x, pos.y);
            whiteboardCtx.stroke();
            drawingPath.push({ x: pos.x, y: pos.y });
            // Send drawing event for pen tools
            sendWhiteboardEvent('draw', pos.x, pos.y);
        } else if (['line', 'arrow', 'rectangle', 'circle'].includes(currentTool)) {
            // Clear and redraw shape with proper restoration
            if (shapePreviewState) {
                var img = new Image();
                img.onload = function() {
                    whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);
                    whiteboardCtx.drawImage(img, 0, 0);
                    // Draw shape after restoring
                    drawShape(startPoint.x, startPoint.y, pos.x, pos.y, currentTool);
                };
                img.src = shapePreviewState;
            } else {
                // First time, just draw the shape
                drawShape(startPoint.x, startPoint.y, pos.x, pos.y, currentTool);
            }
            // Don't send drawing events for shapes - we'll send complete shape on stop
        }
    }

    function stopDrawing() {
        if (!isDrawing || !whiteboardActive || streamRole !== 'host') return;

        isDrawing = false;

        // For shapes, send complete shape data instead of just stop event
        if (['line', 'arrow', 'rectangle', 'circle'].includes(currentTool) && startPoint && currentPoint) {
            // Send complete shape with start and end points
            sendWhiteboardShapeEvent(currentTool, startPoint.x, startPoint.y, currentPoint.x, currentPoint.y);

            // Reset shape preview state
            shapePreviewState = null;
        } else {
            // For drawing tools, send stop event
            sendWhiteboardEvent('stop');
        }

        // Save state for undo/redo
        saveWhiteboardState();

        // Reset drawing variables
        drawingPath = [];
        startPoint = null;
        currentPoint = null;
    }

    function handleTouchStart(e) {
        e.preventDefault();
        startDrawing(e);
    }

    function handleTouchMove(e) {
        e.preventDefault();
        draw(e);
    }

    function clearWhiteboard() {
        if (!whiteboardCanvas) return;

        whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);

        // Reset history for current page after clearing
        if (currentPage >= 0) {
            whiteboardPages[currentPage].history = [];
            whiteboardPages[currentPage].historyStep = -1;

            // Save the cleared state as new initial state
            setTimeout(() => {
                initializePageHistory();
            }, 50);
        }

        // Send clear event to all users
        sendWhiteboardEvent('clear');
    }

    function sendWhiteboardEvent(type, x = 0, y = 0) {
        if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
            const whiteboardData = {
                type: 'whiteboard_draw',
                action: type,
                x: x,
                y: y,
                tool: currentTool,
                color: currentColor,
                size: currentSize,
                timestamp: Date.now()
            };

            try {
                window.rtm.sendChannelMessage(JSON.stringify(whiteboardData), channelName);
            } catch (error) {
                console.error('[Whiteboard RTM] Error sending message:', error);
            }
        }
    }

    function sendWhiteboardShapeEvent(shapeType, startX, startY, endX, endY) {
        if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
            const shapeData = {
                type: 'whiteboard_shape',
                shape: shapeType,
                startX: startX,
                startY: startY,
                endX: endX,
                endY: endY,
                color: currentColor,
                size: currentSize,
                timestamp: Date.now()
            };

            try {
                window.rtm.sendChannelMessage(JSON.stringify(shapeData), channelName);
            } catch (error) {
                console.error('[Whiteboard Shape RTM] Error sending message:', error);
            }
        }
    }

    function sendWhiteboardHistoryEvent(action) {
        if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
            // Get canvas state from current page history
            let canvasState = null;
            if (currentPage >= 0) {
                var currentPageHistory = whiteboardPages[currentPage].history;
                var currentHistoryStep = whiteboardPages[currentPage].historyStep;

                if (currentPageHistory && currentHistoryStep >= 0 && currentHistoryStep < currentPageHistory.length) {
                    canvasState = currentPageHistory[currentHistoryStep];
                }
            }

            const historyData = {
                type: 'whiteboard_history',
                action: action,
                canvasState: canvasState,
                timestamp: Date.now()
            };

            try {
                window.rtm.sendChannelMessage(JSON.stringify(historyData), channelName);
                console.log('[Whiteboard History RTM] Sent history event:', action, 'page:', currentPage, 'step:', whiteboardPages[currentPage].historyStep);
            } catch (error) {
                console.error('[Whiteboard History RTM] Error sending message:', error);
            }
        }
    }

    function sendWhiteboardTextEvent(text, x, y) {
        if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
            const textData = {
                type: 'whiteboard_text',
                text: text,
                x: x,
                y: y,
                color: currentColor,
                size: currentSize,
                timestamp: Date.now()
            };

            try {
                window.rtm.sendChannelMessage(JSON.stringify(textData), channelName);
                console.log('[Whiteboard Text RTM] Sent text event:', text);
            } catch (error) {
                console.error('[Whiteboard Text RTM] Error sending message:', error);
            }
        }
    }

    function sendWhiteboardPageEvent(action, pageNumber) {
        if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
            // Get current page data if available
            const pageData = whiteboardPages[pageNumber] ? whiteboardPages[pageNumber].data : null;

            const pageEventData = {
                type: 'whiteboard_page',
                action: action,
                pageNumber: pageNumber,
                totalPages: whiteboardPages.length,
                pageData: pageData,
                timestamp: Date.now()
            };

            try {
                window.rtm.sendChannelMessage(JSON.stringify(pageEventData), channelName);
                console.log('[Whiteboard Page RTM] Sent page event:', action, pageNumber);
            } catch (error) {
                console.error('[Whiteboard Page RTM] Error sending message:', error);
            }
        }
    }

    function sendWhiteboardToggleEvent(action) {
        if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
            const toggleData = {
                type: 'whiteboard_toggle',
                action: action,
                timestamp: Date.now()
            };

            try {
                window.rtm.sendChannelMessage(JSON.stringify(toggleData), channelName);
            } catch (error) {
                console.error('[Whiteboard Toggle RTM] Error sending message:', error);
            }
        }
    }

    // Make handleWhiteboardEvent globally accessible
    window.handleWhiteboardEvent = function(data) {
        if (!whiteboardCanvas || !whiteboardCtx) return;

        try {
            const { action, x, y, tool, color, size } = data;

            switch (action) {
                case 'start':
                    whiteboardCtx.beginPath();
                    whiteboardCtx.moveTo(x, y);
                    break;

                case 'draw':
                    // Configure drawing style based on tool and received data
                    whiteboardCtx.lineCap = 'round';
                    whiteboardCtx.lineJoin = 'round';
                    whiteboardCtx.lineWidth = size || 4; // Use received size or default

                    if (tool === 'eraser') {
                        whiteboardCtx.globalCompositeOperation = 'destination-out';
                        whiteboardCtx.lineWidth = (size || 4) * 2; // Eraser is bigger
                    } else {
                        whiteboardCtx.globalCompositeOperation = 'source-over';
                        whiteboardCtx.strokeStyle = color || '#000000';

                        if (tool === 'marker') {
                            whiteboardCtx.globalAlpha = 0.5;
                        } else {
                            whiteboardCtx.globalAlpha = 1.0;
                        }
                    }

                    whiteboardCtx.lineTo(x, y);
                    whiteboardCtx.stroke();
                    break;

                case 'stop':
                    whiteboardCtx.beginPath();
                    break;

                case 'clear':
                    whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);

                    // Reset history for current page after clearing (for students)
                    if (currentPage >= 0) {
                        whiteboardPages[currentPage].history = [];
                        whiteboardPages[currentPage].historyStep = -1;

                        // Save the cleared state as new initial state
                        setTimeout(() => {
                            initializePageHistory();
                        }, 50);
                    }
                    break;

                case 'laser':
                    // Show laser pointer for remote users
                    whiteboardCtx.save();
                    whiteboardCtx.fillStyle = '#ff0000';
                    whiteboardCtx.globalAlpha = 0.8;
                    whiteboardCtx.beginPath();
                    whiteboardCtx.arc(x, y, 8, 0, 2 * Math.PI);
                    whiteboardCtx.fill();
                    whiteboardCtx.restore();

                    // Auto fade out after 3 seconds
                    setTimeout(() => {
                        whiteboardCtx.clearRect(x - 10, y - 10, 20, 20);
                    }, 3000);
                    break;
            }
        } catch (error) {
            console.error('[Whiteboard] Error handling event:', error);
        }
    }

    // Make handleWhiteboardShapeEvent globally accessible
    window.handleWhiteboardShapeEvent = function(data) {
        if (!whiteboardCanvas || !whiteboardCtx) return;

        try {
            const { shape, startX, startY, endX, endY, color, size } = data;

            // Set drawing style
            whiteboardCtx.beginPath();
            whiteboardCtx.strokeStyle = color || '#000000';
            whiteboardCtx.lineWidth = size || 4;
            whiteboardCtx.globalCompositeOperation = 'source-over';
            whiteboardCtx.globalAlpha = 1.0;
            whiteboardCtx.lineCap = 'round';
            whiteboardCtx.lineJoin = 'round';

            // Draw the shape
            switch(shape) {
                case 'line':
                    whiteboardCtx.moveTo(startX, startY);
                    whiteboardCtx.lineTo(endX, endY);
                    break;
                case 'arrow':
                    drawRemoteArrow(startX, startY, endX, endY);
                    break;
                case 'rectangle':
                    whiteboardCtx.rect(startX, startY, endX - startX, endY - startY);
                    break;
                case 'circle':
                    var radius = Math.sqrt(Math.pow(endX - startX, 2) + Math.pow(endY - startY, 2));
                    whiteboardCtx.arc(startX, startY, radius, 0, 2 * Math.PI);
                    break;
            }
            whiteboardCtx.stroke();
        } catch (error) {
            console.error('[Whiteboard Shape] Error handling event:', error);
        }
    }

    // Make handleWhiteboardHistoryEvent globally accessible
    window.handleWhiteboardHistoryEvent = function(data) {
        if (!whiteboardCanvas || !whiteboardCtx) return;

        try {
            const { action, canvasState } = data;

            if (canvasState) {
                // Restore canvas state from teacher
                var img = new Image();
                img.onload = function() {
                    whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);
                    whiteboardCtx.drawImage(img, 0, 0);
                };
                img.src = canvasState;
            }

            console.log('[Whiteboard History] Received history event:', action);
        } catch (error) {
            console.error('[Whiteboard History] Error handling event:', error);
        }
    }

    // Make handleWhiteboardTextEvent globally accessible
    window.handleWhiteboardTextEvent = function(data) {
        if (!whiteboardCanvas || !whiteboardCtx) return;

        try {
            const { text, x, y, color, size } = data;

            // Set text style
            whiteboardCtx.fillStyle = color || '#000000';
            whiteboardCtx.font = ((size || 4) * 4) + 'px Arial';

            // Draw text
            whiteboardCtx.fillText(text, x, y);

            console.log('[Whiteboard Text] Received text event:', text);
        } catch (error) {
            console.error('[Whiteboard Text] Error handling event:', error);
        }
    }

    // Make handleWhiteboardPageEvent globally accessible
    window.handleWhiteboardPageEvent = function(data) {
        if (!whiteboardCanvas || !whiteboardCtx) return;

        try {
            const { action, pageNumber, totalPages, pageData } = data;

            // Sync page structure
            while (whiteboardPages.length < totalPages) {
                whiteboardPages.push({ data: null, objects: [], history: [], historyStep: -1 });
            }

            // Update current page
            currentPage = pageNumber;

            // Load page data if available
            if (pageData) {
                var img = new Image();
                img.onload = function() {
                    whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);
                    whiteboardCtx.drawImage(img, 0, 0);

                    // Initialize history after loading page
                    setTimeout(() => {
                        initializePageHistory();
                    }, 50);
                };
                img.src = pageData;
                whiteboardPages[pageNumber].data = pageData;
            } else {
                // Clear canvas for new page
                whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);

                // Initialize history for empty page
                setTimeout(() => {
                    initializePageHistory();
                }, 50);
            }

            // Update page info
            updatePageInfo();

            console.log('[Whiteboard Page] Received page event:', action, pageNumber);
        } catch (error) {
            console.error('[Whiteboard Page] Error handling event:', error);
        }
    }

    function drawRemoteArrow(startX, startY, endX, endY) {
        var headlen = 10;
        var angle = Math.atan2(endY - startY, endX - startX);

        // Draw line
        whiteboardCtx.moveTo(startX, startY);
        whiteboardCtx.lineTo(endX, endY);

        // Draw arrowhead
        whiteboardCtx.lineTo(endX - headlen * Math.cos(angle - Math.PI / 6), endY - headlen * Math.sin(angle - Math.PI / 6));
        whiteboardCtx.moveTo(endX, endY);
        whiteboardCtx.lineTo(endX - headlen * Math.cos(angle + Math.PI / 6), endY - headlen * Math.sin(angle + Math.PI / 6));
    }

    // Initialize whiteboard for students (view-only)
    function initWhiteboardForStudent() {
        if (!whiteboardCanvas || !whiteboardCtx) {
            initializeWhiteboard();
        }

        // Add view-only class for students
        $(whiteboardCanvas).addClass('view-only');

        // Resize canvas for proper display
        setTimeout(() => {
            resizeWhiteboardCanvas();
        }, 200);

        console.log('[Student] Whiteboard initialized for viewing');
    }

    /** ======================
     * LOCAL TRACKS
     * ====================== */
    async function startLocalTracks() {
        try {
            // Configure video settings for better mobile compatibility
            const videoConfig = {
                encoderConfig: {
                    width: 640,
                    height: 480,
                    frameRate: 15,
                    bitrateMin: 200,
                    bitrateMax: 1000,
                }
            };

            localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack();
            localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack(videoConfig);

            // Video placement depends on user role
            if (streamRole === "host") {
                // Host: show own video in main area
                if (localTracks.videoTrack && $hostLiveStreamEl.length) {
                    $hostLiveStreamEl.empty();
                    localTracks.videoTrack.play("hostLiveStream");
                    console.log("[Host Video] Host video displayed in main area");

                    // Ensure video fills container properly on mobile
                    setTimeout(() => {
                        const videoElement = document.querySelector('#hostLiveStream video');
                        if (videoElement) {
                            videoElement.style.width = '100%';
                            videoElement.style.height = '100%';
                            videoElement.style.objectFit = 'cover';
                        }
                    }, 500);
                }
            } else {
                // Student: add own video to remote area (small cards)
                if (localTracks.videoTrack) {
                    await addRemoteUserCard(authUserId);
                    localTracks.videoTrack.play(`remote-player-${authUserId}`);
                    console.log("[Student Video] Student video displayed in remote area");
                }
            }

            // Publish tracks
            await client.publish([localTracks.audioTrack, localTracks.videoTrack]);
            console.log("[Agora] Local tracks published");

        } catch (error) {
            console.error("[Agora] Failed to start local tracks:", error);
        }
    }

    /** ======================
     * PEER EVENTS
     * ====================== */
    async function handlePeerJoined(user) {
        console.log("[Peer Joined]", user);
        const uid = user.uid;
        remoteUsers[uid] = user;

        // Add user to Users Tab for ALL modes (single and multiple)
        await addUserToJoinedList(uid, false);
        console.log(`User ${uid} joined the session`);

        // In single mode, audience users should also be shown even if they don't publish
        if (sessionStreamType === 'single' && streamRole === 'audience') {
            console.log(`Adding audience user ${uid} to users list in single mode`);
        }
    }

    async function handlePeerLeft(user) {
        console.log("[Peer Left]", user);
        const uid = user.uid;

        // Remove from remoteUsers
        delete remoteUsers[uid];

        // Remove from Users Tab
        removeUserFromJoinedList(uid);

        // Remove video element if exists
        $(`#remote-player-${uid}`).remove();
        console.log(`User ${uid} left the session`);
    }

    async function handleUserPublished(user, mediaType) {
        console.log("[User Published]", user, mediaType);
        const uid = user.uid;

        try {
            await client.subscribe(user, mediaType);
            console.log(`[Subscribe Success] User ${uid}, mediaType: ${mediaType}`);

            if (mediaType === 'video') {
                // Handle video placement based on viewer role and user type
                if (uid == hostUserId) {
                    // This is the host publishing video
                    if (streamRole === "host") {
                        // Host sees own video in main area (already handled by startLocalTracks)
                        console.log(`[Host Video] Host ${uid} video published, already in main area`);
                        return;
                    } else {
                        // Students see host video (including screen share) in main area
                        if ($hostLiveStreamEl.length) {
                            $hostLiveStreamEl.empty();
                            user.videoTrack.play("hostLiveStream");

                            // Ensure video fills container properly on mobile
                            setTimeout(() => {
                                const videoElement = document.querySelector('#hostLiveStream video');
                                if (videoElement) {
                                    videoElement.style.width = '100%';
                                    videoElement.style.height = '100%';
                                    videoElement.style.objectFit = 'cover';
                                }
                            }, 500);

                            // Check if this is a screen share track
                            const isScreenShare = user.videoTrack._trackType === 'screen';
                            if (isScreenShare) {
                                console.log(`[Host Screen Share] Host ${uid} screen share displayed in main area for student`);
                            } else {
                                console.log(`[Host Video] Host ${uid} video displayed in main area for student`);
                            }
                        }
                        return;
                    }
                }

                // Check if video element already exists
                if ($(`#remote-player-${uid}`).length > 0) {
                    console.log(`[Video Element Exists] Removing existing element for user ${uid}`);
                    $(`#remote-player-${uid}`).remove();
                }

                // Create video player for remote user (students only)
                await addRemoteUserCard(uid);
                user.videoTrack.play(`remote-player-${uid}`);

                // Update player with user info
                const userInfo = await getUserInfo(uid);
                updateVideoPlayerInfo(uid, userInfo);

                console.log(`[Video Display Success] Student ${uid} video is now playing in remote area`);
            }

            if (mediaType === 'audio') {
                user.audioTrack.play();
                console.log(`[Audio Success] User ${uid} audio is now playing`);
            }
        } catch (error) {
            console.error(`[Subscribe Error] Failed to subscribe to user ${uid}:`, error);
        }
    }

    async function handleUserUnpublished(user, mediaType) {
        console.log("[User Unpublished]", user, mediaType);
        if (mediaType === 'video') {
            $(`#remote-player-${user.uid}`).remove();
        }
    }

    /** ======================
     * USER MANAGEMENT
     * ====================== */
    async function addUserToJoinedList(uid, isCurrentUser = false) {
        try {
            if (joinedUsers[uid]) {
                return; // User already added
            }

            const userInfo = await getUserInfo(uid);
            if (userInfo) {
                joinedUsers[uid] = userInfo;
                handleUserCardHtml(userInfo, isCurrentUser);
                updateUsersCount();
            }
        } catch (error) {
            console.error("[Error] Failed to get user info for:", uid, error);
        }
    }

    function removeUserFromJoinedList(uid) {
        if (joinedUsers[uid]) {
            delete joinedUsers[uid];
            $(`.js-user-card[data-user-id="${uid}"]`).remove();
            updateUsersCount();
        }
    }

    function handleUserCardHtml(user, isCurrentUser = false) {
        // Determine role based on whether user is the session host, not current user
        const roleText = (user.id == hostUserId) ? hostLang : studentLang;
        const html = `
            <div class="js-user-card d-flex align-items-center my-16" data-user-id="${user.id}">
                <div class="user-avatar mr-5">
                    <img src="${user.avatar}" alt="${user.full_name}" width="48" height="48" class="rounded-circle">
                </div>
                <div class="user-info ml-10">
                    <div class="font-14 text-dark font-weight-bold mb-4">${user.full_name}</div>
                    <div class="font-12 text-gray-500">${roleText}</div>
                </div>
            </div>
        `;

        if ($sidebarUsersCards.length) {
            $sidebarUsersCards.append(html);
        }
    }

    function updateUsersCount() {
        const count = Object.keys(joinedUsers).length;

        // Update multiple count elements
        $('.js-joined-users-count').text(count);
        $('.js-all-live-users-count').text(count);

        // Show/hide count elements based on user count
        const $allUsersCountCard = $('.js-all-live-users-count-card');
        if (count > 1) {
            $allUsersCountCard.removeClass('d-none').addClass('d-flex');
        } else {
            $allUsersCountCard.removeClass('d-flex').addClass('d-none');
        }

        console.log(`[Users Count Updated] Total users: ${count}`);
    }

    /** ======================
     * VIDEO PLAYER UTILS
     * ====================== */

    function ensureCorrectVideoLayout() {
        // Make sure host video is in main area if local tracks exist
        if (localTracks.videoTrack && $hostLiveStreamEl.length) {
            try {
                $hostLiveStreamEl.empty();
                localTracks.videoTrack.play("hostLiveStream");
                console.log("[Layout Fix] Host video repositioned to main area");
            } catch (error) {
                console.error("[Layout Fix Error]", error);
            }
        }
    }

    async function addRemoteUserCard(uid) {
        try {
            const userInfo = await getUserInfo(uid);

            const cardHtml = `
                <div id="remote-player-${uid}" class="remote-stream video-player-wrapper">
                    <div class="video-info">
                        <span class="user-name">${userInfo?.full_name || 'User'}</span>
                    </div>
                </div>
            `;
            $remoteLiveStreamEl.append($(cardHtml));

        } catch (error) {
            console.error("Failed to add remote user card:", error);
            const fallbackHtml = `<div id="remote-player-${uid}" class="remote-stream video-player-wrapper"></div>`;
            $remoteLiveStreamEl.append($(fallbackHtml));
        }
    }

    function updateVideoPlayerInfo(uid, userInfo) {
        if (userInfo) {
            $(`#remote-player-${uid} .user-name`).text(userInfo.full_name);
        }
    }

    function handleVideoItemControls(e) {
        e.preventDefault();
        const $btn = $(this);
        const action = $btn.data('action');
        console.log("Video control action:", action);
    }

    /** ======================
     * USER INFO API
     * ====================== */
    window.getUserInfo = function (uid) {
        return new Promise((resolve, reject) => {
            if (getUserInfoCache[uid] !== undefined) {
                resolve(getUserInfoCache[uid]);
            } else {
                $.get(`/panel/users/${uid}/getInfo`, (result) => {
                    if (result?.user) {
                        getUserInfoCache[uid] = result.user;
                        resolve(result.user);
                    } else {
                        reject();
                    }
                }).fail(() => reject());
            }
        });
    }

    /** ======================
     * TIMER
     * ====================== */
    function startTimer(startAt = 0) {
        const $timer = $('.js-stream-timer');
        if (!$timer.length) return;

        let totalSeconds = startAt;

        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            totalSeconds++;
            const seconds = pad(Math.floor((totalSeconds) % 60));
            const minutes = pad(Math.floor((totalSeconds / 60) % 60));
            const hours = pad(Math.floor((totalSeconds / (60 * 60)) % 24));

            $timer.find('.hours').html(hours);
            $timer.find('.minutes').html(minutes);
            $timer.find('.seconds').html(seconds);
        }, 1000);
    }

    function pad(val) {
        let valString = val + "";
        if (valString.length < 2) {
            return "0" + valString;
        } else {
            return valString;
        }
    }

    /** ======================
     * LEAVE
     * ====================== */
    async function leave() {
        Object.values(localTracks).forEach(track => {
            if (track && typeof track.stop === 'function') {
                track.stop();
                track.close();
            }
        });

        await client.leave();
        if (redirectAfterLeave) {
            window.location.href = redirectAfterLeave;
        }
    }

    // End session for all users (only host can call this)
    async function endSessionForAll() {
        try {
            // First, notify server to end the session
            const response = await fetch(`/panel/sessions/${sessionId}/endAgora`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                // Send RTM message to all users about session end
                if (typeof window.rtm !== 'undefined' && window.rtm.channels && window.rtm.channels[channelName]) {
                    try {
                        const sessionEndMessage = JSON.stringify({
                            type: 'session_ended',
                            message: 'Host has ended the session',
                            timestamp: Date.now()
                        });

                        await window.rtm.sendChannelMessage(sessionEndMessage, channelName);
                        console.log('[RTM] Session end message sent to all users');
                    } catch (rtmError) {
                        console.error('[RTM] Error sending session end message:', rtmError);
                    }
                }

                // Immediately leave without any notifications
                leave();
            } else {
                console.error('Failed to end session on server');
                // Fallback: just leave locally
                leave();
            }
        } catch (error) {
            console.error('[Error] Failed to end session for all:', error);
            // Fallback: just leave locally
            leave();
        }
    }

    /** ======================
     * CONTROL BUTTONS
     * ====================== */

    // Toggle Microphone
    $(document).on('click', '.js-toggle-microphone', function() {
        const $this = $(this);
        const isActive = $this.hasClass('active');

        if (localTracks.audioTrack) {
            if (isActive) {
                localTracks.audioTrack.setMuted(true);
                $this.removeClass('active');
                console.log("[Microphone] Muted");
            } else {
                localTracks.audioTrack.setMuted(false);
                $this.addClass('active');
                console.log("[Microphone] Unmuted");
            }
        }
    });

    // Toggle Camera
    $(document).on('click', '.js-toggle-camera', function() {
        const $this = $(this);
        const isActive = $this.hasClass('active');

        if (localTracks.videoTrack) {
            if (isActive) {
                localTracks.videoTrack.setMuted(true);
                $this.removeClass('active');
                console.log("[Camera] Disabled");
            } else {
                localTracks.videoTrack.setMuted(false);
                $this.addClass('active');
                console.log("[Camera] Enabled");
            }
        }
    });

    // Toggle Share Screen
    $(document).on('click', '.js-toggle-share-screen', async function() {
        const $this = $(this);
        const isActive = $this.hasClass('active');

        try {
            if (isActive) {
                // Stop screen sharing
                if (localTracks.screenTrack) {
                    await client.unpublish(localTracks.screenTrack);
                    localTracks.screenTrack.stop();
                    localTracks.screenTrack.close();
                    localTracks.screenTrack = null;

                    // Resume camera in main area
                    if (localTracks.videoTrack) {
                        await client.publish(localTracks.videoTrack);
                        // Clear main area and show camera again
                        $hostLiveStreamEl.empty();
                        localTracks.videoTrack.play("hostLiveStream");
                        console.log("[Screen Share] Camera resumed in main area");
                    }
                }
                $this.removeClass('active');
                console.log("[Screen Share] Stopped");
            } else {
                // Start screen sharing
                localTracks.screenTrack = await AgoraRTC.createScreenVideoTrack();

                // Stop camera publication (but keep track)
                if (localTracks.videoTrack) {
                    await client.unpublish(localTracks.videoTrack);
                }

                // Publish screen track and display in main area
                await client.publish(localTracks.screenTrack);
                $hostLiveStreamEl.empty();
                localTracks.screenTrack.play("hostLiveStream");

                $this.addClass('active');
                console.log("[Screen Share] Started and displayed in main area");

                // Handle screen share end event
                localTracks.screenTrack.on("track-ended", () => {
                    $this.click(); // This will stop screen sharing
                });
            }
        } catch (error) {
            console.error("[Screen Share Error]", error);
        }
    });

    /** ======================
     * WHITEBOARD CONTROLS
     * ====================== */

    // Toggle Whiteboard
    $(document).on('click', '.js-toggle-whiteboard', function() {
        const $this = $(this);
        const isActive = $this.hasClass('active');

        if (isActive) {
            // Hide whiteboard, show video
            whiteboardActive = false;
            $('#whiteboardContainer').addClass('d-none');
            $hostLiveStreamEl.removeClass('d-none');

            // Resume video if available
            if (localTracks.videoTrack && !localTracks.screenTrack) {
                localTracks.videoTrack.play("hostLiveStream");
            } else if (localTracks.screenTrack) {
                localTracks.screenTrack.play("hostLiveStream");
            }

            $this.removeClass('active');
            console.log("[Whiteboard] Hidden");

            // Send hide whiteboard event to all users
            sendWhiteboardToggleEvent('hide');
        } else {
            // Show whiteboard, hide video
            whiteboardActive = true;
            $hostLiveStreamEl.addClass('d-none');
            $('#whiteboardContainer').removeClass('d-none');

            // Resize canvas to fit container
            setTimeout(() => {
                resizeWhiteboardCanvas();
            }, 100);

            $this.addClass('active');
            console.log("[Whiteboard] Shown");

            // Send show whiteboard event to all users
            sendWhiteboardToggleEvent('show');
        }
    });

    // Whiteboard Tool Selection
    $(document).on('click', '.js-whiteboard-tool', function() {
        if (streamRole !== 'host') return;

        const $this = $(this);
        $('.js-whiteboard-tool').removeClass('active');
        $this.addClass('active');

        currentTool = $this.data('tool');
        const color = $this.data('color');

        if (color) {
            currentColor = color;
        }

        console.log(`[Whiteboard] Tool changed to: ${currentTool}, Color: ${currentColor}`);
    });

    // Clear Whiteboard
    $(document).on('click', '.js-whiteboard-clear', function() {
        if (streamRole !== 'host') return;

        if (confirm('Clear whiteboard? This action cannot be undone.')) {
            clearWhiteboard();
            console.log("[Whiteboard] Cleared");
        }
    });

    // Fullscreen Toggle
    $(document).on('click', '[data-tippy-content*="fullscreen"]', function() {
        const videoContainer = document.getElementById('hostLiveStream');

        try {
            if (!document.fullscreenElement) {
                if (videoContainer.requestFullscreen) {
                    videoContainer.requestFullscreen();
                } else if (videoContainer.webkitRequestFullscreen) {
                    videoContainer.webkitRequestFullscreen();
                } else if (videoContainer.msRequestFullscreen) {
                    videoContainer.msRequestFullscreen();
                }
                console.log("[Fullscreen] Entered");
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                console.log("[Fullscreen] Exited");
            }
        } catch (error) {
            console.error("[Fullscreen Error]", error);
        }
    });

    // End Live Stream
    $(document).on('click', '.js-end-live-stream', function() {
        endSessionForAll();
    });

    // Global functions for external access
    window.agoraLeave = leave;
    window.agoraRemoteUsers = remoteUsers;
    window.agoraLocalTracks = localTracks;
    window.addUserToJoinedList = addUserToJoinedList;
    window.removeUserFromJoinedList = removeUserFromJoinedList;
    window.agoraWhiteboard = {
        show: () => $('.js-toggle-whiteboard').click(),
        hide: () => $('.js-toggle-whiteboard.active').click(),
        clear: clearWhiteboard,
        handleRemoteEvent: handleWhiteboardEvent,
        initForStudent: initWhiteboardForStudent
    };

    // Make whiteboard event handler available globally
    window.handleWhiteboardEvent = handleWhiteboardEvent;

    /** ======================
     * PROFESSIONAL WHITEBOARD HELPER FUNCTIONS
     * ====================== */

    function updateCursor(tool) {
        if (!whiteboardCanvas) return;

        var cursor = 'default';
        switch(tool) {
            case 'pen':
            case 'pencil':
            case 'marker': cursor = 'crosshair'; break;
            case 'eraser': cursor = 'grab'; break;
            case 'text': cursor = 'text'; break;
            case 'laser': cursor = 'pointer'; break;
            default: cursor = 'crosshair';
        }
        whiteboardCanvas.style.cursor = cursor;
    }

    function configureDrawingStyle() {
        if (!whiteboardCtx) return;

        whiteboardCtx.lineCap = 'round';
        whiteboardCtx.lineJoin = 'round';
        whiteboardCtx.lineWidth = currentSize;

        if (currentTool === 'eraser') {
            whiteboardCtx.globalCompositeOperation = 'destination-out';
            whiteboardCtx.lineWidth = currentSize * 2;
        } else {
            whiteboardCtx.globalCompositeOperation = 'source-over';
            whiteboardCtx.strokeStyle = currentColor;

            if (currentTool === 'marker') {
                whiteboardCtx.globalAlpha = 0.5;
            } else {
                whiteboardCtx.globalAlpha = 1.0;
            }
        }
    }

    function drawShape(startX, startY, endX, endY, shape) {
        if (!whiteboardCtx) return;

        whiteboardCtx.beginPath();
        whiteboardCtx.strokeStyle = currentColor;
        whiteboardCtx.lineWidth = currentSize;
        whiteboardCtx.globalCompositeOperation = 'source-over';
        whiteboardCtx.globalAlpha = 1.0;

        switch(shape) {
            case 'line':
                whiteboardCtx.moveTo(startX, startY);
                whiteboardCtx.lineTo(endX, endY);
                break;
            case 'arrow':
                drawArrow(startX, startY, endX, endY);
                break;
            case 'rectangle':
                whiteboardCtx.rect(startX, startY, endX - startX, endY - startY);
                break;
            case 'circle':
                var radius = Math.sqrt(Math.pow(endX - startX, 2) + Math.pow(endY - startY, 2));
                whiteboardCtx.arc(startX, startY, radius, 0, 2 * Math.PI);
                break;
        }
        whiteboardCtx.stroke();
    }

    function drawArrow(startX, startY, endX, endY) {
        var headlen = 10;
        var angle = Math.atan2(endY - startY, endX - startX);

        // Draw line
        whiteboardCtx.moveTo(startX, startY);
        whiteboardCtx.lineTo(endX, endY);

        // Draw arrowhead
        whiteboardCtx.lineTo(endX - headlen * Math.cos(angle - Math.PI / 6), endY - headlen * Math.sin(angle - Math.PI / 6));
        whiteboardCtx.moveTo(endX, endY);
        whiteboardCtx.lineTo(endX - headlen * Math.cos(angle + Math.PI / 6), endY - headlen * Math.sin(angle + Math.PI / 6));
    }

    function handleTextInput(x, y) {
        var textInput = prompt('Enter text:');
        if (textInput && whiteboardCtx) {
            whiteboardCtx.fillStyle = currentColor;
            whiteboardCtx.font = (currentSize * 4) + 'px Arial';
            whiteboardCtx.fillText(textInput, x, y);

            // Save state after text input
            saveWhiteboardState();

            // Send text event to other users
            if (streamRole === 'host') {
                sendWhiteboardTextEvent(textInput, x, y);
            }
        }
    }

    function handleLaserPointer(x, y) {
        if (!whiteboardCtx) return;

        // Draw laser pointer dot (red circle)
        whiteboardCtx.save();
        whiteboardCtx.fillStyle = '#ff0000';
        whiteboardCtx.globalAlpha = 0.8;
        whiteboardCtx.beginPath();
        whiteboardCtx.arc(x, y, 8, 0, 2 * Math.PI);
        whiteboardCtx.fill();
        whiteboardCtx.restore();

        // Send laser pointer event to other users
        sendWhiteboardEvent('laser', x, y);

        // Auto fade out after 3 seconds
        setTimeout(() => {
            whiteboardCtx.clearRect(x - 10, y - 10, 20, 20);
            // Redraw the area if needed
            var imageData = whiteboardCtx.getImageData(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);
            whiteboardCtx.putImageData(imageData, 0, 0);
        }, 3000);
    }

    // Global variable to store canvas state before shape preview
    var shapePreviewState = null;



    function saveWhiteboardState() {
        if (!whiteboardCanvas || currentPage < 0) return;

        // Get current page history
        var currentPageHistory = whiteboardPages[currentPage].history;
        var currentHistoryStep = whiteboardPages[currentPage].historyStep;

        currentHistoryStep++;
        if (currentHistoryStep < currentPageHistory.length) {
            currentPageHistory.length = currentHistoryStep;
        }
        currentPageHistory.push(whiteboardCanvas.toDataURL());

        // Limit history to 50 steps per page
        if (currentPageHistory.length > 50) {
            currentPageHistory.shift();
            currentHistoryStep--;
        }

        // Update page history step
        whiteboardPages[currentPage].historyStep = currentHistoryStep;
    }

    function undoWhiteboard() {
        if (currentPage < 0) return;

        var currentHistoryStep = whiteboardPages[currentPage].historyStep;
        if (currentHistoryStep > 0) {
            whiteboardPages[currentPage].historyStep--;
            restoreWhiteboardState();
        }
    }

    function redoWhiteboard() {
        if (currentPage < 0) return;

        var currentPageHistory = whiteboardPages[currentPage].history;
        var currentHistoryStep = whiteboardPages[currentPage].historyStep;

        if (currentHistoryStep < currentPageHistory.length - 1) {
            whiteboardPages[currentPage].historyStep++;
            restoreWhiteboardState();
        }
    }

    function restoreWhiteboardState() {
        if (!whiteboardCanvas || !whiteboardCtx || currentPage < 0) return;

        var currentPageHistory = whiteboardPages[currentPage].history;
        var currentHistoryStep = whiteboardPages[currentPage].historyStep;

        if (currentHistoryStep >= 0 && currentHistoryStep < currentPageHistory.length) {
            var img = new Image();
            img.onload = function() {
                whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);
                whiteboardCtx.drawImage(img, 0, 0);
            };
            img.src = currentPageHistory[currentHistoryStep];
        }
    }

    function savePage() {
        if (!whiteboardCanvas || currentPage < 0) return;
        whiteboardPages[currentPage].data = whiteboardCanvas.toDataURL();
    }

    function loadPage() {
        if (!whiteboardCanvas || !whiteboardCtx || currentPage < 0) return;

        whiteboardCtx.clearRect(0, 0, whiteboardCanvas.width, whiteboardCanvas.height);

        if (whiteboardPages[currentPage] && whiteboardPages[currentPage].data) {
            var img = new Image();
            img.onload = function() {
                whiteboardCtx.drawImage(img, 0, 0);

                // Initialize history with current state if empty
                initializePageHistory();
            };
            img.src = whiteboardPages[currentPage].data;
        } else {
            // Initialize history with empty state for new pages
            initializePageHistory();
        }
    }

    function initializePageHistory() {
        if (!whiteboardCanvas || currentPage < 0) return;

        // Initialize history if empty
        if (whiteboardPages[currentPage].history.length === 0) {
            // Save the current (empty) state as the first history entry
            whiteboardPages[currentPage].history.push(whiteboardCanvas.toDataURL());
            whiteboardPages[currentPage].historyStep = 0;

            console.log('[Whiteboard] Initialized history for page', currentPage + 1, 'with empty state');
        }
    }

    function updatePageInfo() {
        $('#currentPage').text(currentPage + 1);
        $('#totalPages').text(whiteboardPages.length);
    }

    function updateZoom() {
        if (!whiteboardCanvas) return;

        whiteboardCanvas.style.transform = `scale(${whiteboardZoom}) translate(${whiteboardPanX}px, ${whiteboardPanY}px)`;
        $('.zoom-level').text(Math.round(whiteboardZoom * 100) + '%');
    }

    function updateZoomInfo() {
        $('.zoom-level').text('100%');
    }

})(jQuery);
