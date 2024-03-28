<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $jwtSecret = getenv('JWT_SECRET');
        $authorizationHeader = $request->getHeaderLine('Authorization');
        $auth = [
            'message' => "",
            'isAuthenticated' => false,
            'user' => [
            ],
        ];

        try {
            if (strpos($authorizationHeader, 'Bearer ') === 0) {
                $token = substr($authorizationHeader, 7);
            }
            if($token) {
                $decodedToken = JWT::decode($token, new Key($jwtSecret, 'HS256'));
                $decodedToken = json_decode(json_encode($decodedToken), true);
                $auth['isAuthenticated'] = true;
                $auth['user'] = $decodedToken['data'];
            }

        } catch(UnexpectedValueException $e) {

            $auth['message'] = "Token is not valid";

        }
        //var_dump($auth); die;
        $request = $request->withAttribute('auth', $auth);
        return $handler->handle($request);
    }
}
