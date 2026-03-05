<?php

namespace Database\Seeders;


use App\Models\ThemeHeaderFooter;
use App\Models\Translation\ThemeHeaderFooterTranslation;
use Illuminate\Database\Seeder;

class ThemeHeaderFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (ThemeHeaderFooter::$headers as $headerComponentName => $headerTitle) {
            $check = ThemeHeaderFooter::query()->where('component_name', $headerComponentName)
                ->where('type', 'header')
                ->first();

            if (empty($check)) {
                $locale = getDefaultLocale();

                $newThemeHeader = ThemeHeaderFooter::query()->create([
                    'type' => 'header',
                    'component_name' => $headerComponentName,
                    'created_at' => time(),
                ]);

                ThemeHeaderFooterTranslation::query()->updateOrCreate([
                    'theme_header_footer_id' => $newThemeHeader->id,
                    'locale' => mb_strtolower($locale)
                ], [
                    'title' => $headerTitle,
                    'content' => json_encode([]),
                ]);
            }
        }

        // Footers

        foreach (ThemeHeaderFooter::$footers as $footerComponentName => $footerTitle) {
            $check = ThemeHeaderFooter::query()->where('component_name', $footerComponentName)
                ->where('type', 'footer')
                ->first();

            if (empty($check)) {
                $locale = getDefaultLocale();

                $newFooterHeader = ThemeHeaderFooter::query()->create([
                    'type' => 'footer',
                    'component_name' => $footerComponentName,
                    'created_at' => time(),
                ]);

                ThemeHeaderFooterTranslation::query()->updateOrCreate([
                    'theme_header_footer_id' => $newFooterHeader->id,
                    'locale' => mb_strtolower($locale)
                ], [
                    'title' => $footerTitle,
                    'content' => json_encode([]),
                ]);
            }
        }

    }
}
