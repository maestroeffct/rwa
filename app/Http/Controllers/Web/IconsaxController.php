<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class IconsaxController extends Controller
{

    private function getAllIconsName()
    {
        return cache()->rememberForever('iconsax_names', function () {
            $path = base_path('vendor/saade/blade-iconsax/resources/svg');
            $files = File::files($path);

            return collect($files)
                ->map(fn ($f) => $f->getFilenameWithoutExtension())
                ->sort()
                ->values()
                ->all();
        });
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $name = $request->input('name');

        $icons = collect($this->getAllIconsName());
        $result = $icons->filter(fn ($iconsName) => str_contains($iconsName, strtolower($name)))
            ->values()
            ->all();

        return response()->json([
            'code' => 200,
            'results' => collect($result)->map(fn ($name) => [
                'id'   => $name,
                'text' => $name,
                'svg'  => svg("iconsax-{$name}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])->toHtml(),
            ]),
        ]);
    }

}
