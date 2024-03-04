<?php

namespace Huy\MediaStorage\helpers;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class MediaStorageHelper
{
    static function resizeAndSaveImage($image, $path)
    {
        $inter_image = ImageManager::imagick()->read($image);
        $width = $inter_image->width();
        $height = $inter_image->height();
        $sizes = config('media_storage.image_resize');
        $path_save = [
            'full' => 'images/full/' . $path
        ];
        Storage::put($path_save['full'], $inter_image->encode());
        foreach ($sizes as $type => $size) {
            if ($width >= $size) {
                $inter_image_cp = clone $inter_image;
                $inter_image_cp->scaleDown(width: $size);
                $image_path = "images/$type/" . $path;
                $path_save[$type] = $image_path;
                Storage::put($image_path, $inter_image_cp->encode());
            }
        }
        return [
            'size' => [
                'width' => $width,
                'height' => $height
            ],
            'path' => $path_save
        ];
    }
    static function getPathSaveFile(string $extension = 'jpeg')
    {
        $now = time();
        $file_name = "{$now}_" . Str::random(5) . ".$extension";
        $path = date('Y/m/d') . "/$file_name";
        return [
            'file_name' => $file_name,
            'path' => $path
        ];
    }

    static function getSizeVideo($video)
    {
        $data = $video->getStreams();
        return [
            $data->first()->get('width'),
            $data->first()->get('height')
        ];
    }

    static function resizeAndSaveVideo($file, $path)
    {
        $full_path = "videos/full/$path";
        Storage::put($full_path, $file);
        //Save file to tmp
        $video_tmp = "tmp/" . time() . "_" . Str::random(5) . ".mp4";
        Storage::disk('local')->put($video_tmp, $file);
        $ffmpegPath = exec('which ffmpeg');
        $ffprobe = exec('which ffprobe');
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => $ffmpegPath,
            'ffprobe.binaries' => $ffprobe,
        ]);
//        $ffmpeg->getFFMpegDriver()->listen(new \Alchemy\BinaryDriver\Listeners\DebugListener());
//        $ffmpeg->getFFMpegDriver()->on('debug', function ($message) {
//            echo $message."\n";
//        });
//        $video = $ffmpeg->open(StorageFile::path($full_path));
        $video = $ffmpeg->open(Storage::disk('local')->path($video_tmp));
        list($width, $height) = self::getSizeVideo($video);
        $sizes = config('media_storage.video_resize');
        $path_save = [
            'full' => $full_path
        ];

        foreach ($sizes as $type => $size) {
            if ($width >= $size) {
                $tmp_save = "tmp/" . time() . "_" . Str::random(5) . "_{$type}.mp4";
                $video_path = "videos/$type/" . $path;
                $video_cp = clone $video;
                $scale = $size / $width;
                $height_new = (int)round($height * $scale);
                $video_cp
                    ->filters()
                    //cut clip 5s
                    ->clip(TimeCode::fromSeconds(0), TimeCode::fromSeconds(5))
                    ->resize(new Dimension(ceil($size / 2) * 2, ceil($height_new / 2) * 2))
                    ->synchronize();
                $format = new X264('aac');
                //Create file tmp
                Storage::disk('local')->put($tmp_save, 'echo');
                //Save video to file tmp
                $video_cp->save($format, Storage::disk('local')->path($tmp_save));
                //Save video to storage
                Storage::put($video_path, Storage::disk('local')->get($tmp_save));
                $path_save[$type] = $video_path;
                //Delete file tmp
                Storage::disk('local')->delete($tmp_save);
            }
        }
        Storage::disk('local')->delete($video_tmp);
        return [
            'size' => [
                'width' => $width,
                'height' => $height
            ],
            'path' => $path_save
        ];
    }
}