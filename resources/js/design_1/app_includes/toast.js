require('jquery-toast-plugin/dist/jquery.toast.min');

// Toast container for notifications
let toastContainer = document.querySelector('.toast-container');

// Create toast container if it doesn't exist
if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);
}

function makeCustomHtml(toastId, type, title, message) {
    let bgColor = successColor; // success (green)

    switch (type) {
        case 'success':
            bgColor = successColor; // success (green)
            break;
        case 'warning':
            bgColor = warningColor; // warning (amber)
            break;
        case 'danger':
        case 'error':
            bgColor = dangerColor; // danger (red)
            break;
        case 'info':
            bgColor = infoColor; // info (blue)
            break;
    }

    // Create icons using SVG with fill
    let iconHtml = '';

    switch (type) {
        case 'success':
            iconHtml = `<div class="toast-icon-inner success-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                </svg>
            </div>`;
            break;
        case 'warning':
            iconHtml = `<div class="toast-icon-inner warning-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5.99L19.53 19H4.47L12 5.99M12 2L1 21h22L12 2zm1 14h-2v2h2v-2zm0-6h-2v4h2v-4z"/>
                </svg>
            </div>`;
            break;
        case 'danger':
        case 'error':
            iconHtml = `<div class="toast-icon-inner danger-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </div>`;
            break;
        case 'info':
            iconHtml = `<div class="toast-icon-inner info-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
            </div>`;
            break;
    }

    // Create toast HTML
    return `
        <div id="${toastId}" class="toast-notification ${type}" style="background-color: ${bgColor}; opacity: 0;">
            <div class="toast-icon">
                ${iconHtml}
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
            <div class="toast-divider"></div>
            <div class="toast-progress"></div>
        </div>
    `;
}


/**
 * Display a toast notification
 * @param {string} type - Type of toast: 'success', 'warning', 'danger', 'info'
 * @param {string} title - Title text for the toast
 * @param {string} message - Message text for the toast
 * @param {number} duration - Duration in milliseconds (default: 10000)
 * @param {string} customId - Optional custom ID for the toast
 */
window.showToast = function (type, title, message, duration = 10000, customId = null) {
    // Create unique ID for the toast
    const toastId = customId || 'toast-' + Math.random().toString(36).substr(2, 9);

    // Make sure container exists
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }

    const toastHTML = makeCustomHtml(toastId, type, title, message)

    // Add toast to container
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    // Get the toast element
    const toast = document.getElementById(toastId);

    // Fade in the toast
    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s ease';
        toast.style.opacity = '1';
    }, 100);

    // Add progress bar animation
    const progressBar = toast.querySelector('.toast-progress');
    if (progressBar) {
        progressBar.style.width = '100%';

        setTimeout(() => {
            progressBar.style.transition = `width ${duration}ms linear`;
            progressBar.style.width = '0';
        }, 300);
    }

    // Auto-hide toast after duration
    setTimeout(() => {
        toast.style.opacity = '0';

        // Remove toast after animation completes
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, duration);

    // Close toast on button click
    const closeBtn = toast.querySelector('.toast-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            toast.style.opacity = '0';

            // Remove toast after animation completes
            setTimeout(() => {
                toast.remove();
            }, 500);
        });
    }

    // Return the toast ID for potential reference
    return toastId;
};


// Shorthand functions for each toast type
window.successToast = function (title, message, duration) {
    return window.showToast('success', title, message, duration);
};

window.warningToast = function (title, message, duration) {
    return window.showToast('warning', title, message, duration);
};

window.dangerToast = function (title, message, duration) {
    return window.showToast('danger', title, message, duration);
};

window.errorToast = function (title, message, duration) {
    return window.showToast('danger', title, message, duration);
};

window.infoToast = function (title, message, duration) {
    return window.showToast('info', title, message, duration);
};

// jQuery Toast plugin compatibility layer
(function ($) {
    "use strict";

    // Override the jQuery toast plugin with our custom implementation
    $.toast = function (options) {
        // Extract options
        const heading = options.heading || '';
        const text = options.text || '';
        const icon = options.icon || 'info';
        const hideAfter = options.hideAfter || 10000;
        const showHideTransition = options.showHideTransition || 'fade';
        const allowToastClose = options.allowToastClose !== false;
        const stack = options.stack !== false;

        // Map jQuery toast icon to our toast types
        let type = 'info';
        switch (icon) {
            case 'success':
                type = 'success';
                break;
            case 'warning':
                type = 'warning';
                break;
            case 'error':
                type = 'danger';
                break;
            case 'info':
                type = 'info';
                break;
        }

        // Call our custom toast function
        if (window.showToast) {
            return window.showToast(type, heading, text, hideAfter);
        }

    };

    // Add reset method for compatibility
    $.toast.reset = function () {
        // Clear all toasts
        const toastContainer = document.querySelector('.toast-container');
        if (toastContainer) {
            toastContainer.innerHTML = '';
        }

        // Clear stored toasts
        localStorage.removeItem('pendingToasts');
    };

})(jQuery);

