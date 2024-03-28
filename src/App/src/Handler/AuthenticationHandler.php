<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;
use Throwable;
use App\Models\User;
use \Firebase\JWT\JWT;
use App\Helpers\Checkers;

class AuthenticationHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $jwtSecret = getenv('JWT_SECRET');
        $check = new Checkers();
        $requiredParams = ["password", "login"];
        $reqData = $request->getParsedBody();
        try {
            if (!$check->checkRequiredParams($requiredParams, $reqData)) {
                throw new Exception(
                    "Required arguments not found"
                );
            }

            $login = $reqData["login"];
            $password = $reqData["password"];
            $dbUser = User::firstWhere('login', $login);
            if(!$dbUser) {
                throw new Exception(
                    "No record found"
                );
            }
            $dbPassword = $dbUser->password;
            if(!password_verify($password, $dbPassword)) {
                throw new Exception(
                    "Wrong password"
                );
            }

        } catch (Throwable $e) {
            return new JsonResponse([
                "error" => $e->getMessage(),
            ], 401);
        }

        $issuedat_claim = time();
        //not before in seconds
        $notbefore_claim = $issuedat_claim + 10;
        //expire time in seconds
        $expire_claim = $issuedat_claim + 43200;
        $token = array(
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $dbUser->id,
                "login" => $dbUser->login
        ));

        $jwt = JWT::encode($token, $jwtSecret, 'HS256');

        return new JsonResponse([
            "token" => $jwt
        ]);
    }
}
