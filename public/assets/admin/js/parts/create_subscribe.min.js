/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************************!*\
  !*** ./resources/js/admin/parts/create_subscribe.js ***!
  \******************************************************/
(function ($) {
  "use strict";

  $('body').on('change', '.js-target-types-input', function () {
    var value = $(this).val();
    var $targets = $('.js-select-target-field');
    $targets.find('select').val("");
    $targets.addClass('d-none');
    $targets.find('.js-target-option').addClass('d-none');
    if (value && value !== 'all' && value !== 'recharge_wallet') {
      $targets.removeClass('d-none');
      $targets.find(".js-target-option-".concat(value)).removeClass('d-none');
    }
    handleSpecificItemsShow();
  });
  $('body').on('change', '.js-target-input', function () {
    var value = $(this).val();
    var targetType = $('.js-target-types-input').val();
    handleSpecificItemsShow(value, targetType);
  });
  function handleSpecificItemsShow() {
    var target = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var targetType = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    var $specificCategoriesField = $('.js-specific-categories-field');
    var $specificInstructorsField = $('.js-specific-instructors-field');
    var $specificCoursesField = $('.js-specific-courses-field');
    var $specificBundlesField = $('.js-specific-bundles-field');
    $specificCategoriesField.addClass('d-none');
    $specificInstructorsField.addClass('d-none');
    $specificCoursesField.addClass('d-none');
    $specificBundlesField.addClass('d-none');
    if (target === "specific_categories") {
      $specificCategoriesField.removeClass('d-none');
    } else if (target === "specific_instructors") {
      $specificInstructorsField.removeClass('d-none');
    } else if (target === "specific_courses") {
      $specificCoursesField.removeClass('d-none');
    } else if (target === "specific_bundles") {
      $specificBundlesField.removeClass('d-none');
    }
  }
})(jQuery);
/******/ })()
;