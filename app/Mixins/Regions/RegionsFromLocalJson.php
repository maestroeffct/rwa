<?php

namespace App\Mixins\Regions;

use Illuminate\Support\Facades\File;

class RegionsFromLocalJson
{
    private function basePath(): string
    {
        return base_path('database/regions');
    }

    private function path(string $filename): ?string
    {
        $full = $this->basePath() . DIRECTORY_SEPARATOR . $filename;
        return is_file($full) ? $full : null;
    }

    public function getCountries(): array
    {
        $path = $this->path('countries.json');
        if (!$path) return [];
        $json = json_decode(File::get($path), true);
        return is_array($json) ? $json : [];
    }

    public function getStates($countryId = null): array
    {
        $path = $this->path('states.json');
        if (!$path) return [];
        $items = json_decode(File::get($path), true);
        if (!is_array($items)) return [];
        if ($countryId === null) return $items;
        return array_values(array_filter($items, function ($item) use ($countryId) {
            return isset($item['country_id']) && (string)$item['country_id'] === (string)$countryId;
        }));
    }

    public function getCities($stateId = null): array
    {
        $path = $this->path('cities.json');
        if (!$path) return [];
        $items = json_decode(File::get($path), true);
        if (!is_array($items)) return [];
        if ($stateId === null) return $items;
        return array_values(array_filter($items, function ($item) use ($stateId) {
            return isset($item['state_id']) && (string)$item['state_id'] === (string)$stateId;
        }));
    }
}


