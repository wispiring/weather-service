<?php

namespace Wispiring\WeatherService\Provider;

use Wispiring\WeatherService\Model\Place;
use Wispiring\WeatherService\Model\Weather;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

class OpenWeatherMapProvider extends BaseProvider implements ProviderInterface
{
    private $apiKey;

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    private $language = 'en';

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    private $handle;

    private function getHandle()
    {
        if ($this->handle === null) {
            // Get OpenWeatherMap object. Don't use caching (take a look into Example_Cache.php to see how it works).
            $this->handle = new OpenWeatherMap();
        }

        return $this->handle;
    }

    public function getWeather()
    {
        if (!$this->place || !$this->apiKey) {
            throw new \Exception('Place not set.');
        }

        $owm = $this->getHandle();
        $w = $owm->getWeather($this->place->getName(), $this->unit, $this->language, $this->apiKey);
        // try {
        //     $weather = $owm->getWeather($this->place, $this->unit, $this->language, $this->apiKey);
        // } catch (OWMException $e) {
        //     $output->writeln('<error>OpenWeatherMap exception: '.$e->getMessage().' (Code '.$e->getCode().').</error>');
        //     exit;
        // } catch (\Exception $e) {
        //     $output->writeln('<error>General exception: '.$e->getMessage().' (Code '.$e->getCode().').</error>');
        //     exit;
        // }
        $weather = new Weather();
        $weather->setCity($w->city->name)
            ->setCountry($w->city->country)
            ->setStamp()
            ->setTemperature($w->temperature->now)
            ->setMinTemperature($w->temperature->min)
            ->setMaxTemperature($w->temperature->max)
            ->setPressure($w->pressure)
            ->setHumidity($w->humidity)
            ->setSunrise($w->sun->rise)
            ->setSunset($w->sun->set)
        ;

        return $weather;
    }

    public function getForcast($days = 5)
    {
        $owm = $this->getHandle();
        $forecast = $owm->getWeatherForecast(
            $this->place->getName(),
            $this->unit,
            $this->language,
            $this->apiKey,
            $days
        );
    }
}
