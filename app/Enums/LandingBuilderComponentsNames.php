<?php

namespace App\Enums;

use ReflectionClass;

class LandingBuilderComponentsNames
{

    // Names
    const TWO_COLUMNS_HERO = 'two_columns_hero';
    const FULL_WIDTH_HERO = 'full_width_hero';
    const SINGLE_INSTRUCTOR_HERO = 'single_instructor_hero';
    const STATISTICS = 'statistics';
    const FEATURED_COURSES = 'featured_courses';
    const LINKS_AND_TITLES_SLIDER_2_ROWS = 'links_and_titles_slider_2_rows';
    const LINKS_AND_TITLES_SLIDER_1_ROW = 'links_and_titles_slider_1_row';
    const INFORMATION_CARDS = 'information_cards';
    const TRENDING_CATEGORIES = 'trending_categories';
    const NEWEST_COURSES = 'newest_courses';
    const BEST_SELLING_COURSES = 'best_selling_courses';
    const BEST_RATED_COURSES = 'best_rated_courses';
    const DISCOUNTED_COURSES = 'discounted_courses';
    const FREE_COURSES = 'free_courses';
    const UPCOMING_COURSES = 'upcoming_courses';
    const COURSE_BUNDLES = 'course_bundles';
    const STORE_PRODUCTS = 'store_products';
    const HYBRID_INFORMATION_SECTION_2_IMAGES_CHECK_ITEMS_TEXT = 'hybrid_information_section_2_images_check_items_text';
    const HYBRID_INFORMATION_SECTION_2_IMAGES_TEXT = 'hybrid_information_section_2_images_text';
    const HYBRID_INFORMATION_SECTION_2_IMAGES_TEXT_2 = 'hybrid_information_section_2_images_text_2';
    const HYBRID_INFORMATION_SECTION_3_IMAGES_TEXT = 'hybrid_information_section_3_images_text';
    const HYBRID_INFORMATION_SECTION_4_IMAGES_TEXT = 'hybrid_information_section_4_images_text';
    const COMPANY_LOGOS = 'company_logos';
    const FULL_WIDTH_BAR_CTA = 'full_width_bar_cta';
    const FAQ_6_COL = 'faq_6_col';
    const FEATURES_4X = 'features_4x';
    const BANNERS_GRID_3_IN_DIFFERENT_SIZES = 'banners_grid_3_in_different_sizes';
    const MEETING_BOOKING_LIST = 'meeting_booking_list';
    const SUBSCRIPTION_PLANS = 'subscription_plans';
    const SINGLE_VIDEO_SECTION = 'single_video_section';
    const SLIDING_TESTIMONIALS_2_ROWS = 'sliding_testimonials_2_rows';
    const CTA_CARD_8_COLUMNS = 'cta_card_8_columns';
    const ORGANIZATIONS = 'organizations';
    const INSTRUCTORS = 'instructors';
    const BLOG = 'blog';
    const CTA_SECTION_FULL_WIDTH = 'cta_section_full_width';
    const BANNER_FULL_WIDTH = 'banner_full_width';
    const BANNER_3_ITEMS_PER_ROW = 'banner_3_items_per_row';
    const BANNER_2_ITEMS_PER_ROW = 'banner_2_items_per_row';
    const BANNER_4_ITEMS_PER_ROW = 'banner_4_items_per_row';
    const BOXED_CTA_FULL_WIDTH = 'boxed_cta_full_width';
    const CTA_AND_INFORMATION_HYBRID = 'cta_and_information_hybrid';
    const HYBRID_INFORMATION_SECTION_FULL_WIDTH = 'hybrid_information_section_full_width';
    const LINKS_AND_IMAGES_6_ITEMS_PER_ROW = 'links_and_images_6_items_per_row';
    const VIDEO_AND_IMAGE_SLIDER_FULL_WIDTH = 'video_and_image_slider_full_width';
    const INFORMATION_CARD_FULL_WIDTH = 'information_card_full_width';
    const INFORMATION_CARD_FULL_WIDTH_2 = 'information_card_full_width_2';
    const FULL_WIDTH_IMAGE_AND_VIDEO_CTA = 'full_width_image_and_video_cta';
    const IMAGE_INFORMATION_CARDS_3X = 'image_information_cards_3x';
    const TWO_SIDED_INFORMATION_IMAGES_AND_CARDS = 'two_sided_information_images_and_cards';
    const LINKED_IMAGES_3X = 'linked_images_3x';
    const BIG_CALL_TO_ACTION_CARDS_2X = 'big_call_to_action_cards_2x';
    const CUSTOM_COURSES_GRID = 'custom_courses_grid';
    const CENTER_TEXT = 'center_text';
    const VERTICAL_SPACER = 'vertical_spacer';
    const SLIDING_MEETUPS = 'sliding_events';
    const SLIDING_COURSES_HERO = 'sliding_courses_hero';
    const CENTERED_HERO_WITH_FOUR_IMAGES_TWO_ICONS = 'centered_hero_with_four_images_two_icons';
    const MULTI_TAB_IMAGE_VIDEO_PLACEHOLDER = 'multi_tab_image_video_placeholder';
    const MULTI_TAB_COURSES = 'multi_tab_courses';
    const COUNTDOWN_CALL_TO_ACTION = 'countdown_call_to_action';
    const MEETING_PACKAGES_GRID = 'meeting_packages_grid';


