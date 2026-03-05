<?php

$themeCache = null;

function getActiveTheme()
{
    global $themeCache;

    if (empty($themeCache)) {
        $withRelations = [
            'homeLanding' => function ($query) {
                $query->with([
                    'components' => function ($query) {
                        $query->with([
                            'landingBuilderComponent'
                        ]);
                        $query->orderBy('order', 'asc');
                    }
                ]);
            }
        ];

        $themeCache = \App\Models\Theme::query()
            ->where('enable', true)
            ->with($withRelations)
            ->first();

        if (empty($themeCache)) {
            $themeCache = \App\Models\Theme::query()
                ->where('is_default', true)
                ->with($withRelations)
                ->first();
        }
    }

    return $themeCache;
}

function getUserThemeColorMode()
{
    $mode = null;
    $user = auth()->user();

    if (!empty($user) and !empty($user->theme_color_mode)) {
        $mode = $user->theme_color_mode;
    }

    if (empty($mode)) {
        $checkCookie = Cookie::get('user_theme_color_mode');

        if (!empty($checkCookie)) {
            $mode = $checkCookie;
        }
    }

    if (empty($mode)) {
        $theme = getActiveTheme();

        if (!empty($theme) and !empty($theme->default_color_mode)) {
            $mode = $theme->default_color_mode;
        } else {
            $mode = 'light';
        }
    }

    if ($mode != 'light') {
        $themeHeaderData = getThemeHeaderData();
        $themeHeaderTopNavData = (!empty($themeHeaderData['contents']) and !empty($themeHeaderData['contents']['top_navbar'])) ? $themeHeaderData['contents']['top_navbar'] : null;

        if (empty($themeHeaderTopNavData) or empty($themeHeaderTopNavData['show_color_mode'])) {
            $mode = 'light';
        }
    }

    return $mode;
}

function getThemeColorsMode()
{
    $theme = getActiveTheme();

    $colorContents = [];
    if (!empty($theme) and !empty($theme->color)) {
        $colorContents = json_decode($theme->color->content, true);
    }

    return $colorContents;
}

/**
 * @return string ("primary_color"|"secondary_color") || null
 * */
function getThemeColorsSettings($landingItem = null, $isAdminSide = false)
{
    $rootResult = '';
    $colorContents = [];

    if (empty($landingItem) or empty($landingItem->color) or empty($landingItem->color->content)) {
        $theme = getActiveTheme();
        if (!empty($theme) and !empty($theme->color)) {
            $colorContents = json_decode($theme->color->content, true);
        }
    } else {
        $colorContents = json_decode($landingItem->color->content, true);
    }

    $mode = $isAdminSide ? 'light' : getUserThemeColorMode();
    $colors = !empty($colorContents[$mode]) ? $colorContents[$mode] : [];

    if (!$isAdminSide) {
        if ($mode == 'dark' and empty($colors)) {
            $colors = !empty($colorContents['light']) ? $colorContents['light'] : [];
        }

        if ($mode == 'light' and empty($colors)) {
            $colors = !empty($colorContents['dark']) ? $colorContents['dark'] : [];
        }
    }

    $btnColors = ['primary', 'secondary', 'accent', 'success', 'info', 'warning', 'danger', 'white'];

    if (!empty($colors)) {
        $rootResult = ":root{" . PHP_EOL;

        foreach ($colors as $colorName => $colorValue) {
            $colorName = str_replace("_", "-", $colorName);

            $rootResult .= "--{$colorName}:" . $colorValue . ';' . PHP_EOL;

            if (in_array($colorName, $btnColors)) {
                $rootResult .= "--{$colorName}-hover:" . darkenColor($colorValue, 10) . ';' . PHP_EOL;
                $rootResult .= "--{$colorName}-border:" . darkenColor($colorValue, 0) . ';' . PHP_EOL;
                $rootResult .= "--{$colorName}-hover-border:" . darkenColor($colorValue, 10) . ';' . PHP_EOL;

                if ($mode == 'light') {
                    $rootResult .= "--{$colorName}-btn-color:" . lightenColor($colorValue, 100) . ';' . PHP_EOL;
                    $rootResult .= "--{$colorName}-btn-hover-color:" . lightenColor($colorValue, 100) . ';' . PHP_EOL;
                } else {
                    $rootResult .= "--{$colorName}-btn-color:" . darkenColor($colorValue, 100) . ';' . PHP_EOL;
                    $rootResult .= "--{$colorName}-btn-hover-color:" . darkenColor($colorValue, 100) . ';' . PHP_EOL;
                }
            }
        }

        $rootResult .= "}" . PHP_EOL;
    }

    return $rootResult;
}

function convertHexToRgb($hex)
{
    $hex = ltrim($hex, '#');

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2)),
    ];
}

function convertRgbToHex($r, $g, $b)
{
    return sprintf("#%02x%02x%02x", max(0, min(255, $r)), max(0, min(255, $g)), max(0, min(255, $b)));
}

function lightenColor($hex, $percent)
{
    $rgb = convertHexToRgb($hex);
    $amount = $percent / 100;

    $r = $rgb['r'] + (255 - $rgb['r']) * $amount;
    $g = $rgb['g'] + (255 - $rgb['g']) * $amount;
    $b = $rgb['b'] + (255 - $rgb['b']) * $amount;

    return convertRgbToHex($r, $g, $b);
}

