<?php

namespace Vantoozz\Oboom\ServiceProviders;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Vantoozz\Oboom\API;
use Vantoozz\Oboom\Auth;
use Vantoozz\Oboom\Transport\GuzzleTransport;

/**
 * Class Silex
 * @package Vantoozz\Oboom\ServiceProviders
 */
class Silex implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['oboom'] = $app->share(function ($app) {
            $transport = new GuzzleTransport();
            $auth = new Auth($transport);
            $auth->setConfig($app['config']['oboom']);
            $auth->login();

            return new API($transport, $auth);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}