    const categories = [
        self::TWO_COLUMNS_HERO => LandingBuilderComponentCategories::HERO,
        self::FULL_WIDTH_HERO => LandingBuilderComponentCategories::HERO,
        self::SINGLE_INSTRUCTOR_HERO => LandingBuilderComponentCategories::HERO,
        self::SLIDING_COURSES_HERO => LandingBuilderComponentCategories::HERO,
        self::CENTERED_HERO_WITH_FOUR_IMAGES_TWO_ICONS => LandingBuilderComponentCategories::HERO,
        self::STATISTICS => LandingBuilderComponentCategories::STATISTICS,
        self::FEATURED_COURSES => LandingBuilderComponentCategories::COURSES,
        self::LINKS_AND_TITLES_SLIDER_2_ROWS => LandingBuilderComponentCategories::MISCELLANEOUS,
        self::LINKS_AND_TITLES_SLIDER_1_ROW => LandingBuilderComponentCategories::MISCELLANEOUS,
        self::INFORMATION_CARDS => LandingBuilderComponentCategories::CARDS,
        self::TRENDING_CATEGORIES => LandingBuilderComponentCategories::CATEGORIES,
        self::NEWEST_COURSES => LandingBuilderComponentCategories::COURSES,
        self::BEST_SELLING_COURSES => LandingBuilderComponentCategories::COURSES,
        self::BEST_RATED_COURSES => LandingBuilderComponentCategories::COURSES,
        self::DISCOUNTED_COURSES => LandingBuilderComponentCategories::COURSES,
        self::FREE_COURSES => LandingBuilderComponentCategories::COURSES,
        self::UPCOMING_COURSES => LandingBuilderComponentCategories::UPCOMING_COURSES,
        self::COURSE_BUNDLES => LandingBuilderComponentCategories::BUNDLES,
        self::STORE_PRODUCTS => LandingBuilderComponentCategories::STORE_PRODUCTS,
        self::HYBRID_INFORMATION_SECTION_2_IMAGES_CHECK_ITEMS_TEXT => LandingBuilderComponentCategories::INFORMATION,
        self::HYBRID_INFORMATION_SECTION_2_IMAGES_TEXT => LandingBuilderComponentCategories::INFORMATION,
        self::HYBRID_INFORMATION_SECTION_2_IMAGES_TEXT_2 => LandingBuilderComponentCategories::INFORMATION,
        self::HYBRID_INFORMATION_SECTION_3_IMAGES_TEXT => LandingBuilderComponentCategories::INFORMATION,
        self::HYBRID_INFORMATION_SECTION_4_IMAGES_TEXT => LandingBuilderComponentCategories::INFORMATION,
        self::COMPANY_LOGOS => LandingBuilderComponentCategories::LOGOS,
        self::FULL_WIDTH_BAR_CTA => LandingBuilderComponentCategories::CALL_TO_ACTION,
        self::FAQ_6_COL => LandingBuilderComponentCategories::FAQ,
        self::FEATURES_4X => LandingBuilderComponentCategories::FEATURES,
        self::BANNERS_GRID_3_IN_DIFFERENT_SIZES => LandingBuilderComponentCategories::BANNERS,
        self::MEETING_BOOKING_LIST => LandingBuilderComponentCategories::MEETING_BOOKING,
        self::SUBSCRIPTION_PLANS => LandingBuilderComponentCategories::SUBSCRIPTION,
        self::SINGLE_VIDEO_SECTION => LandingBuilderComponentCategories::VIDEO,
        self::SLIDING_TESTIMONIALS_2_ROWS => LandingBuilderComponentCategories::TESTIMONIALS,
        self::CTA_CARD_8_COLUMNS => LandingBuilderComponentCategories::CALL_TO_ACTION,
        self::ORGANIZATIONS => LandingBuilderComponentCategories::ORGANIZATIONS,
        self::INSTRUCTORS => LandingBuilderComponentCategories::INSTRUCTORS,
        self::BLOG => LandingBuilderComponentCategories::BLOG_POSTS,
        self::CTA_SECTION_FULL_WIDTH => LandingBuilderComponentCategories::CALL_TO_ACTION,
        self::BANNER_FULL_WIDTH => LandingBuilderComponentCategories::BANNERS,
        self::BANNER_3_ITEMS_PER_ROW => LandingBuilderComponentCategories::BANNERS,
        self::BANNER_2_ITEMS_PER_ROW => LandingBuilderComponentCategories::BANNERS,
        self::BANNER_4_ITEMS_PER_ROW => LandingBuilderComponentCategories::BANNERS,
        self::BOXED_CTA_FULL_WIDTH => LandingBuilderComponentCategories::CALL_TO_ACTION,
        self::CTA_AND_INFORMATION_HYBRID => LandingBuilderComponentCategories::INFORMATION,
        self::HYBRID_INFORMATION_SECTION_FULL_WIDTH => LandingBuilderComponentCategories::INFORMATION,
        self::LINKS_AND_IMAGES_6_ITEMS_PER_ROW => LandingBuilderComponentCategories::MISCELLANEOUS,
        self::VIDEO_AND_IMAGE_SLIDER_FULL_WIDTH => LandingBuilderComponentCategories::VIDEO,
        self::INFORMATION_CARD_FULL_WIDTH => LandingBuilderComponentCategories::CARDS,
        self::INFORMATION_CARD_FULL_WIDTH_2 => LandingBuilderComponentCategories::CARDS,
        self::FULL_WIDTH_IMAGE_AND_VIDEO_CTA => LandingBuilderComponentCategories::INFORMATION,
        self::IMAGE_INFORMATION_CARDS_3X => LandingBuilderComponentCategories::BANNERS,
        self::TWO_SIDED_INFORMATION_IMAGES_AND_CARDS => LandingBuilderComponentCategories::INFORMATION,
        self::LINKED_IMAGES_3X => LandingBuilderComponentCategories::BANNERS,
        self::BIG_CALL_TO_ACTION_CARDS_2X => LandingBuilderComponentCategories::CALL_TO_ACTION,
        self::CUSTOM_COURSES_GRID => LandingBuilderComponentCategories::COURSES,
        self::CENTER_TEXT => LandingBuilderComponentCategories::TEXT,
        self::VERTICAL_SPACER => LandingBuilderComponentCategories::MISCELLANEOUS,
        self::SLIDING_MEETUPS => LandingBuilderComponentCategories::CARDS,
        self::MULTI_TAB_IMAGE_VIDEO_PLACEHOLDER => LandingBuilderComponentCategories::VIDEO,
        self::MULTI_TAB_COURSES => LandingBuilderComponentCategories::COURSES,
        self::COUNTDOWN_CALL_TO_ACTION => LandingBuilderComponentCategories::CALL_TO_ACTION,
        self::MEETING_PACKAGES_GRID => LandingBuilderComponentCategories::CARDS,
    ];

    /**
     * Returns all constants as an array.
     *
     * @return array
     */
    public static function getAll(): array
    {
        return array_keys(self::categories);
    }

    public static function getCategory(string $name): string
    {
        return self::categories[$name] ?? LandingBuilderComponentCategories::HERO;
    }
}
