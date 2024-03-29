<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class ViewProductsHandler implements RequestHandlerInterface
{
    private Capsule $capsule;

    /**
     * сonstructor
     */
    public function __construct(Capsule $capsule)
    {
        $this->capsule = $capsule;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //Check if user is authenticated and set user id
        $id = 0;
        $authData = $request->getAttributes('auth');
        if($authData['auth']['isAuthenticated']) {
            $id = $authData['auth']['user']['id'];
        }
        $reqData = $request->getQueryParams();
        $allProducts = [];

        //Get info from request or set their default values
        $orderType = $reqData['orderType'] ? strval($reqData['orderType']) : "login";
        $orderDirection = ($reqData['orderDirection'] && $reqData['orderDirection'] == 1)  ? "asc" : "desc";
        $limit = $reqData['limit'] ? intval($reqData['limit']) : 100;
        $offset = $reqData['offset'] ? intval($reqData['ofset']) : 0;
        $priceMin = $reqData['priceMin'] ? intval($reqData['priceMin']) : 0;
        $priceMax = $reqData['priceMax'] ? intval($reqData['priceMax']) : 0;
        
        //Query users products by price min and max if they were set
        if($priceMin !== 0 && $priceMax !== 0) {
            $allProducts = $this->capsule::table('users')
                ->join('products', 'users.id', '=', 'products.idUserFk')
                ->select('*')
                ->orderBy($orderType, $orderDirection)
                ->offset($offset)
                ->limit($limit)
                ->whereBetween('price', [$priceMin, $priceMax])
                ->get();
        } else {
            $allProducts = $this->capsule::table('users')
            ->join('products', 'users.id', '=', 'products.idUserFk')
            ->select('users.login', 'products.id', 'products.title', 'products.price', 'products.description', 'products.pictureLink', 'products.idUserFk')
            ->orderBy($orderType, $orderDirection)
            ->offset($offset)
            ->limit($limit)
            ->get();
        }

        //If user is authenticated return flag of product ownership
        if($id !== 0 && count($allProducts) !== 0) {
            foreach ($allProducts as $product) {
                $product->owner = $id == $product->idUserFk ? true : false;
            }
        }

        return new JsonResponse([
            "products" => $allProducts
        ]);
    }
}