function darkenColor($hex, $percent)
{
    $rgb = convertHexToRgb($hex);
    $amount = $percent / 100;

    $r = $rgb['r'] * (1 - $amount);
    $g = $rgb['g'] * (1 - $amount);
    $b = $rgb['b'] * (1 - $amount);

    return convertRgbToHex($r, $g, $b);
}

/**
 * @return string
 * */
function getThemeFontsSettings()
{
    $result = '';
    $theme = getActiveTheme();

    if (!empty($theme) and !empty($theme->font)) {
        $fontContents = json_decode($theme->font->content, true);

        foreach ($fontContents as $type => $setting) {

            if (!empty($setting['regular'])) {
                $result .= "@font-face {
                      font-family: '$type-font-family';
                      font-style: normal;
                      font-weight: 400;
                      font-display: swap;
                      src: url({$setting['regular']}) format('woff2');
                    }";
            }

            if (!empty($setting['bold'])) {
                $result .= "@font-face {
                      font-family: '$type-font-family';
                      font-style: normal;
                      font-weight: bold;
                      font-display: swap;
                      src: url({$setting['bold']}) format('woff2');
                    }";
            }

            if (!empty($setting['medium'])) {
                $result .= "@font-face {
                      font-family: '$type-font-family';
                      font-style: normal;
                      font-weight: 500;
                      font-display: swap;
                      src: url({$setting['medium']}) format('woff2');
                    }";
            }

        }
    }

    return $result;
}

/**
 * @param null $key => css, js
 * @return string|array => {css, js}
 */
function getThemeCustomCssAndJs($key = null)
{
    $css = null;
    $js = null;

    $theme = getActiveTheme();

    if (!empty($theme)) {
        $themeContents = [];
        if (!empty($theme->contents)) {
            $themeContents = json_decode($theme->contents, true);
        }

        if (!empty($themeContents['custom_css'])) {
            $css = $themeContents['custom_css'];
        }

        if (!empty($themeContents['custom_js'])) {
            $js = $themeContents['custom_js'];
        }
    }

    $result = [
        'css' => $css,
        'js' => $js,
    ];

    if (!empty($key) and !empty($result[$key])) {
        return $result[$key];
    }

    return $result;
}


/**
 * @param null $page => admin_login, admin_dashboard, login, register, remember_pass, search, categories,
 * become_instructor, certificate_validation, blog, instructors
 * ,dashboard, panel_sidebar, user_avatar, user_cover, instructor_finder_wizard, products_lists
 * @return string|array => [all pages]
 */
function getThemePageBackgroundSettings($page = null)
{
    $allImages = [];
    $theme = getActiveTheme();

    if (!empty($theme)) {
        $themeContents = [];
        if (!empty($theme->contents)) {
            $themeContents = json_decode($theme->contents, true);
        }

        $allImages = !empty($themeContents['images']) ? $themeContents['images'] : [];
    }

    if (!empty($page)) {
        return !empty($allImages[$page]) ? $allImages[$page] : '';
    }

    return $allImages;
}

function getThemeContentCardStyle($content)
{
    $cardName = "grid_card_1";
    $theme = getActiveTheme();

    if (!empty($theme)) {
        $themeContents = [];
        if (!empty($theme->contents)) {
            $themeContents = json_decode($theme->contents, true);
        }

        if (!empty($themeContents['card_styles']) and !empty($themeContents['card_styles'][$content])) {
            $cardName = $themeContents['card_styles'][$content];
        }
    }

    return $cardName;
}


function getThemeAuthenticationPagesSettings($key = null)
{
    $allSettings = [];
    $theme = getActiveTheme();

    if (!empty($theme)) {
        $themeContents = [];
        if (!empty($theme->contents)) {
            $themeContents = json_decode($theme->contents, true);
        }

        $allSettings = !empty($themeContents['authentication_pages']) ? $themeContents['authentication_pages'] : [];
    }

    if (!empty($key)) {
        return !empty($allSettings[$key]) ? $allSettings[$key] : '';
    }

    return $allSettings;
}

function getThemeAuthenticationPagesStyleName()
{
    $styleName = "theme_1";
    $theme = getActiveTheme();

    if (!empty($theme)) {
        $themeContents = [];
        if (!empty($theme->contents)) {
            $themeContents = json_decode($theme->contents, true);
        }

        if (!empty($themeContents) and !empty($themeContents['authentication_pages']) and !empty($themeContents['authentication_pages']['style'])) {
            $styleName = $themeContents['authentication_pages']['style'];
        }
    }

    return $styleName;
}

function getThemeHeaderData($userDeviceType = "desktop")
{
    $headerName = "header_1";
    $headerContents = [];

    $theme = getActiveTheme();

    if (!empty($theme) and $theme->header) {
        $headerName = $theme->header->component_name;

        if (!empty($theme->header->content)) {
            $headerContents = json_decode($theme->header->content, true);
        }
    }

    if ($userDeviceType == "mobile") {
        $headerName = "mobile";
    }

    return [
        'component_name' => $headerName,
        'contents' => $headerContents,
    ];
}

function getThemeFooterData()
{
    $footerName = "footer_1";
    $footerContents = [];

    $theme = getActiveTheme();

    if (!empty($theme) and $theme->footer) {
        $footerName = $theme->footer->component_name;

        if (!empty($theme->footer->content)) {
            $footerContents = json_decode($theme->footer->content, true);
        }
    }

    return [
        'component_name' => $footerName,
        'contents' => $footerContents,
    ];
}
