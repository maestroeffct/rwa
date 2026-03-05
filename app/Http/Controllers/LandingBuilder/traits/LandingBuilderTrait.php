<?php
namespace App\Http\Controllers\LandingBuilder\traits;

use App\Models\Landing;
use App\Models\ThemeColorFont;
use Illuminate\Http\Request;

trait LandingBuilderTrait
{

    private function getPanelCommonData(Request $request): array
    {
        $landingItems = Landing::query()
            ->withCount([
                'components'
            ])
            ->get();

        $themeColors = ThemeColorFont::query()->where('type', 'color')->get();

        return [
            'landingItems' => $landingItems,
            'themeColors' => $themeColors,
        ];
    }

}
