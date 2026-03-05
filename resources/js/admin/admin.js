
const style = getComputedStyle(document.body);
window.primaryColor = style.getPropertyValue('--primary');
window.secondaryColor = style.getPropertyValue('--secondary');
window.infoColor = style.getPropertyValue('--info');
window.warningColor = style.getPropertyValue('--warning');
window.dangerColor = style.getPropertyValue('--danger');
window.successColor = style.getPropertyValue('--success');

window.Swal = require('sweetalert2/dist/sweetalert2')

// Includes
require('./../design_1/app_includes/toast');
require('./../design_1/app_includes/ajax_setup');
require('./../design_1/app_includes/icon_picker');
require('./../design_1/app_includes/main');
require('./main_includes/main')
