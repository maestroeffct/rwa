<?php

namespace App\Http\Controllers\Panel\Traits;

use App\Mixins\BunnyCDN\BunnyVideoStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait VideoDemoTrait
{
    private function handleVideoDemoData(Request $request, $userId, $data, $name)
    {
        if (!empty($data['video_demo_source'])) {
            if ($data['video_demo_source'] == "secure_host") {
                if (!empty($request->file('video_demo_file'))) {
                    $data = $this->uploadDemoToBunny($request, $name, $data);
                } else {
                    $data['video_demo_source'] = null;
                    $data['video_demo'] = null;
                }
            } else if ($data['video_demo_source'] == "s3") {
                $file = $request->file('video_demo_file');

                if (!empty($file)) {
                    $data = $this->uploadDemoToS3($data, $file, $userId);
                } else {
                    $data['video_demo_source'] = null;
                    $data['video_demo'] = null;
                }
            } else if (!in_array($data['video_demo_source'], ['upload', 'youtube', 'vimeo', 'external_link', 'google_drive', 'iframe'])) {
                $data['video_demo_source'] = null;
                $data['video_demo'] = null;
            }
        } else {
            $data['video_demo_source'] = null;
            $data['video_demo'] = null;
        }

        return $data;
    }

    private function uploadDemoToBunny(Request $request, $name, $data)
    {
        try {
            $bunnyVideoStream = new BunnyVideoStream();

            $file = $request->file('video_demo_file');

            $collectionId = $bunnyVideoStream->createCollection($name);

            if ($collectionId) {

                $videoUrl = $bunnyVideoStream->uploadVideo($file->getClientOriginalName(), $collectionId, $file);

                $data['video_demo'] = $videoUrl;
            }
        } catch (\Exception $ex) {
            //dd($ex);

            $data['video_demo'] = null;
        }

        return $data;
    }

    private function uploadDemoToS3($data, $file, $user_id)
    {
        $path = 'store/' . $user_id;

        try {
            $fileName = time() . $file->getClientOriginalName();

            $storage = Storage::disk('minio');

            if (!$storage->exists($path)) {
                $storage->makeDirectory($path);
            }

            $path = $storage->put($path, $file, $fileName);

            $demoPath = $storage->url($path);

            $data['video_demo'] = $demoPath;
        } catch (\Exception $ex) {
            //dd($ex);

            $data['video_demo'] = null;
        }

        return $data;
    }

}
