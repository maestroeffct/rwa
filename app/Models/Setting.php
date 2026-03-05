<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Setting extends Model implements TranslatableContract
{
    use Translatable;

    //TODO:: To use the site settings, please use the functions of the helper.php file.
    // If you have created new settings, please use the same structure to optimize
    // the number of requests to the database, because this system does not use cache.

    protected $table = 'settings';
    public $timestamps = false;
    protected $guarded = ['id'];


    public $translatedAttributes = ['value'];

    public function getValueAttribute()
    {
        return getTranslateAttributeValue($this, 'value');
    }

    // The result is stored in these variables
    // If you use each function more than once per page, the database will be requested only once.
    static $seoMetas, $socials,
        $general, $features,
        $financial, $offlineBanks, $referral, $referralHowWork, $currencySettings,
        $reportReasons, $notificationTemplates,
        $contactPage, $Error404Page, $Error500Page, $Error419Page, $Error403Page, $navbarLink, $panelSidebar, $findInstructors, $rewardProgram, $rewardsSettings, $storeSettings,
        $registrationPackagesGeneral, $registrationPackagesInstructors, $registrationPackagesOrganizations, $becomeInstructorSection,
        $cookieSettings, $mobileAppSettings, $remindersSettings, $generalSecuritySettings, $advertisingModal,
        $othersPersonalization, $installmentsSettings, $installmentsTermsSettings, $registrationBonusSettings, $registrationBonusTermsSettings, $statisticsSettings,
        $maintenanceSettings, $restrictionSettings, $generalOptions, $giftsGeneralSettings, $aiContentsSettings, $certificateSettings, $abandonedCartSettings, $smsChannelsSettings,
        $commissionSettings, $instructorFinderSettings, $becomeInstructorSettings, $forumsHomepageSettings, $forumsHomepageRevolverSettings, $forumsCtaSectionSettings, $forumsGeneralSettings, $forumsImagesSettings, $guarantyTextSettings, $contentReviewInformationSettings, $blogFeaturedContentsSettings,
        $storeFeaturedProductsSettings, $userDashboardDataSettings, $mobileAppGeneralSettings, $attendanceSettings, $eventsSettings, $meetingPackagesSettings;

    // settings name , Using these keys, values are taken from the settings table
    static $seoMetasName = 'seo_metas';
    static $socialsName = 'socials';
    static $generalName = 'general';
    static $featuresName = 'features';
    static $financialName = 'financial';
    static $offlineBanksName = 'offline_banks';
    static $referralName = 'referral';
    static $referralHowWorkName = 'referral_how_work';
    static $currencySettingsName = 'currency_settings';
    static $commissionSettingsName = 'commission_settings';
    static $guarantyTextSettingsName = 'guaranty_text';
    static $contentReviewInformationSettingsName = 'content_review_information';
    static $reportReasonsName = 'report_reasons';
    static $notificationTemplatesName = 'notifications';
    static $contactPageName = 'contact_us';
    static $Error404PageName = '404';
    static $Error500PageName = '500';
    static $Error419PageName = '419';
    static $Error403PageName = '403';
    static $navbarLinkName = 'navbar_links';
    static $panelSidebarName = 'panel_sidebar';
    static $findInstructorsName = 'find_instructors';
    static $rewardProgramName = 'reward_program';
    static $rewardsSettingsName = 'rewards_settings';
    static $storeSettingsName = 'store_settings';
    static $registrationPackagesGeneralName = 'registration_packages_general';
    static $registrationPackagesInstructorsName = 'registration_packages_instructors';
    static $registrationPackagesOrganizationsName = 'registration_packages_organizations';
    static $becomeInstructorSectionName = 'become_instructor_section';
    static $cookieSettingsName = 'cookie_settings';
    static $mobileAppSettingsName = 'mobile_app';
    static $remindersSettingsName = 'reminders';
    static $generalSecuritySettingsName = 'security';
    static $advertisingModalName = 'advertising_modal';
    static $othersPersonalizationName = 'others_personalization';
    static $installmentsSettingsName = 'installments_settings';
    static $installmentsTermsSettingsName = 'installments_terms_settings';
    static $registrationBonusSettingsName = 'registration_bonus_settings';
    static $registrationBonusTermsSettingsName = 'registration_bonus_terms_settings';
    static $statisticsSettingsName = 'statistics';
    static $maintenanceSettingsName = 'maintenance_settings';
    static $restrictionSettingsName = 'restriction_settings';
    static $generalOptionsName = 'general_options';
    static $giftsGeneralSettingsName = 'gifts_general_settings';
    static $aiContentsSettingsName = 'ai_contents_settings';
    static $abandonedCartSettingsName = 'abandoned_cart_settings';
    static $certificateSettingsName = 'certificate_settings';
    static $smsChannelsSettingName = 'sms_channels';
    static $instructorFinderSettingsName = 'instructor_finder_settings';
    static $becomeInstructorSettingsName = 'become_instructor_settings';

    static $forumsHomepageSettingsName = 'forum_homepage_settings';
    static $forumsGeneralSettingsName = 'forum_general_settings';
    static $forumsImagesSettingsName = 'forum_images_settings';
    static $forumsHomepageRevolverSettingsName = 'forum_homepage_revolver_settings';
    static $forumsCtaSectionSettingsName = 'forum_cta_section_settings';
    static $blogFeaturedContentsSettingsName = 'blog_featured_contents_settings';
    static $storeFeaturedProductsSettingsName = 'store_featured_products_settings';
    static $userDashboardDataSettingsName = 'user_dashboard_data';
    static $mobileAppGeneralSettingsName = 'mobile_app_general_settings';
    static $attendanceSettingsName = 'attendances_settings';
    static $eventsSettingsName = 'events_settings';
    static $meetingPackagesSettingsName = 'meeting_packages_settings';

    //statics
    static $pagesSeoMetas = ['home', 'search', 'tags', 'categories', 'classes', 'login', 'register', 'contact', 'blog', 'certificate_validation',
        'instructors', 'organizations', 'instructor_finder_wizard', 'instructor_finder', 'reward_courses', 'products_lists', 'reward_products',
        'forum', 'upcoming_courses_lists', 'bundles_lists', 'event_ticket_validation', 'events_lists', 'meeting_packages_lists',
    ];
    static $mainSettingSections = ['general', 'financial', 'payment'];
    static $mainSettingPages = ['general', 'financial', 'personalization', 'notifications', 'seo', 'customization', 'other'];

    static $defaultSettingsLocale = 'en'; // Because the settings table uses translation and some settings do not need to be translated, so we save them with a default locale


    static function getSettingsWithDefaultLocal(): array
    {
        return [
            self::$seoMetasName,
            self::$socialsName,
            self::$generalName,
            self::$financialName,
            self::$offlineBanksName,
            self::$referralName,
            self::$notificationTemplatesName,
            self::$contactPageName,
        ];
    }

    // functions
    static function getSetting(&$static, $name, $key = null)
    {
        if (!isset($static)) {
            $static = cache()->remember('settings.' . $name, 24 * 60 * 60, function () use ($name) {
                return self::where('name', $name)->first();
            });
        }

        $value = [];

        if (!empty($static) and !empty($static->value) and isset($static->value)) {
            $value = json_decode($static->value, true);
        }

        if (!empty($value) and !empty($key)) {
            if (isset($value[$key])) {
                return $value[$key];
            } else {
                return null;
            }
        }

        if (!empty($key) and (empty($value) or count($value) < 1)) {
            return '';
        }

        return $value;
    }

    /**
     * @param null $page => home, search, categories, login, register, about, contact
     * @return array => [title, description]
     */
    static function getSeoMetas($page = null)
    {
        return self::getSetting(self::$seoMetas, self::$seoMetasName, $page);
    }

    /**
     * @return array [title, image, link]
     */
    static function getSocials()
    {
        return self::getSetting(self::$socials, self::$socialsName);
    }


    /**
     * @return array [site_name, site_email, site_language, user_languages, rtl_languages, fav_icon, logo, footer_logo, rtl_layout, home hero1 is active, home hero2 is active, content_translate ]
     */
    static function getGeneralSettings($key = null)
    {
        return self::getSetting(self::$general, self::$generalName, $key);
    }

    /**
     * @return array []
     */
    static function getFeaturesSettings($key = null)
    {
        return self::getSetting(self::$features, self::$featuresName, $key);
    }

    /**
     * @return array []
     */
    static function getCookieSettings($key = null)
    {
        return self::getSetting(self::$cookieSettings, self::$cookieSettingsName, $key);
    }


    /**
     * @param $key
     * @return array|[commission, tax, minimum_payout, currency]
     */
    static function getFinancialSettings($key = null)
    {
        return self::getSetting(self::$financial, self::$financialName, $key);
    }

    /**
     * @param $key
     *
     * @return array|string
     */
    static function getFinancialCurrencySettings($key = null)
    {
        return self::getSetting(self::$currencySettings, self::$currencySettingsName, $key);
    }

    /**
     * @param $key
     *
     * @return array|string
     */
    static function getCommissionSettings($key = null)
    {
        return self::getSetting(self::$commissionSettings, self::$commissionSettingsName, $key);
    }


    /**
     * @return array
     */
    static function getReportReasons()
    {
        return self::getSetting(self::$reportReasons, self::$reportReasonsName);
    }

    /**
     * @param $template {String|nullable}
     * @return array
     */
    static function getNotificationTemplates($template = null)
    {
        return self::getSetting(self::$notificationTemplates, self::$notificationTemplatesName, $template);
    }

    /**
     * @return array
     */
    static function getOfflineBankSettings($key = null)
    {
        return self::getSetting(self::$offlineBanks, self::$offlineBanksName, $key);
    }

    /**
     * @return array
     */
    static function getReferralSettings()
    {
        return self::getSetting(self::$referral, self::$referralName);
    }

    /**
     * @return array
     */
    static function getReferralHowWorkSettings($key = null)
    {
        return self::getSetting(self::$referralHowWork, self::$referralHowWorkName, $key);
    }

    /**
     * @param $key
     * @return array
     */
    static function getContactPageSettings($key = null)
    {
        return self::getSetting(self::$contactPage, self::$contactPageName, $key);
    }

    /**
     * @param $key
     * @return array
     */
    static function get404ErrorPageSettings($key = null)
    {
        return self::getSetting(self::$Error404Page, self::$Error404PageName, $key);
    }

    /**
     * @param $key
     * @return array
     */
    static function get500ErrorPageSettings($key = null)
    {
        return self::getSetting(self::$Error500Page, self::$Error500PageName, $key);
    }

    /**
     * @param $key
     * @return array
     */
    static function get419ErrorPageSettings($key = null)
    {
        return self::getSetting(self::$Error419Page, self::$Error419PageName, $key);
    }

    /**
     * @param $key
     * @return array
     */
    static function get403ErrorPageSettings($key = null)
    {
        return self::getSetting(self::$Error403Page, self::$Error403PageName, $key);
    }


    /**
     * @param $key
     * @return array
     */
    static function getNavbarLinksSettings($key = null)
    {
        return self::getSetting(self::$navbarLink, self::$navbarLinkName, $key);
    }

    /**
     * @return array
     */
    static function getPanelSidebarSettings()
    {
        return self::getSetting(self::$panelSidebar, self::$panelSidebarName);
    }

    /**
     * @return array
     */
    static function getFindInstructorsSettings()
    {
        return self::getSetting(self::$findInstructors, self::$findInstructorsName);
    }

    /**
     * @return array
     */
    static function getRewardProgramSettings()
    {
        return self::getSetting(self::$rewardProgram, self::$rewardProgramName);
    }

    /**
     * @return array
     */
    static function getRewardsSettings()
    {
        return self::getSetting(self::$rewardsSettings, self::$rewardsSettingsName);
    }

    /**
     * @return array
     */
    static function getStoreSettings($key = null)
    {
        return self::getSetting(self::$storeSettings, self::$storeSettingsName, $key);
    }

    static function getBecomeInstructorSectionSettings()
    {
        return self::getSetting(self::$becomeInstructorSection, self::$becomeInstructorSectionName);
    }

    static function getRegistrationPackagesGeneralSettings($key = null)
    {
        return self::getSetting(self::$registrationPackagesGeneral, self::$registrationPackagesGeneralName, $key);
    }

    static function getRegistrationPackagesInstructorsSettings($key = null)
    {
        return self::getSetting(self::$registrationPackagesInstructors, self::$registrationPackagesInstructorsName, $key);
    }

    static function getRegistrationPackagesOrganizationsSettings($key = null)
    {
        return self::getSetting(self::$registrationPackagesOrganizations, self::$registrationPackagesOrganizationsName, $key);
    }

    static function getMobileAppSettings($key = null)
    {
        return self::getSetting(self::$mobileAppSettings, self::$mobileAppSettingsName, $key);
    }

    static function getRemindersSettings($key = null)
    {
        return self::getSetting(self::$remindersSettings, self::$remindersSettingsName, $key);
    }

    static function getGeneralSecuritySettings($key = null)
    {
        return self::getSetting(self::$generalSecuritySettings, self::$generalSecuritySettingsName, $key);
    }

    static function getAdvertisingModalSettings($key = null)
    {
        return self::getSetting(self::$advertisingModal, self::$advertisingModalName, $key);
    }

    static function getOthersPersonalizationSettings($key = null)
    {
        return self::getSetting(self::$othersPersonalization, self::$othersPersonalizationName, $key);
    }

    static function getInstallmentsSettings($key = null)
    {
        return self::getSetting(self::$installmentsSettings, self::$installmentsSettingsName, $key);
    }

    static function getInstallmentsTermsSettings($key = null)
    {
        return self::getSetting(self::$installmentsTermsSettings, self::$installmentsTermsSettingsName, $key);
    }

    static function getRegistrationBonusSettings($key = null)
    {
        return self::getSetting(self::$registrationBonusSettings, self::$registrationBonusSettingsName, $key);
    }

    static function getRegistrationBonusTermsSettings($key = null)
    {
        return self::getSetting(self::$registrationBonusTermsSettings, self::$registrationBonusTermsSettingsName, $key);
    }

    static function getStatisticsSettings($key = null)
    {
        return self::getSetting(self::$statisticsSettings, self::$statisticsSettingsName, $key);
    }

    static function getMaintenanceSettings($key = null)
    {
        return self::getSetting(self::$maintenanceSettings, self::$maintenanceSettingsName, $key);
    }

    static function getRestrictionSettings($key = null)
    {
        return self::getSetting(self::$restrictionSettings, self::$restrictionSettingsName, $key);
    }

    static function getGeneralOptionsSettings($key = null)
    {
        return self::getSetting(self::$generalOptions, self::$generalOptionsName, $key);
    }

    static function getSMSChannelsSettings($key = null)
    {
        return self::getSetting(self::$smsChannelsSettings, self::$smsChannelsSettingName, $key);
    }

    static function getGiftsGeneralSettings($key = null)
    {
        return self::getSetting(self::$giftsGeneralSettings, self::$giftsGeneralSettingsName, $key);
    }

    static function getAiContentsSettingsName($key = null)
    {
        return self::getSetting(self::$aiContentsSettings, self::$aiContentsSettingsName, $key);
    }

    static function getCertificateMainSettings($key = null)
    {
        return self::getSetting(self::$certificateSettings, self::$certificateSettingsName, $key);
    }

    static function getAbandonedCartSettings($key = null)
    {
        return self::getSetting(self::$abandonedCartSettings, self::$abandonedCartSettingsName, $key);
    }

    static function getInstructorFinderSettings($key = null)
    {
        return self::getSetting(self::$instructorFinderSettings, self::$instructorFinderSettingsName, $key);
    }

    static function getBecomeInstructorSettings($key = null)
    {
        return self::getSetting(self::$becomeInstructorSettings, self::$becomeInstructorSettingsName, $key);
    }

    static function getForumsHomepageSettings($key = null)
    {
        return self::getSetting(self::$forumsHomepageSettings, self::$forumsHomepageSettingsName, $key);
    }

    static function getForumsHomepageRevolverSettings($key = null)
    {
        return self::getSetting(self::$forumsHomepageRevolverSettings, self::$forumsHomepageRevolverSettingsName, $key);
    }

    static function getForumsCtaSectionSettings($key = null)
    {
        return self::getSetting(self::$forumsCtaSectionSettings, self::$forumsCtaSectionSettingsName, $key);
    }

    static function getForumsGeneralSettings($key = null)
    {
        return self::getSetting(self::$forumsGeneralSettings, self::$forumsGeneralSettingsName, $key);
    }

    static function getForumsImagesSettings($key = null)
    {
        return self::getSetting(self::$forumsImagesSettings, self::$forumsImagesSettingsName, $key);
    }

    static function getGuarantyTextSettings($key = null)
    {
        return self::getSetting(self::$guarantyTextSettings, self::$guarantyTextSettingsName, $key);
    }

    static function getContentReviewInformationSettings($key = null)
    {
        return self::getSetting(self::$contentReviewInformationSettings, self::$contentReviewInformationSettingsName, $key);
    }

    static function getBlogFeaturedContentsSettings($key = null)
    {
        return self::getSetting(self::$blogFeaturedContentsSettings, self::$blogFeaturedContentsSettingsName, $key);
    }

    static function getStoreFeaturedProductsSettings($key = null)
    {
        return self::getSetting(self::$storeFeaturedProductsSettings, self::$storeFeaturedProductsSettingsName, $key);
    }

    static function getUserDashboardDataSettings($key = null)
    {
        return self::getSetting(self::$userDashboardDataSettings, self::$userDashboardDataSettingsName, $key);
    }

    static function getMobileAppGeneralSettings($key = null)
    {
        return self::getSetting(self::$mobileAppGeneralSettings, self::$mobileAppGeneralSettingsName, $key);
    }

    static function getAttendanceSettings($key = null)
    {
        return self::getSetting(self::$attendanceSettings, self::$attendanceSettingsName, $key);
    }

    static function getEventsSettings($key = null)
    {
        return self::getSetting(self::$eventsSettings, self::$eventsSettingsName, $key);
    }

    static function getMeetingPackagesSettings($key = null)
    {
        return self::getSetting(self::$meetingPackagesSettings, self::$meetingPackagesSettingsName, $key);
    }
}
