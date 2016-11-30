<?php

namespace Wispiring\WeatherService;

use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SecurityServiceProvider as SilexSecurityServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Parser as YamlParser;
use Silex\Provider\SessionServiceProvider;
use PDO;
use RuntimeException;

class Application extends SilexApplication
{
    private $pdo;

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->configureParameters();
        $this->configureService();
        $this->configurePdo();
        $this->configureRoutes();
        $this->configureSecurity();
        $this->configureTheme();
        $this->configureTemplate();
    }

    private function getConfigFromParameters()
    {
        if (!$this->offsetExists('parameters')) {
            $parser = new YamlParser();
            $this['parameters'] = $parser->parse(file_get_contents(__DIR__.'/../app/config/parameters.yml'));
        }

        return $this['parameters'];
    }

    private function configureParameters()
    {
        $this['debug'] = false;
        $parameters = $this->getConfigFromParameters();
        if (isset($parameters['debug'])) {
            $this['debug'] = !!$parameters['debug'];
        }
    }

    private function configureService()
    {
        $this->register(new RoutingServiceProvider());
        $this->register(new SessionServiceProvider(), [
            'session.storage.save_path' => '/tmp/wispiring_weather_sessions',
        ]);
    }

    private function configurePdo()
    {
        $parameters = $this->getConfigFromParameters();
        if (isset($parameters['database']) && !$this->offsetExists('pdo')) {
            foreach ($parameters['database'] as $dbName => $conf) {
                $this['pdo'] = new PDO(
                    ('mysql:host='.($conf['host'] ?: 'localhost').';dbname='.$dbName),
                    $conf['username'],
                    $conf['password']
                );
                break;
            }
        }
    }

    private function configureRoutes()
    {
        $locator = new FileLocator(array(__DIR__.'/../app/config'));
        $loader = new YamlFileLoader($locator);
        $this['routes'] = $loader->load('routes.yml');
    }

    private function configureSecurity()
    {
        $this->register(new SilexSecurityServiceProvider(), []);

        $parameters = $this->getConfigFromParameters();
        $security = $parameters['security'];

        if (isset($security['encoder'])) {
            $digest = '\\Symfony\\Component\\Security\\Core\\Encoder\\'.$security['encoder'];
            $this['security.encoder.digest'] = new $digest(true);
        }

        $this['security.firewalls'] = [
            'default' => [
                'stateless' => true,
                'pattern' => '^/',
                'http' => true,
                'users' => $this->getUserSecurityProvider(),
            ],
        ];
    }

    private function getUserSecurityProvider()
    {
        $parameters = $this->getConfigFromParameters();
        foreach ($parameters['security']['providers'] as $provider => $providerConfig) {
            switch ($provider) {
                case 'JsonFile':
                    return new \Wispiring\WeatherService\Security\JsonFileUserProvider(
                        __DIR__.'/../'.$providerConfig['path']
                    );
                default:
                    break;
            }
        }
        throw new RuntimeException('Cannot find any security provider');
    }

    private function configureTheme()
    {
        $parameters = $this->getConfigFromParameters();

        $theme = isset($parameters['theme']) ? $parameters['theme'] : 'default';
        $themePath = __DIR__.'/../themes/'.$theme;
        if (!file_exists($themePath)) {
            throw new RuntimeException('Invalid theme: '.$theme);
        }
        $this['themePath'] = $themePath;
    }

    private function configureTemplate()
    {
        $this->register(new TwigServiceProvider(), [
            'twig.path' => __DIR__.'/Resources/views/',
        ]);
        $this['twig.loader.filesystem']->addPath(
            $this['themePath'],
            'Theme'
        );
    }
}
