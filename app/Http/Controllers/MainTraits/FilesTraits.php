<?php

namespace App\Http\Controllers\MainTraits;

use App\Mixins\BunnyCDN\BunnyVideoStream;
use Illuminate\Support\Facades\Storage;

trait FilesTraits
{

    // Define disallowed MIME types for file upload (security risk files)
    public $disallowedFileMimeTypes = [
        'application/x-httpd-php',
        'application/x-php',
        'application/php',
        'application/x-httpd-php-source',
        'application/x-httpd-cgi',
        'text/x-php',
        'text/php',
        'application/x-msdownload',
        'application/x-msmetafile',
        'application/x-msdos-program',
        'application/x-javascript',
        'application/x-phtml',
        'application/x-perl',
        'application/x-python',
        'application/x-ruby',
        'application/x-sh',
    ];

    public $disallowedFileExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phar', 'phps', 'cgi', 'pl', 'py', 'rb', 'sh', 'exe', 'dll', 'bat', 'cmd', 'js'];


    /**
     * @param $file \Illuminate\Http\UploadedFile
     * @param $destination string
     * @param $fileName string|null
     * @param $userId integer|null
     * @param $storage string|null
     *
     * @return string|null
     * */
    public function uploadFile($file, $destination, $fileName = null, $userId = null, $storage = "public"): string|null
    {
        // Validate file MIME type - reject dangerous file types
        $mimeType = $file->getMimeType();
        $fileOriginalExtension = strtolower($file->getClientOriginalExtension());

        if (in_array($mimeType, $this->disallowedFileMimeTypes) or in_array($fileOriginalExtension, $this->disallowedFileExtensions)) {
            //throw new \Exception('File type not allowed for security reasons: ' . $mimeType);
            return null;
        }

        $originalName = $file->getClientOriginalName();
        $name = $fileName ? $fileName . '.' . $fileOriginalExtension : $originalName;

        if ($storage == "bunny") {
            return $this->uploadToBunny($file, $name);
        } else {
            $storage = Storage::disk($storage);

            $path = (!empty($userId) ? '/' . $userId : '') . '/' . $destination;

            if (!$storage->exists($path)) {
                $storage->makeDirectory($path);
            }

            $storage->putFileAs($path, $file, $name);

            $url = $path . '/' . $name;

            return $storage->url($url);
        }
    }

    public function removeFile($path, $storage = "public")
    {
        $storage = Storage::disk($storage);

        $path = str_replace('/store', '', $path);

        if ($storage->exists($path)) {
            $storage->delete($path);
        }
    }

    private function uploadToBunny($file, $name)
    {
        try {
            $bunnyVideoStream = new BunnyVideoStream();

            $collectionId = $bunnyVideoStream->createCollection($name);

            if ($collectionId) {

                $videoUrl = $bunnyVideoStream->uploadVideo($name, $collectionId, $file);

                return $videoUrl;
            }
        } catch (\Exception $ex) {
            //dd($ex);
        }

        return null;
    }

}
