<?php

declare(strict_types=1);

namespace App\Handler;

use App\Helpers\FileUploader;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class UploadHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uploadedFiles = $request->getUploadedFiles();
        try {
            if (!count($uploadedFiles)) {
                throw new Exception(
                    "No file was specified"
                );
            }
            $uploadedFile = $uploadedFiles['pictureLink'];

            $fileUploader = new FileUploader();
            $result = $fileUploader->imageUploader($uploadedFile);
            if($result['uploadPath'] === "") {
                throw new Exception(
                    $result['error']
                );
            }

        } catch (Throwable $e) {
            return new JsonResponse([
                "error" => $e->getMessage(),
            ], 400);
        }

        return new JsonResponse([
            "filePath" => $result['uploadPath']
        ]);
    }
}
