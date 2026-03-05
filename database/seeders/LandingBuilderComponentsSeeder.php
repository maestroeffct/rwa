<?php

namespace Database\Seeders;

use App\Enums\LandingBuilderComponentsNames;
use App\Models\LandingBuilderComponent;
use Illuminate\Database\Seeder;

class LandingBuilderComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (LandingBuilderComponentsNames::getAll() as $index => $name) {

            $check = LandingBuilderComponent::query()->where('name', $name)->first();

            if (empty($check)) {
                LandingBuilderComponent::query()->create([
                    'name' => $name,
                    'category' => LandingBuilderComponentsNames::getCategory($name),
                ]);
            }
        }
    }
}
