<?php

namespace App\Mixins\LandingBuilder;

use App\Models\Bundle;
use App\Models\Product;
use App\Models\Role;
use App\Models\Sale;
use App\Models\UpcomingCourse;
use App\Models\Webinar;
use App\User;

class StatisticComponentFrontMixins
{

    public $sources = ['number_of_instructors', 'number_of_students', 'number_of_organizations', 'number_of_all_users', 'number_of_video_courses', 'number_of_live_courses', 'number_of_text_courses', 'number_of_courses', 'number_of_bundles', 'number_of_store_products', 'number_of_upcoming_courses', 'number_of_sales', 'sales_amount'];

    public function calculateStatisticData($statistic)
    {
        $dataType = $statistic['data_type'] ?? null;
        $dataSource = $statistic['data_source'] ?? null;

        $sourceValue = 0;

        if (in_array($dataType, ["real", "semi_real"])) {
            $sourceValue = $this->{$dataSource}();

            if ($dataType == "semi_real") {
                $sourceValue += !empty($statistic['start_from']) ? $statistic['start_from'] : 0;
            }
        } else if ($dataType == "manual") {
            $sourceValue = !empty($statistic['manual_data']) ? $statistic['manual_data'] : '';
        }


        return $sourceValue;
    }

    private function number_of_instructors()
    {
        return User::query()->where('role_name', Role::$teacher)
            ->where('status', 'active')
            ->count();
    }

    private function number_of_students()
    {
        return User::query()->where('role_name', Role::$user)
            ->where('status', 'active')
            ->count();
    }

    private function number_of_organizations()
    {
        return User::query()->where('role_name', Role::$organization)
            ->where('status', 'active')
            ->count();
    }

    private function number_of_all_users()
    {
        return User::query()
            ->where('status', 'active')
            ->whereDoesntHave('role', function ($query) {
                $query->where('is_admin', true);
            })
            ->count();
    }

    private function number_of_video_courses()
    {
        return Webinar::query()
            ->where('status', 'active')
            ->where('type', 'course')
            ->count();
    }

    private function number_of_live_courses()
    {
        return Webinar::query()
            ->where('status', 'active')
            ->where('type', 'webinar')
            ->count();
    }

    private function number_of_text_courses()
    {
        return Webinar::query()
            ->where('status', 'active')
            ->where('type', 'text_lesson')
            ->count();
    }

    private function number_of_courses()
    {
        return Webinar::query()
            ->where('status', 'active')
            ->count();
    }

    private function number_of_bundles()
    {
        return Bundle::query()
            ->where('status', 'active')
            ->count();
    }

    private function number_of_store_products()
    {
        return Product::query()
            ->where('status', 'active')
            ->count();
    }

    private function number_of_upcoming_courses()
    {
        return UpcomingCourse::query()
            ->where('status', 'active')
            ->count();
    }

    private function number_of_sales()
    {
        return Sale::query()->count();
    }

    private function sales_amount()
    {
        return handlePrice(Sale::query()->sum('total_amount'));
    }

}
