<?php

namespace Wispiring\WeatherService\Formatter;

use Wispiring\WeatherService\Model\Weather;
use PDO;

class PdoFormatter
{
    private $pdo;
    private $weather;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setWeather(Weather $weather)
    {
        $this->weather = $weather;

        return $this;
    }

    public function format()
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO current
             (stamp, temperature_c) VALUES (:stamp, :temperature_c)'
        );
        $statement->execute([
            'stamp' => $this->weather->getStamp(),
            'temperature_c' => $this->weather->getTemperature(),
        ]);
    }
}
