<?php

namespace Wispiring\WeatherService\Server\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController
{
    public function indexAction(Application $app, Request $request)
    {
        return new Response($app['twig']->render(
            'admin/index.html.twig',
            []
        ));
    }
}
