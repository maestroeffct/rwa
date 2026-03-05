<?php


function getOthersPersonalizationSettings($key = null)
{
    return \App\Models\Setting::getOthersPersonalizationSettings($key);
}

function getInstallmentsSettings($key = null)
{
    return \App\Models\Setting::getInstallmentsSettings($key);
}

function getInstallmentsTermsSettings($key = null)
{
    return \App\Models\Setting::getInstallmentsTermsSettings($key);
}

function getRegistrationBonusSettings($key = null)
{
    return \App\Models\Setting::getRegistrationBonusSettings($key);
}

function getRegistrationBonusTermsSettings($key = null)
{
    return \App\Models\Setting::getRegistrationBonusTermsSettings($key);
}

function getStatisticsSettings($key = null)
{
    return \App\Models\Setting::getStatisticsSettings($key);
}


/**
 * @param null $page => Setting::$pagesSeoMetas
 * @return array [title, description]
 */
function getSeoMetas($page = null)
{
    return App\Models\Setting::getSeoMetas($page);
}

/**
 * @return array [title, image, link]
 */
function getSocials()
{
    return App\Models\Setting::getSocials();
}

/*
 * @return array [site_name, site_email, site_phone, site_language, register_method, user_languages, rtl_languages, fav_icon, locale, logo, footer_logo, rtl_layout, home hero1 is active, home hero2 is active, content_translate, default_time_zone, date_format, time_format]
 */
function getGeneralSettings($key = null)
{
    return App\Models\Setting::getGeneralSettings($key);
}

/**
 * @param null $key
 * $key => "agora_resolution" | "agora_max_bitrate" | "agora_min_bitrate" | "agora_frame_rate" | "agora_live_streaming" | "agora_chat" | "agora_cloud_rec" | "agora_in_free_courses"
 * "new_interactive_file" | "timezone_in_register" | "timezone_in_create_webinar"
 * "sequence_content_status" | "webinar_assignment_status" | "webinar_private_content_status" | "disable_view_content_after_user_register"
 * "direct_classes_payment_button_status" | "mobile_app_status" | "cookie_settings_status" | "show_other_register_method" | "show_certificate_additional_in_register"
 * @return
 * */
function getFeaturesSettings($key = null)
{
    return App\Models\Setting::getFeaturesSettings($key);
}

/**
 * @return bool
 */
function isFreeModeEnabled(): bool
{
    $freeMode = getFeaturesSettings('free_mode');
    return !empty($freeMode) && (bool)$freeMode;
}

/**
 * Determine if price should be shown while Free Mode is enabled
 */
function isFreeModeShowPriceEnabled(): bool
{
    $showPrice = getFeaturesSettings('free_mode_show_price');
    return !empty($showPrice) && (bool)$showPrice;
}

/**
 * Determine if cart icon should be shown while Free Mode is enabled
 */
function isFreeModeShowCartEnabled(): bool
{
    $showCart = getFeaturesSettings('free_mode_show_cart');
    return !empty($showCart) && (bool)$showCart;
}


function getSMSChannelsSettings($key = null)
{
    return App\Models\Setting::getSMSChannelsSettings($key);
}

/**
 * @param null $key
 * $key => cookie_settings_modal_message | cookie_settings_modal_items
 * @return
 * */
function getCookieSettings($key = null)
{
    return App\Models\Setting::getCookieSettings($key);
}


/**
 * @param $key
 * @return array|[commission, tax, minimum_payout, currency, currency_position, price_display]
 */
function getFinancialSettings($key = null)
{
    return App\Models\Setting::getFinancialSettings($key);
}

function getFinancialCurrencySettings($key = null)
{
    return App\Models\Setting::getFinancialCurrencySettings($key);
}

function getCommissionSettings($key = null)
{
    return App\Models\Setting::getCommissionSettings($key);
}

/**
 * @return array
 */
function getOfflineBankSettings($key = null)
{
    return App\Models\Setting::getOfflineBankSettings($key);
}

