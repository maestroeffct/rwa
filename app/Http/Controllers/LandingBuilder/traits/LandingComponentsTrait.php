<?php

namespace App\Http\Controllers\LandingBuilder\traits;

use App\Models\Landing;
use Illuminate\Http\Request;

trait LandingComponentsTrait
{

    private function makeContentDataByName(Request $request, $contents, $fileName = "")
    {
        $data = [];

        foreach ($contents as $name1 => $contentData) {
            $fn = "{$fileName}.{$name1}";

            if (is_array($contentData)) {
                $data[$name1] = $this->makeContentDataByName($request, $contentData, $fn);
            } else {
                $fileName = ltrim($fileName, ".");
                $fn2 = "{$fileName}.{$name1}";

                if ($request->hasFile($fn2)) {
                    $file = $request->file($fn2);
                    $fileExtension = $file->getClientOriginalExtension();
                    $fileOriginalName = str_replace(".{$fileExtension}", "", $file->getClientOriginalName());
                    $fileOriginalName = str_replace(" ", '_', $fileOriginalName);
                    $fileOriginalName = $fileOriginalName . '_' . random_str(3);

                    $pa = $this->uploadFile($file, $this->uploadDestination, $fileOriginalName);

                    $data[$name1] = $pa;
                } else {
                    $data[$name1] = $contentData;
                }
            }
        }

        return $data;
    }


    private function handleOldAndNewContentData($oldContents, $newContentData)
    {
        $data = [];

        $ignoreKeys = [ // Keys to always take from newData only
            'highlight_words',
            'students_widget_avatars',
            'companies_widget_logos',
            'checked_items',
            'statistics',
            'featured_courses',
            'title_items',
            'information_cards',
            'trending_categories',
            'faq_items',
            'features_cards',
            'meeting_instructors',
            'subscriptions_plans',
            'testimonials',
            'specific_organizations',
            'specific_instructors',
            'specific_banners',
            'specific_information',
            'specific_sliders',
            'specific_links',
            'specific_links_2',
            'specific_buttons',
            'image_video_content',
            'course_tabs_content',
            'featured_packages',
        ];

        // Merge keys from both arrays to ensure all keys are included
        $allKeys = array_unique(array_merge(array_keys($oldContents), array_keys($newContentData)));

        foreach ($allKeys as $key) {
            $newValue = $newContentData[$key] ?? null;
            $oldValue = $oldContents[$key] ?? null;

            // If the key is in $ignoreKeys, always take from newData
            if (in_array($key, $ignoreKeys, true)) {
                $data[$key] = $newValue;
                continue;
            }

            if (is_array($newValue) || (is_array($oldValue) and !empty($oldValue))) {
                // Ensure both are arrays before merging
                $data[$key] = $this->handleOldAndNewContentData(is_array($oldValue) ? $oldValue : [], is_array($newValue) ? $newValue : []);
            } else {
                // Prioritize non-empty newValue, otherwise fallback to oldValue
                $data[$key] = !empty($newValue) ? $newValue : null;
            }
        }

        return $data;
    }


}
