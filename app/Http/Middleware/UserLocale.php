<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Torann\GeoIP\Facades\GeoIP;

class UserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $generalSettings = getGeneralSettings();

        $defaultLocale = getDefaultLocale();

        $locale = $defaultLocale;

        if (auth()->check()) {
            $user = auth()->user();
            $locale = !empty($user->language) ? $user->language : $defaultLocale;
        } else {
            $checkCookie = Cookie::get('user_locale');

            if (!empty($checkCookie)) {
                $locale = $checkCookie;
            } else {
                // Detect guest language by IP if enabled in settings
                $visitorsDefaultLangMode = $generalSettings['visitors_default_language'] ?? 'default';
                if ($visitorsDefaultLangMode === 'detect_ip') {
                    try {
                        $location = GeoIP::getLocation(request()->ip());
                        $countryIso = !empty($location) && !empty($location->iso_code) ? strtoupper($location->iso_code) : null;

                        if (!empty($countryIso)) {
                            // Country-to-language mapping (grouped by continent)
                            $countryToLanguage = [
                                // AFRICA (North Africa - Arabic)
                                'DZ' => 'AR','EG' => 'AR','MA' => 'AR','TN' => 'AR','LY' => 'AR','SD' => 'AR',
                                // AFRICA (West/Central/East/South - major)
                                'NG' => 'EN','ZA' => 'EN','KE' => 'EN','ET' => 'EN','GH' => 'EN','TZ' => 'EN','UG' => 'EN','CM' => 'EN','CI' => 'FR','SN' => 'FR','ML' => 'FR','NE' => 'FR','BF' => 'FR','BJ' => 'FR','TG' => 'FR','GA' => 'FR','CG' => 'FR','CD' => 'FR','AO' => 'PT','MZ' => 'PT','NA' => 'EN','ZW' => 'EN','ZM' => 'EN','RW' => 'EN',

                                // ASIA (Middle East - Arabic)
                                'AE' => 'AR','SA' => 'AR','KW' => 'AR','BH' => 'AR','OM' => 'AR','QA' => 'AR','JO' => 'AR','LB' => 'AR','IQ' => 'AR','SY' => 'AR','YE' => 'AR','PS' => 'AR',
                                // ASIA (Iran/Turkey/Pakistan/Afghanistan)
                                'IR' => 'FA','TR' => 'TR','PK' => 'UR','AF' => 'FA',
                                // ASIA (Caucasus & Central Asia)
                                'AZ' => 'AZ','AM' => 'HY','GE' => 'KA','KZ' => 'RU','KG' => 'RU','UZ' => 'UZ','TM' => 'TK','TJ' => 'TG',
                                // ASIA (East Asia)
                                'CN' => 'ZH','TW' => 'ZH','HK' => 'ZH','JP' => 'JA','KR' => 'KO',
                                // ASIA (South Asia)
                                'IN' => 'EN','BD' => 'EN','LK' => 'EN','NP' => 'EN',
                                // ASIA (Southeast Asia)
                                'TH' => 'TH','VN' => 'VI','ID' => 'ID','MY' => 'MS','SG' => 'EN','PH' => 'TL','KH' => 'KM','LA' => 'LO','MM' => 'MY',
                                // ASIA (Israel)
                                'IL' => 'HE',

                                // EUROPE (Western)
                                'FR' => 'FR','DE' => 'DE','IT' => 'IT','ES' => 'ES','PT' => 'PT','NL' => 'NL','BE' => 'FR','CH' => 'DE','AT' => 'DE',
                                // EUROPE (Nordic)
                                'SE' => 'SV','NO' => 'NO','DK' => 'DA','FI' => 'FI','IE' => 'EN',
                                // EUROPE (Central/Eastern)
                                'PL' => 'PL','CZ' => 'CS','RO' => 'RO','HU' => 'HU','GR' => 'EL','RU' => 'RU','UA' => 'UK','BY' => 'RU','BG' => 'BG','HR' => 'HR','RS' => 'SR','SI' => 'SL','SK' => 'SK',

                                // NORTH AMERICA
                                'US' => 'EN','CA' => 'EN','MX' => 'ES','GT' => 'ES','HN' => 'ES','SV' => 'ES','NI' => 'ES','CR' => 'ES','PA' => 'ES','DO' => 'ES','CU' => 'ES','HT' => 'FR','JM' => 'EN','TT' => 'EN','BS' => 'EN','BZ' => 'EN',

                                // SOUTH AMERICA
                                'BR' => 'PT','AR' => 'ES','CL' => 'ES','CO' => 'ES','PE' => 'ES','VE' => 'ES','EC' => 'ES','UY' => 'ES','PY' => 'ES','BO' => 'ES','GY' => 'EN','SR' => 'NL','GF' => 'FR',

                                // OCEANIA
                                'AU' => 'EN','NZ' => 'EN','FJ' => 'FJ','PG' => 'EN','WS' => 'SM','SB' => 'EN',

                                // UNITED KINGDOM
                                'GB' => 'EN',
                            ];

                            if (!empty($countryToLanguage[$countryIso])) {
                                $locale = $countryToLanguage[$countryIso];
                            }
                        }
                    } catch (\Throwable $e) {
                        // Ignore detection errors and fallback below
                    }
                }
            }
        }

        $userLanguages = $generalSettings['user_languages'] ?? [];

        if (!in_array($locale, $userLanguages)) {
            $locale = $defaultLocale;
        }

        \Session::put('locale', mb_strtolower($locale));

        return $next($request);
    }
}
