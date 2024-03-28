<?php

declare(strict_types=1);

namespace App\Factory;

use App\Handler\RegistrationHandler;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RegistrationHandlerFactory
{
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        //настройка конфигурации базы данных
        $capsule = new Capsule();
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        $config = $container->get('config')['database']['container'];
        $capsule->addConnection($config);

        return new RegistrationHandler();
    }
}
