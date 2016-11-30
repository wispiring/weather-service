<?php

namespace Wispiring\WeatherService\Formatter;

use Symfony\Component\Console\Output\OutputInterface;
use Wispiring\WeatherService\Model\Weather;

class ConsoleFormatter
{
    private $output;
    private $weather;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function setWeather(Weather $weather)
    {
        $this->weather = $weather;

        return $this;
    }

    public function format()
    {
        $output = $this->output;
        $weather = $this->weather;

        $output->writeln(
            '<comment>City: '.$this->normalizeChar($weather->getCity()).' - '.
            $this->normalizeChar($weather->getCountry()).'</comment>'
        );

        $output->writeln('<info>Current: '.$weather->getTemperature()->getValue().'</info>');
        $output->writeln('<info>Min.: '.$weather->getMinTemperature()->getValue().'</info>');
        $output->writeln('<info>Max.: '.$weather->getMaxTemperature()->getValue().'</info>');

        $output->writeln(
            '<info>Pressure: '.$weather->getPressure()->getValue().' '.$weather->getPressure()->getUnit().'</info>'
        );
        $output->writeln(
            '<info>Humidity: '.$weather->getHumidity()->getValue().' '.$weather->getHumidity()->getUnit().'</info>'
        );

        $output->writeln('<info>Sunrise: '.$weather->getSunrise()->format('r').'</info>');
        $output->writeln('<info>Sunset: '.$weather->getSunset()->format('r').'</info>');
    }

    private function normalizeChar($string)
    {
        return str_replace('&deg;C', '℃', $string);
    }
}
