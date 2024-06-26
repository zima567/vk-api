<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\ViewProductsHandler;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ViewProductsHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        //database setting configurations
        $capsule = new Capsule();
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $config = $container->get('config')['database']['container'];
        $capsule->addConnection($config);

        return new ViewProductsHandler($capsule);
    }
}
