<?php

namespace App\Mixins\BulkImports\Drivers;


use App\Mixins\BulkImports\IImportChannel;
use App\Models\Category;
use App\Models\Translation\CategoryTranslation;


class CategoriesBulkImports implements IImportChannel
{
    protected $locale;

    public function import(array $items, $locale = null, $currency = null)
    {
        $this->locale = !empty($locale) ? $locale : getDefaultLocale();

        $chunks = array_chunk($items, 200);

        foreach ($chunks as $chunkItems) {
            foreach ($chunkItems as $item) {
                $newCategory = $this->makeNewCategory($item);

                // Translation
                $this->handleCategoryTranslation($newCategory->id, $item);
            }
        }
    }

    private function makeNewCategory($data)
    {
        return Category::create([
            'slug' => Category::makeSlug($data['title']),
            'parent_id' => !empty($data['parent_id']) ? $data['parent_id'] : null,
        ]);
    }

    private function handleCategoryTranslation($categoryId, $data)
    {
        CategoryTranslation::updateOrCreate([
            'category_id' => $categoryId,
            'locale' => mb_strtolower($this->locale),
        ], [
            'title' => $data['title'],
            'subtitle' => !empty($data['subtitle']) ? $data['subtitle'] : null,
            'bottom_seo_title' => !empty($data['seo_title']) ? $data['seo_title'] : null,
            'bottom_seo_content' => !empty($data['seo_description']) ? $data['seo_description'] : null,
        ]);
    }

    public function getValidatorRule(): array
    {
        return [
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'subtitle' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
        ];
    }

}
