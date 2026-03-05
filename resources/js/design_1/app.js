// Load jQuery
window.$ = window.jQuery = require('jquery');
require('bootstrap');

// plugins
window.Swal = require('sweetalert2/dist/sweetalert2')
require('select2/dist/js/select2');
window.tippy = require('tippy.js').default;


const style = getComputedStyle(document.body);
window.primaryColor = style.getPropertyValue('--primary');
window.secondaryColor = style.getPropertyValue('--secondary');
window.infoColor = style.getPropertyValue('--info');
window.warningColor = style.getPropertyValue('--warning');
window.dangerColor = style.getPropertyValue('--danger');
window.successColor = style.getPropertyValue('--success');

// parts
require('./app_includes/toast');
require('./app_includes/ajax_setup');
require('./app_includes/captcha');
require('./app_includes/make-summernote');
require('./app_includes/icon_picker');
require('./app_includes/main');
require('./app_includes/theme');
require('./app_includes/cart_drawer');
require('./app_includes/chart_init');
require('./app_includes/video-source');
require('./app_includes/create-media');
require('./app_includes/newsletter');
require('./app_includes/plyrio');
require('./app_includes/meeting_packages_public');
