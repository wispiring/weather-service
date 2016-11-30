<?php

namespace Wispiring\WeatherService\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use RuntimeException;

class JsonFileUserProvider implements UserProviderInterface
{
    private $jsonfilepath;

    public function __construct($jsonfilepath)
    {
        $this->jsonfilepath = $jsonfilepath;
        if (!file_exists($jsonfilepath)) {
            throw new RuntimeException('jsonfilepath does not exist.'.$jsonfilepath);
        }
    }

    private function loadConfigurationFromFile()
    {
        $jsonfilename = $this->jsonfilepath;
        if (!file_exists($jsonfilename)) {
            throw new UsernameNotFoundException('security config file does not exist.');
        }
        $json = file_get_contents($jsonfilename);

        return json_decode($json, true);
    }

    public function loadUserByUsername($username)
    {
        $config = $this->loadConfigurationFromFile();
        $userConfig = $config['users'][$username];
        if (!$userConfig) {
            throw new UsernameNotFoundException(sprintf('User %s is not found.', $username));
        }

        return new User($username, $userConfig['password'], $userConfig['roles'], true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}
