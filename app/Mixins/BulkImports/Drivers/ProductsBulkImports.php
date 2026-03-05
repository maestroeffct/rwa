<?php

namespace App\Mixins\BulkImports\Drivers;


use App\Mixins\BulkImports\IImportChannel;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Translation\ProductTranslation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductsBulkImports implements IImportChannel
{
    protected $locale;
    protected $currency;


    public function import(array $items, $locale = null, $currency = null)
    {
        $this->locale = !empty($locale) ? $locale : getDefaultLocale();
        $this->currency = !empty($currency) ? $currency : getDefaultCurrency();


        $chunks = array_chunk($items, 200);

        foreach ($chunks as $chunkItems) {
            foreach ($chunkItems as $item) {
                $newProduct = $this->makeProduct($item);

                // Translation
                $this->handleProductTranslation($newProduct->id, $item);

                // thumbnail
                if (!empty($item['thumbnail_url'])) {
                    $this->handleProductThumbnail($newProduct, $item['thumbnail_url']);
                }

            }
        }

    }

    private function makeProduct($data)
    {
        $currencyItem = getCurrencyItemByCurrency($this->currency);

        return Product::create([
            'creator_id' => !empty($data['creator_id']) ? $data['creator_id'] : auth()->id(),
            'type' => $data['type'],
            'slug' => Product::makeSlug($data['title']),
            'category_id' => $data['category_id'],
            'price' => convertPriceToDefaultCurrency($data['price'], $currencyItem),
            'inventory' => !empty($data['inventory']) ? $data['inventory'] : null,
            'status' => Product::$draft,
            'updated_at' => time(),
            'created_at' => time(),
        ]);
    }

    private function handleProductTranslation($productId, $data)
    {
        ProductTranslation::query()->updateOrCreate([
            'product_id' => $productId,
            'locale' => mb_strtolower($this->locale),
        ], [
            'title' => $data['title'],
            'summary' => $data['summary'] ?? null,
            'description' => $data['description'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
        ]);
    }

    private function handleProductThumbnail($product, $url)
    {
        $imgPath = $url;

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $mimeType = $response->header('Content-Type');

                $extension = match ($mimeType) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp',
                    default => 'jpg', // fallback
                };

                $destination = "{$product->creator_id}/products/{$product->id}/thumbnail.{$extension}";
                $storage = Storage::disk('public');
                $storage->put($destination, $response->body());

                $imgPath = $storage->url($destination);
            }
        } catch (\Exception $e) {

        }

        if (!empty($imgPath)) {
            ProductMedia::updateOrCreate([
                'creator_id' => $product->creator_id,
                'product_id' => $product->id,
                'type' => ProductMedia::$thumbnail,
            ], [
                'path' => $imgPath,
                'created_at' => time(),
            ]);
        }
    }

    public function getValidatorRule(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', Product::$productTypes),
            'category_id' => 'required|exists:product_categories,id',
            'price' => 'required',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'inventory' => 'nullable|numeric',
            'creator_id' => 'nullable|exists:users,id',
            'thumbnail_url' => 'nullable|string',
        ];
    }
}
