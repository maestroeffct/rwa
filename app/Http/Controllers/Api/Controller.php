<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiResponseBuilderTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponseBuilderTrait;

    public static $auth;


    public function uploadFile($file, $destination, $fileName = null, $userId = null, $test = false): string
    {
        $storage = Storage::disk('public');

        $path = (!empty($userId) ? '/' . $userId : '') . '/' . $destination;

        if (!$storage->exists($path)) {
            $storage->makeDirectory($path);
        }

        $originalName = $file->getClientOriginalName();

        $name = $fileName ? $fileName . '.' . $file->getClientOriginalExtension() : $originalName;

        $path = $path . '/' . $name;

        $storage->put($path, file_get_contents($file));

        return $storage->url($path);
    }

    public function removeFile($path)
    {
        $storage = Storage::disk('public');

        $path = str_replace('/store', '', $path);

        if ($storage->exists($path)) {
            $storage->delete($path);
        }
    }
}
