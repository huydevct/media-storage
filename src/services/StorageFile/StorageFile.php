<?php

namespace Huy\MediaStorage\services\StorageFile;

use Huy\MediaStorage\helpers\MediaStorageHelper;
use Illuminate\Support\Facades\Storage;

class StorageFile
{
    static function uploadMultiImages(array $files)
    {
        return array_map(function ($file) {
            return self::uploadImage($file);
        }, $files);
    }

    static function uploadImage($file)
    {
        $image_config = config('game.image_resize');

        $original_name = $file->getClientOriginalName();
        $type_file = explode(".", $original_name);
        $type_file = end($type_file);
        $path = MediaStorageHelper::getPathSaveFile($type_file);
        $image_upload = MediaStorageHelper::resizeAndSaveImage($file, $path['path']);
        $url = [
            'full' => Storage::url("images/full/{$path['path']}")
        ];
        $url_pre = null;
        foreach ($image_config as $type => $size) {
            if ($image_upload['size']['width'] < $size) {
                $url[$type] = empty($url_pre) ? $url['full'] : $url_pre;
            } else {
                $url[$type] = Storage::url("images/$type/{$path['path']}");
            }
            $url_pre = $url[$type];
        }
        $image_insert[] = [
            "size" => $image_upload['size'],
            "path" => $path['path'],
            "url" => $url,
        ];
        return $image_insert;
    }

    static function uploadMultiVideos(array $files)
    {
        return array_map(function ($file) {
            return self::uploadVideo($file);
        }, $files);
    }

    static function uploadVideo($file)
    {
        $video_config = config('game.video_resize');

        $original_name = $file->getClientOriginalName();
        $type_file = explode(".", $original_name);
        $type_file = end($type_file);
        $path = MediaStorageHelper::getPathSaveFile($type_file);
        $video_upload = MediaStorageHelper::resizeAndSaveVideo(file_get_contents($file->getPathname()), $path['path']);

        $url = [
            'full' => Storage::url("videos/full/{$path['path']}")
        ];
        $url_pre = null;
        foreach ($video_config as $type => $size) {
            if ($video_upload['size']['width'] < $size) {
                $url[$type] = empty($url_pre) ? $url['full'] : $url_pre;
            } else {
                $url[$type] = Storage::url("videos/$type/{$path['path']}");
            }
            $url_pre = $url[$type];
        }

        $video_insert[] = [
            "size" => $video_upload['size'],
            "path" => $path['path'],
            "url" => $url,
        ];
        return $video_insert;
    }
}