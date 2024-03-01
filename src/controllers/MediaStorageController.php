<?php

namespace Huy\MediaStorage\controllers;

use Huy\MediaStorage\requests\UploadRequest;
use Huy\MediaStorage\services\StorageFile\StorageFile;
use Illuminate\Routing\Controller as BaseController;

class MediaStorageController extends BaseController
{
    function uploadImage(UploadRequest $request)
    {
        $params = $request->validated();
        if (isset($params['file'])) {
            $data_return = StorageFile::uploadImage($params['file']);
        } else if (isset($params['files'])) {
            $data_return = StorageFile::uploadMultiImages($params['files']);
        } else {
            return response()->json(["message" => "Not found file upload"], 404);
        }

        if ($data_return == false){
            return response()->json(["message" => "Upload file fail!"], 500);
        }

        return response()->json($data_return);
    }

    function uploadVideo(UploadRequest $request)
    {
        $params = $request->validated();
        if (isset($params['file'])) {
            $data_return = StorageFile::uploadVideo($params['file']);
        } else if (isset($params['files'])) {
            $data_return = StorageFile::uploadMultiVideos($params['files']);
        } else {
            return response()->json(["message" => "Not found file upload"], 404);
        }

        if ($data_return == false){
            return response()->json(["message" => "Upload file fail!"], 500);
        }

        return response()->json($data_return);
    }
}