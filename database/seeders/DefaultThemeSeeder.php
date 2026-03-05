<?php

namespace Database\Seeders;


use App\Models\Theme;
use Illuminate\Database\Seeder;

class DefaultThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check = Theme::query()->where('is_default', true)->first();

        if (empty($check)) {
            Theme::query()->create([
                'title' => "Default Theme",
                'is_default' => true,
                'enable' => true,
                'created_at' => time(),
            ]);
        }
    }

}
