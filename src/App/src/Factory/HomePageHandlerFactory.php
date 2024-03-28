<?php

declare(strict_types=1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use App\Handler\HomePageHandler;
use Psr\Http\Server\RequestHandlerInterface;

class HomePageHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        return new HomePageHandler();
    }
}
