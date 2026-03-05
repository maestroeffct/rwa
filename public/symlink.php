
<?php
$targetFolder1 = $_SERVER['DOCUMENT_ROOT'].'/../laravel/storage/app/public';
$linkFolder1 = $_SERVER['DOCUMENT_ROOT'].'/storage';
symlink($targetFolder1,$linkFolder1);

$targetFolder2 = $_SERVER['DOCUMENT_ROOT'].'/../laravel/storage/app/images';
$linkFolder2 = $_SERVER['DOCUMENT_ROOT'].'/images';
symlink($targetFolder2,$linkFolder2);

$targetFolder2 = $_SERVER['DOCUMENT_ROOT'].'/../laravel/storage/app/public/bin';
$linkFolder2 = $_SERVER['DOCUMENT_ROOT'].'/bin';
symlink($targetFolder2,$linkFolder2);

echo 'Symlink process successfully completed';