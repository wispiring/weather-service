<?php

namespace Wispiring\WeatherService\Provider;

use Wispiring\WeatherService\Model\Place;

interface ProviderInterface
{
    public function getPlace();
    public function setPlace(Place $place);
    public function getUnit();
    public function setUnit($unit);
    public function getWeather();
    public function getForcast();
}
