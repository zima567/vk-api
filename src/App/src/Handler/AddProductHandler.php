<?php

declare(strict_types=1);

namespace App\Handler;

use App\Helpers\Checkers;
use App\Helpers\Validator;
use App\Models\Product;
use App\Models\User;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class AddProductHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //Get array with authentication informations from $request
        $authData = $request->getAttributes('auth');
        $auth = $authData['auth'];
        
        //Verify that request has all the required fields
        $requiredParams = ["description", "price", "pictureLink", "title"];
        $reqData = $request->getParsedBody();
        $check = new Checkers();
        try {
            if (!$check->checkRequiredParams($requiredParams, $reqData)) {
                throw new Exception(
                    "Required arguments not found"
                );
            }
            //Set variables
            $currentDateTime = date('Y-m-d H:i:s');
            $title = $reqData['title'];
            $price = $reqData['price'];
            $description = $reqData['description'];
            $pictureLink = $reqData['pictureLink'];
            $id = $auth['user']['id'];

            //check title, description, price params
            $validator = new Validator();
            if(!$validator->validateMaxLengthTitle($title)) {
                throw new Exception(
                    "Product title exceed max length"
                );
            }

            if(!$validator->validateMaxLengthDesc($description)) {
                throw new Exception(
                    "Product description exceed max length"
                );
            }

            if(!$validator->validatePriceRequirement($price)) {
                throw new Exception(
                    "Incorrect price number format"
                );
            }

            //save to db
            //check if user exist in db
            $dbUser = User::find($id);
            if(!$dbUser) {
                throw new Exception(
                    "Error: Failed to find user account"
                );
            }

            $newProduct = Product::create([
                'title' => $title,
                'price' => $price,
                'description' => $description,
                'pictureLink' => $pictureLink,
                'createdAt' => $currentDateTime,
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
