<?php

namespace Wispiring\WeatherService\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController
{
    public function indexAction(Application $app, Request $request)
    {
        return new JsonResponse(['hello' => 'world']);
    }
}
