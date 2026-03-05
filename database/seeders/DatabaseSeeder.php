<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SectionsTableSeeder::class);

        $this->call(PaymentChannelsTableSeeder::class);

        $this->call(LandingBuilderComponentsSeeder::class);

        $this->call(ThemeHeaderFooterSeeder::class);

        $this->call(DefaultThemeSeeder::class);
    }
}
