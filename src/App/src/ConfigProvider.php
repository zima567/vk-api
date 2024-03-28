<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Middlewares\AuthorizationMiddleware::class => Middlewares\AuthorizationMiddleware::class,
                Middlewares\AuthMiddleware::class => Middlewares\AuthMiddleware::class, 
            ],
            'factories'  => [
                Handler\HomePageHandler::class => Factory\HomePageHandlerFactory::class,
                Handler\RegistrationHandler::class => Factory\RegistrationHandlerFactory::class,
                Handler\AuthenticationHandler::class => Factory\AuthenticationHandlerFactory::class,
                Handler\AddProductHandler::class => Factory\AddProductHandlerFactory::class,
                Handler\ViewProductsHandler::class => Factory\ViewProductsHandlerFactory::class,
                Handler\UploadHandler::class => Factory\UploadHandlerFactory::class,
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [],
        ];
    }
}
