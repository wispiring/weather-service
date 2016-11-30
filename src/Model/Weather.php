<?php

namespace Wispiring\WeatherService\Model;

class Weather
{
    private $city;

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    private $country;

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    private $stamp;

    public function getStamp()
    {
        return $this->stamp;
    }

    public function setStamp($stamp = null)
    {
        $this->stamp = $stamp ?: time();

        return $this;
    }

    private $temperature;

    public function getTemperature()
    {
        return $this->temperature;
    }

    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    private $minTemperature;

    public function getMinTemperature()
    {
        return $this->minTemperature;
    }

    public function setMinTemperature($minTemperature)
    {
        $this->minTemperature = $minTemperature;

        return $this;
    }

    private $maxTemperature;

    public function getMaxTemperature()
    {
        return $this->maxTemperature;
    }

    public function setMaxTemperature($maxTemperature)
    {
        $this->maxTemperature = $maxTemperature;

        return $this;
    }

    private $pressure;

    public function getPressure()
    {
        return $this->pressure;
    }

    public function setPressure($pressure)
    {
        $this->pressure = $pressure;

        return $this;
    }

    private $humidity;

    public function getHumidity()
    {
        return $this->humidity;
    }

    public function setHumidity($humidity)
    {
        $this->humidity = $humidity;

        return $this;
    }

    private $sunrise;

    public function getSunrise()
    {
        return $this->sunrise;
    }

    public function setSunrise($sunrise)
    {
        $this->sunrise = $sunrise;

        return $this;
    }

    private $sunset;

    public function getSunset()
    {
        return $this->sunset;
    }

    public function setSunset($sunset)
    {
        $this->sunset = $sunset;

        return $this;
    }
}
