<?php

declare(strict_types=1);

namespace App\Helpers;

use Laminas\Diactoros\UploadedFile;

/**
 * Предоставляет методы для ...
 *
 * @package Helpers
 */
class FileUploader
{
    public function imageUploader(UploadedFile $uploadedFile): array
    {
        $res = [
            'uploadPath' => "",
            'error' => ""
        ];

        if (!$uploadedFile->getError() === UPLOAD_ERR_OK) {
            $res['error'] = "Error occurred during file upload";
            return $res;
        }

        if ($uploadedFile->getSize() > 2097152) {
            $res['error'] = "File size exceeds the limit of 2MB";
            return $res;
        }

        $allowedExtensions = ['jpeg', 'jpg', 'png'];
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $allowedExtensions)) {
            $res['error'] = "Invalid file type. Only JPEG and PNG files are allowed";
            return $res;
        }
        
        $timestamp = time();
        $newFileName = $timestamp . '-' . $uploadedFile->getClientFilename();
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $uploadPath = $rootPath.'/upload/' . $newFileName;

        if (!move_uploaded_file($uploadedFile->getStream()->getMetadata('uri'), $uploadPath)) {
            $res['error'] = "Failed to move uploaded file";
            return $res;
        }
        $res['uploadPath'] = $uploadPath;
        return $res;
    }
}
