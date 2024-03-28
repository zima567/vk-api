<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Handler\UploadHandler;
use Psr\Http\Server\RequestHandlerInterface;

class UploadHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new UploadHandler();
    }
}
