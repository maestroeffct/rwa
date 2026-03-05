<?php

namespace App\Mixins\Themes;

class ThemeHeaderMixins
{


    public function getHeader1NavbarSpecificLinks($contents)
    {
        $links = [];

        if (!empty($contents['specific_links']) and is_array($contents['specific_links'])) {
            foreach ($contents['specific_links'] as $linkData) {
                if (!empty($linkData['title']) and !empty($linkData['url'])) {
                    $links[] = [
                        'title' => $linkData['title'],
                        'url' => $linkData['url'],
                    ];
                }
            }
        }

        return $links;
    }

    public function getHeader1NavbarSpecificButton($contents)
    {
        $user = auth()->user();
        $result = null;

        if (!empty($contents['specific_buttons']) and is_array($contents['specific_buttons'])) {
            foreach ($contents['specific_buttons'] as $btnData) {
                if (empty($result) and !empty($btnData['user_role']) and !empty($btnData['title']) and !empty($btnData['url'])) {
                    if (empty($user)) {
                        $result = ($btnData['user_role'] == "for_guest") ? $btnData : null;
                    } else if ($btnData['user_role'] == $user->role_id) {
                        $result = $btnData;
                    }
                }
            }
        }

        return $result;
    }
}
