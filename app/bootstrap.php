<?php

use Wispiring\WeatherService\Application;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app->before(function (Request $request, Application $app) {
    if (isset($app['parameters']['baseurl'])) {
        $app['request_context']->setBaseUrl($app['parameters']['baseurl']);
    }
    $app['twig']->addGlobal('current_route', $request->get('_route'));
});

return $app;
