<?php

declare(strict_types=1);

namespace App\Middlewares;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Exception;
use Throwable;

class AuthorizationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            $authData = $request->getAttributes('auth');
            $auth = $authData['auth'];
            if(!$auth['isAuthenticated']) {
                throw new Exception(
                    "Authentication required."
                );
            }
        } catch (Throwable $e) {
            return new JsonResponse([
                "error" => $e->getMessage(),
            ]);
        }

        return $handler->handle($request);
    }
}