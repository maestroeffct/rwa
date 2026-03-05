<?php

namespace App\Enums;

class UploadSource
{
    const YOUTUBE = 'youtube';
    const VIMEO = 'vimeo';
    const UPLOAD = 'upload';
    const S3 = 's3';
    const EXTERNAL = 'external';
    const IFRAME = 'iframe';
    const SECURE_HOST = 'secure_host';

    const allSources = [
        self::YOUTUBE,
        self::VIMEO,
        self::UPLOAD,
        self::S3,
        self::EXTERNAL,
        self::IFRAME,
        self::SECURE_HOST,
    ];

    const uploadItems = [self::UPLOAD, self::S3, self::SECURE_HOST];
    const urlPathItems = [
        self::YOUTUBE,
        self::VIMEO,
        self::EXTERNAL,
        self::IFRAME,
    ];
}
