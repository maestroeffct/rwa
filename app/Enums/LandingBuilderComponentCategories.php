<?php

namespace App\Enums;

use ReflectionClass;

class LandingBuilderComponentCategories
{

    const HERO = 'hero';
    const BANNERS = 'banners';
    const COURSES = 'courses';
    const CATEGORIES = 'categories';
    const UPCOMING_COURSES = 'upcoming_courses';
    const LOGOS = 'logos';
    const BUNDLES = 'bundles';
    const FAQ = 'faq';
    const STORE_PRODUCTS = 'store_products';
    const VIDEO = 'video';
    const BLOG_POSTS = 'blog_posts';
    const FEATURES = 'features';
    const SUBSCRIPTION = 'subscription';
    const CARDS = 'cards';
    const TESTIMONIALS = 'testimonials';
    const INFORMATION = 'information';
    const CALL_TO_ACTION = 'call_to_action';
    const STATISTICS = 'statistics';
    const MEETING_BOOKING = 'meeting_booking';
    const MISCELLANEOUS = 'miscellaneous';
    const INSTRUCTORS = 'instructors';
    const TEXT = 'text';
    const ORGANIZATIONS = 'organizations';

    /**
     * Returns all constants as an array.
     *
     * @return array
     */
    public static function getAll(): array
    {
        return (new ReflectionClass(__CLASS__))->getConstants();
    }

    public static function getIcon($cat)
    {
        $icons = [
            self::HERO => 'home-2',
            self::BANNERS => 'gallery',
            self::COURSES => 'video-play',
            self::CATEGORIES => 'category',
            self::UPCOMING_COURSES => 'clock',
            self::LOGOS => 'sticker',
            self::BUNDLES => 'box-1',
            self::FAQ => 'message-question',
            self::STORE_PRODUCTS => 'bag',
            self::VIDEO => 'video-vertical',
            self::BLOG_POSTS => 'note-2',
            self::FEATURES => 'setting-2',
            self::SUBSCRIPTION => 'cup',
            self::CARDS => 'slider-horizontal',
            self::TESTIMONIALS => 'star',
            self::INFORMATION => 'shield',
            self::CALL_TO_ACTION => 'mouse-circle',
            self::STATISTICS => 'trend-up',
            self::MEETING_BOOKING => 'calendar-2',
            self::MISCELLANEOUS => 'document',
            self::INSTRUCTORS => 'teacher',
            self::TEXT => 'document-text',
            self::ORGANIZATIONS => 'briefcase',
        ];

        return $icons[$cat] ?? 'home-2';
    }
}
