<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;
use Throwable;
use App\Helpers\FilterCredentials;
use App\Models\User;
use App\Helpers\Checkers;

class RegistrationHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
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
            $filer = new FilterCredentials();
            if(!$filer->checkPassword($password) || !$filer->checkLogin($login)) {
                throw new Exception(
                    "Incorrect password/login."
                );
            }
            //verify dublicated login
            $dbUser = User::firstWhere('login', $login);
            if($dbUser) {
                throw new Exception(
                    "User with same credentials already exist"
                );
            }

            //hash password
            $options = [
                'cost' => 12,
            ];
            $hashpassword = password_hash($password, PASSWORD_BCRYPT, $options);
            $newUser = User::create([
                'login' => $login,
                'password' => $hashpassword
            ]);
            if($newUser) {
                $res = $newUser;
            }

        } catch (Throwable $e) {
            return new JsonResponse([
                "error" => $e->getMessage(),
            ], 400);
        }

        return new JsonResponse($res);
    }
}
