<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled?
    |--------------------------------------------------------------------------
    |
    | By default, Route Browser is only enabled in the 'local' environment,
    | and only when debugging is enabled.
    |
    */

    'enabled' => env('ROUTE_BROWSER_ENABLED', null),

    /*
    |--------------------------------------------------------------------------
    | Route Path
    |--------------------------------------------------------------------------
    |
    | The URI path where Route Browser will be accessible.
    | The default is /routes, but you can change it to avoid conflicts.
    |
    */

    'path' => 'routes',

    /*
    |--------------------------------------------------------------------------
    | Exclude Routes
    |--------------------------------------------------------------------------
    |
    | Paths to exclude from the list. May contain wildcards.
    |
    */

    'exclude' => [
        '/_debugbar/*', // https://github.com/barryvdh/laravel-debugbar
        '/_ignition/*', // https://github.com/facade/ignition
        '/horizon', '/horizon/*', // https://laravel.com/docs/horizon
        '/ignition-vendor/*', // https://flareapp.io/docs/ignition-for-laravel/third-party-extensions
        '/telescope', '/telescope/*', // https://laravel.com/docs/telescope
        '/tinker', // https://github.com/spatie/laravel-web-tinker
    ],

    // Exclude Route Browser's own routes?
    'exclude-self' => true,

];
