<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;
use Throwable;
use App\Helpers\Checkers;
use App\Helpers\FilterCredentials;
use App\Models\User;
use App\Models\Product;

class AddProductHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $authData = $request->getAttributes('auth');
        $auth = $authData['auth'];
        $check = new Checkers();
        $requiredParams = ["description", "price", "pictureLink", "title"];
        $reqData = $request->getParsedBody();
        try {
            if (!$check->checkRequiredParams($requiredParams, $reqData)) {
                throw new Exception(
                    "Required arguments not found"
                );
            }

            //check description and price params
            $validator = new FilterCredentials();
            if(!$validator->validateMaxLength($reqData['description'])) {
                throw new Exception(
                    "Product description exceed max length"
                );
            }

            if(!$validator->validatePriceRequirement($reqData['price'])) {
                throw new Exception(
                    "Incorrect price number format"
                );
            }
            //save to db
            //check if user exist in db
            $id = $auth['user']['id'];
            $dbUser = User::find($id);
            if(!$dbUser) {
                throw new Exception(
                    "Error: Failed to find user account"
                );
            }
            $newProduct = Product::create([
                'title' => $reqData['title'],
                'price' => $reqData['price'],
                'description' => $reqData['description'],
                'pictureLink' => $reqData['pictureLink'],
                'idUserFk' => $id
            ]);

        } catch (Throwable $e) {
            return new JsonResponse([
                "error" => $e->getMessage(),
            ], 400);
        }

        return new JsonResponse([
            "New product" => $newProduct
        ]);
    }
}
