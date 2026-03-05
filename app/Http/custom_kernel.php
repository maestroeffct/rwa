<?php

namespace App\Http;

/**
 * Custom Kernel Class for User Customizations
 */
class CustomKernel
{
    /**
     * Custom global middleware to be added to the application
     * 
     * @var array
     */
    public static function getGlobalMiddleware()
    {
        return [
            // Add your custom global middleware here
            // Example: \App\Http\Middleware\YourCustomMiddleware::class,
        ];
    }

    /**
     * Custom middleware groups to be merged with the application's middleware groups
     * 
     * @var array
     */
    public static function getMiddlewareGroups()
    {
        return [
            'web' => [
                // Add your custom web middleware here
                // Example: \App\Http\Middleware\YourCustomWebMiddleware::class,
            ],
            'api' => [
                // Add your custom API middleware here
                // Example: \App\Http\Middleware\YourCustomApiMiddleware::class,
            ],
            // You can also define completely new middleware groups
            'custom_group' => [
                // \App\Http\Middleware\YourCustomGroupMiddleware::class,
            ],
        ];
    }

    /**
     * Custom route middleware to be merged with the application's route middleware
     * 
     * @var array
     */
    public static function getRouteMiddleware()
    {
        return [
            // Add your custom route middleware here
            // Example: 'custom.auth' => \App\Http\Middleware\YourCustomAuthMiddleware::class,
        ];
    }
} 