/**
 * @return array [status, users_affiliate_status, affiliate_user_commission, affiliate_user_amount, referred_user_amount, referral_description]
 */
function getReferralSettings()
{
    $settings = App\Models\Setting::getReferralSettings();

    if (empty($settings['status'])) {
        $settings['status'] = false;
    } else {
        $settings['status'] = true;
    }

    if (empty($settings['users_affiliate_status'])) {
        $settings['users_affiliate_status'] = false;
    } else {
        $settings['users_affiliate_status'] = true;
    }

    if (empty($settings['affiliate_user_commission'])) {
        $settings['affiliate_user_commission'] = 0;
    }

    if (empty($settings['affiliate_user_amount'])) {
        $settings['affiliate_user_amount'] = 0;
    }

    if (empty($settings['referred_user_amount'])) {
        $settings['referred_user_amount'] = 0;
    }

    if (empty($settings['referral_description'])) {
        $settings['referral_description'] = '';
    }

    return $settings;
}

function getReferralHowWorkSettings($key = null)
{
    return App\Models\Setting::getReferralHowWorkSettings($key);
}

/**
 * @return array
 */
function getOfflineBanksTitle()
{
    $titles = [];

    $banks = getOfflineBankSettings();

    if (!empty($banks) and count($banks)) {
        foreach ($banks as $bank) {
            $titles[] = $bank['title'] ?? "";
        }
    }

    return $titles;
}

/**
 * @return array
 */
function getReportReasons()
{
    return App\Models\Setting::getReportReasons();
}

/**
 * @param $template {String|nullable}
 * @return array
 */
function getNotificationTemplates($template = null)
{
    return App\Models\Setting::getNotificationTemplates($template);
}

/**
 * @param $key
 * @return array
 */
function getContactPageSettings($key = null)
{
    return App\Models\Setting::getContactPageSettings($key);
}

/**
 * @param $key
 * @return array
 */
function get404ErrorPageSettings($key = null)
{
    return App\Models\Setting::get404ErrorPageSettings($key);
}

/**
 * @param $key
 * @return array
 */
function get500ErrorPageSettings($key = null)
{
    return App\Models\Setting::get500ErrorPageSettings($key);
}

/**
 * @param $key
 * @return array
 */
function get419ErrorPageSettings($key = null)
{
    return App\Models\Setting::get419ErrorPageSettings($key);
}

/**
 * @param $key
 * @return array
 */
function get403ErrorPageSettings($key = null)
{
    return App\Models\Setting::get403ErrorPageSettings($key);
}


/**
 * @param $key
 * @return array
 */
function getNavbarLinks()
{
    // TODO:: The system locale is applied on the view side and when the data is created in the model this way, it is localeless. Therefore, this method is not used.
    $links = App\Models\Setting::getNavbarLinksSettings();

    if (!empty($links)) {
        usort($links, function ($item1, $item2) {
            return $item1['order'] <=> $item2['order'];
        });
    }

    return $links;
}

function handleNavbarLinks(\App\Models\Setting $setting)
{
    $links = [];

    if (!empty($setting->value) and isset($setting->value)) {
        $links = json_decode($setting->value, true);
    }

    if (!empty($links)) {
        usort($links, function ($item1, $item2) {
            return $item1['order'] <=> $item2['order'];
        });
    }

    return $links;
}

/**
 * @return array
 */
function getPanelSidebarSettings()
{
    return App\Models\Setting::getPanelSidebarSettings();
}

/**
 * @return array
 */
function getRewardProgramSettings()
{
    return App\Models\Setting::getRewardProgramSettings();
}

/**
 * @return array
 */
function getRewardsSettings()
{
    return App\Models\Setting::getRewardsSettings();
}

/**
 * @param $kay => [status, virtual_product_commission, physical_product_commission, store_tax,
 *                 possibility_create_virtual_product, possibility_create_physical_product,
 *                 shipping_tracking_url, activate_comments
 *              ]
 */
