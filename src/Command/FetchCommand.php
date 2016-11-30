<?php

namespace Wispiring\WeatherService\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cmfcmf\OpenWeatherMap;
use Wispiring\WeatherService\Application;
use Wispiring\WeatherService\Provider\OpenWeatherMapProvider;
use Wispiring\WeatherService\Formatter\ConsoleFormatter;
use Wispiring\WeatherService\Formatter\PdoFormatter;

class FetchCommand extends Command
{
    protected function configure()
    {
        $this->setName('weather:fetch')
            ->setDescription('Weather command')
            ->addArgument(
                'place',
                InputArgument::REQUIRED,
                'The name of the city or place, e.g. Shenyang'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $place = $input->getArgument('place');

        $app = new Application();
        $parameters = $app['parameters'];
        $apiKey = $parameters['weather']['providers']['openweathermap']['api_key'];

        $provider = new OpenWeatherMapProvider();
        $provider->setApiKey($apiKey)
            ->setLanguage('en')
            ->setUnit('metric')
            ->setPlaceByName($place);
        $weather = $provider->getWeather();

        $formatter = new PdoFormatter($app['pdo']);
        $formatter->setWeather($weather)->format();

        $formatter = new ConsoleFormatter($output);
        $formatter->setWeather($weather)->format();
    }

    private function normalizeChar($string)
    {
        return str_replace('&deg;C', '℃', $string);
    }
}
