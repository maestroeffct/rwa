<?php

namespace App\Mixins\BulkImports\Drivers;


use App\Mixins\BulkImports\IImportChannel;
use App\Models\Translation\WebinarTranslation;
use App\Models\Webinar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CoursesBulkImports implements IImportChannel
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
                $newCourse = $this->makeNewCourse($item);

                // Translation
                $this->handleCourseTranslation($newCourse->id, $item);

                // thumbnail
                if (!empty($item['thumbnail_url'])) {
                    $this->handleCourseThumbnail($newCourse, $item['thumbnail_url']);
                }

            }
        }

    }

    private function makeNewCourse($data)
    {
        $currencyItem = getCurrencyItemByCurrency($this->currency);

        return Webinar::create([
            'type' => $data['type'],
            'slug' => Webinar::makeSlug($data['title']),
            'category_id' => $data['category_id'],
            'teacher_id' => !empty($data['instructor_id']) ? $data['instructor_id'] : auth()->id(),
            'creator_id' => !empty($data['organization_id']) ? $data['organization_id'] : auth()->id(),
            'price' => convertPriceToDefaultCurrency($data['price'], $currencyItem),
            'status' => Webinar::$isDraft,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    private function handleCourseTranslation($courseId, $data)
    {
        WebinarTranslation::updateOrCreate([
            'webinar_id' => $courseId,
            'locale' => mb_strtolower($this->locale),
        ], [
            'title' => $data['title'],
            'summary' => $data['summary'] ?? null,
            'description' => $data['description'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
        ]);
    }

    private function handleCourseThumbnail($course, $url)
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

                $destination = "{$course->creator_id}/webinars/{$course->id}/thumbnail.{$extension}";
                $storage = Storage::disk('public');
                $storage->put($destination, $response->body());

                $imgPath = $storage->url($destination);
            }
        } catch (\Exception $e) {

        }

        $course->update([
            'thumbnail' => $imgPath,
        ]);
    }

    public function getValidatorRule(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', Webinar::$allTypes),
            'category_id' => 'required|exists:categories,id',
            'price' => 'required',
            'summary' => 'nullable|string',
            'description' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'organization_id' => 'nullable|exists:users,id',
            'instructor_id' => 'nullable|exists:users,id',
            'thumbnail_url' => 'nullable|string',
        ];
    }

}
