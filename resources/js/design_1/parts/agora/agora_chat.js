import RtmClient from './vendor/rtm-client';

(function ($) {
    "use strict"

    var $sidebarChatCards = $('#sidebarChatCards');


    function chatItemHtml(message, memberName, date, isOwn) {
        const chatType = isOwn ? 'own-chat' : 'others-chat';

        return `<div class="chat-card ${chatType} mb-24">
                <div class="chat-user-info d-flex align-items-center gap-8 font-12">
                    <span class="font-weight-bold text-black">${memberName}</span>
                    <span class="dot"></span>
                    <span class="text-gray-500">${date}</span>
                </div>

                <div class="chat-message mt-8 p-16">${message}</div>
            </div>`
    }

    async function userJoinedHtml(username) {
        const uid = username.replaceAll('user ', '');
        const userInfo = await getUserInfo(uid);

        return `<div class="card-with-mask position-relative mb-24">
            <div class="mask-8-white border-gray-200"></div>
            <div class="position-relative z-index-2 d-flex align-items-center bg-white rounded-16 p-16 border-gray-200 ">
                <div class="size-40 rounded-circle">
                    <img src="${userInfo.avatar}" alt="" class="img-cover rounded-circle">
                </div>
                <div class="ml-8">
                    <h6 class="font-14 text-black">${userInfo.full_name}</h6>
                    <div class="mt-2 font-12 text-gray-500">${joinedLiveSessionLang}</div>
                </div>
            </div>
        </div>`
    }

    function handleLogin(rtm, callback) {
        if (rtm._logined) {
            return false;
        }

        try {
            rtm.init(appId);

            window.rtm = rtm;

            rtm.login(accountName, rtmToken).then(() => {
                console.log('##########################')
                console.log('chat login');

                rtm._logined = true;

                callback();
            }).catch((err) => {
                console.log(err);
            });
        } catch (err) {
            console.error(err);
        }
    }

    function handleJoinToChannel(rtm, callback) {

        if (!rtm._logined) {
            return false;
        }

        rtm.joinChannel(channelName).then(() => {

            userJoinedHtml(rtm.accountName).then(html => {
                $sidebarChatCards.append(html);

                updateChatViewScroll();

                rtm.channels[channelName].joined = true;

                callback();
            }).catch((err) => {
                console.error(err);
            });
        }).catch((err) => {
            console.error(err);
        });
    }

    $(() => {
        const rtm = new RtmClient();

        // login user by token
        handleLogin(rtm, function () {

            // join to channel
            handleJoinToChannel(rtm, function () {
                //agoraLoading.addClass('d-none');

                rtm.on('MemberJoined', ({channelName, args}) => {
                    const memberId = args[0];

                    userJoinedHtml(memberId).then(html => {
                        $sidebarChatCards.append(html);

                        updateChatViewScroll();
                    }).catch((err) => {
                        console.error(err);
                    });

                    // Also add to Agora users list for single mode
                    if (sessionStreamType === 'single') {
                        const uid = parseInt(memberId.replace('user ', ''));
                        if (!isNaN(uid) && typeof window.addUserToJoinedList === 'function') {
                            window.addUserToJoinedList(uid, uid === authUserId);
                        }
                    }
                });

                rtm.on('MemberLeft', ({channelName, args}) => {
                    const memberId = args[0];

                    // Remove from Agora users list for single mode
                    if (sessionStreamType === 'single') {
                        const uid = parseInt(memberId.replace('user ', ''));
                        if (!isNaN(uid) && typeof window.removeUserFromJoinedList === 'function') {
                            window.removeUserFromJoinedList(uid);
                        }
                    }
                });

                rtm.on('ChannelMessage', ({channelName, args}) => {
                    afterChannelMessage(args)
                });
            });
        });
    });

    async function afterChannelMessage([message, memberId, other]) {
        const userId = memberId.replaceAll('user ', '');
        const date = new Date(other.serverReceivedTs).toLocaleTimeString();

        // Check if this is a special message (JSON format)
        try {
            const messageData = JSON.parse(message.text);
            
            if (messageData.type === 'session_ended') {
                // Handle session end
                handleSessionEnded();
                return;
            } else if (messageData.type === 'whiteboard_draw') {
                // Handle whiteboard drawing events
                handleWhiteboardDrawMessage(messageData);
                return;
            } else if (messageData.type === 'whiteboard_shape') {
                // Handle whiteboard shape events
                handleWhiteboardShapeMessage(messageData);
                return;
            } else if (messageData.type === 'whiteboard_history') {
                // Handle whiteboard history events (undo/redo)
                handleWhiteboardHistoryMessage(messageData);
                return;
            } else if (messageData.type === 'whiteboard_text') {
                // Handle whiteboard text events
                handleWhiteboardTextMessage(messageData);
                return;
            } else if (messageData.type === 'whiteboard_page') {
                // Handle whiteboard page events
                handleWhiteboardPageMessage(messageData);
                return;
            } else if (messageData.type === 'whiteboard_toggle') {
                // Handle whiteboard show/hide events
                handleWhiteboardToggleMessage(messageData);
                return;
            } else if (messageData.type && messageData.type.startsWith('whiteboard_')) {
                // Any other whiteboard event - don't show in chat
                console.log('[Whiteboard RTM] Unknown whiteboard event:', messageData.type);
                return;
            }
            
            // If it's a JSON message but not a whiteboard event, also don't show in chat
            // unless it's a regular chat message without type
            if (messageData.type) {
                console.log('[Chat RTM] Unknown message type:', messageData.type);
                return;
            }
        } catch (e) {
            // Not a JSON message, treat as regular chat
        }

        const userInfo = await getUserInfo(userId);

        $sidebarChatCards.append(chatItemHtml(message.text, userInfo.full_name, date, false));

        updateChatViewScroll();
    }
    
    function handleWhiteboardDrawMessage(data) {
        // Only process whiteboard drawing events if user is not the host (avoid self-sync)
        if (streamRole === 'host') return;
        
        // Call the whiteboard handler from the main script
        if (typeof window.agoraWhiteboard !== 'undefined' && 
            typeof window.agoraWhiteboard.handleRemoteEvent === 'function') {
            window.agoraWhiteboard.handleRemoteEvent(data);
        } else if (typeof window.handleWhiteboardEvent === 'function') {
            window.handleWhiteboardEvent(data);
        }
        
        console.log('[Whiteboard RTM] Received drawing event:', data.action);
    }
    
    function handleWhiteboardShapeMessage(data) {
        // Only process whiteboard shape events if user is not the host (avoid self-sync)
        if (streamRole === 'host') return;
        
        // Call the whiteboard shape handler from the main script
        if (typeof window.handleWhiteboardShapeEvent === 'function') {
            window.handleWhiteboardShapeEvent(data);
        }
        
        console.log('[Whiteboard RTM] Received shape event:', data.shape);
    }
    
    function handleWhiteboardHistoryMessage(data) {
        // Only process whiteboard history events if user is not the host (avoid self-sync)
        if (streamRole === 'host') return;
        
        // Call the whiteboard history handler from the main script
        if (typeof window.handleWhiteboardHistoryEvent === 'function') {
            window.handleWhiteboardHistoryEvent(data);
        }
        
        console.log('[Whiteboard RTM] Received history event:', data.action);
    }
    
    function handleWhiteboardTextMessage(data) {
        // Only process whiteboard text events if user is not the host (avoid self-sync)
        if (streamRole === 'host') return;
        
        // Call the whiteboard text handler from the main script
        if (typeof window.handleWhiteboardTextEvent === 'function') {
            window.handleWhiteboardTextEvent(data);
        }
        
        console.log('[Whiteboard RTM] Received text event:', data.text);
    }
    
    function handleWhiteboardPageMessage(data) {
        // Only process whiteboard page events if user is not the host (avoid self-sync)
        if (streamRole === 'host') return;
        
        // Call the whiteboard page handler from the main script
        if (typeof window.handleWhiteboardPageEvent === 'function') {
            window.handleWhiteboardPageEvent(data);
        }
        
        console.log('[Whiteboard RTM] Received page event:', data.action, data.pageNumber);
    }
    
    function handleWhiteboardToggleMessage(data) {
        // Only process whiteboard toggle events if user is not the host (avoid self-sync)
        if (streamRole === 'host') return;
        
        console.log('[Whiteboard RTM] Received toggle event:', data.action);
        
        if (data.action === 'show') {
            // Show whiteboard for students
            $('#whiteboardContainer').removeClass('d-none');
            $('#hostLiveStream').addClass('d-none');
            
            // Initialize canvas if not done already
            if (typeof window.agoraWhiteboard !== 'undefined' && 
                typeof window.agoraWhiteboard.initForStudent === 'function') {
                window.agoraWhiteboard.initForStudent();
            }
            
            console.log('[Student] Whiteboard shown');
        } else if (data.action === 'hide') {
            // Hide whiteboard for students
            $('#whiteboardContainer').addClass('d-none');
            $('#hostLiveStream').removeClass('d-none');
            
            console.log('[Student] Whiteboard hidden');
        }
    }

    function handleSessionEnded() {
        console.log('[RTM] Received session ended message');
        
        // Immediately redirect without any notifications
        if (typeof window.agoraLeave === 'function') {
            window.agoraLeave();
        } else {
            // Fallback redirect
            window.location.href = redirectAfterLeave || '/panel';
        }
    }

    function updateChatViewScroll() {
        const $sidebarChatCards = $('.sidebar-chats-cards');

        $sidebarChatCards.scrollTop($sidebarChatCards[0].scrollHeight);
    }

    function sendMessage() {
        if (!rtm._logined) {
            //alert('Please Login First');
            return false;
        }

        const messageInput = $('#chatMessageInput');
        const message = messageInput.val();

        if (message && message !== '') {
            rtm.sendChannelMessage(message, channelName).then(() => {
                const date = new Date().toLocaleTimeString();

                $sidebarChatCards.append(chatItemHtml(message, userName, date, true));

                updateChatViewScroll();

                messageInput.val('');
            }).catch((err) => {
                console.error(err);
            });
        }
    }

    $('body').on('click', '.js-send-message-btn', function (e) {
        e.preventDefault();

        sendMessage();
    });

})(jQuery)