function getStoreSettings($key = null)
{
    return App\Models\Setting::getStoreSettings($key);
}

function getBecomeInstructorSectionSettings()
{
    return App\Models\Setting::getBecomeInstructorSectionSettings();
}

function getRegistrationPackagesGeneralSettings($key = null)
{
    return App\Models\Setting::getRegistrationPackagesGeneralSettings($key);
}

function getRegistrationPackagesInstructorsSettings($key = null)
{
    return App\Models\Setting::getRegistrationPackagesInstructorsSettings($key);
}

function getRegistrationPackagesOrganizationsSettings($key = null)
{
    return App\Models\Setting::getRegistrationPackagesOrganizationsSettings($key);
}

function getMobileAppSettings($key = null)
{
    return App\Models\Setting::getMobileAppSettings($key);
}

function getMaintenanceSettings($key = null)
{
    return App\Models\Setting::getMaintenanceSettings($key);
}

function getRestrictionSettings($key = null)
{
    return App\Models\Setting::getRestrictionSettings($key);
}

function getGeneralOptionsSettings($key = null)
{
    return App\Models\Setting::getGeneralOptionsSettings($key);
}

function getGiftsGeneralSettings($key = null)
{
    return App\Models\Setting::getGiftsGeneralSettings($key);
}

function getAiContentsSettingsName($key = null)
{
    return App\Models\Setting::getAiContentsSettingsName($key);
}

function getCertificateMainSettings($key = null)
{
    return App\Models\Setting::getCertificateMainSettings($key);
}

function getRemindersSettings($key = null)
{
    return App\Models\Setting::getRemindersSettings($key);
}

function getGeneralSecuritySettings($key = null)
{
    return App\Models\Setting::getGeneralSecuritySettings($key);
}

function getAbandonedCartSettings($key = null)
{
    return App\Models\Setting::getAbandonedCartSettings($key);
}

function getInstructorFinderSettings($key = null)
{
    return App\Models\Setting::getInstructorFinderSettings($key);
}

function getBecomeInstructorSettings($key = null)
{
    return App\Models\Setting::getBecomeInstructorSettings($key);
}

function getForumsHomepageSettings($key = null)
{
    return App\Models\Setting::getForumsHomepageSettings($key);
}

function getForumsHomepageRevolverSettings($key = null)
{
    return App\Models\Setting::getForumsHomepageRevolverSettings($key);
}


function getForumsCtaSectionSettings($key = null)
{
    return App\Models\Setting::getForumsCtaSectionSettings($key);
}

function getForumsGeneralSettings($key = null)
{
    return App\Models\Setting::getForumsGeneralSettings($key);
}

function getForumsImagesSettings($key = null)
{
    return App\Models\Setting::getForumsImagesSettings($key);
}

function getGuarantyTextSettings($key = null)
{
    return App\Models\Setting::getGuarantyTextSettings($key);
}

function getContentReviewInformationSettings($key = null)
{
    return App\Models\Setting::getContentReviewInformationSettings($key);
}

function getBlogFeaturedContentsSettings($key = null)
{
    return App\Models\Setting::getBlogFeaturedContentsSettings($key);
}

function getStoreFeaturedProductsSettings($key = null)
{
    return App\Models\Setting::getStoreFeaturedProductsSettings($key);
}

function getUserDashboardDataSettings($key = null)
{
    return App\Models\Setting::getUserDashboardDataSettings($key);
}

function getMobileAppGeneralSettings($key = null)
{
    return App\Models\Setting::getMobileAppGeneralSettings($key);
}

function getAttendanceSettings($key = null)
{
    return App\Models\Setting::getAttendanceSettings($key);
}

function getEventsSettings($key = null)
{
    return App\Models\Setting::getEventsSettings($key);
}

function getMeetingPackagesSettings($key = null)
{
    return App\Models\Setting::getMeetingPackagesSettings($key);
}
