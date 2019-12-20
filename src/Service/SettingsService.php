<?php

namespace App\Service;

use App\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingsService {

    /**
     * @var array
     */
    private $settings;

    function __construct(ContainerInterface $container) {
        $this->settings = $container->getParameter('strichliste');
    }

    /**
     * @param bool $includePrivateSettings
     * @return array
     */
    function getAll(bool $includePrivateSettings = false) {
        if ($includePrivateSettings) {
            return $this->settings;
        } else {
            return array_filter($this->settings, function($key) {
                return $key[0] !== '_';
            }, ARRAY_FILTER_USE_KEY);
        }
    }

    /**
     * @param string $path
     * @param null $default
     * @return array|mixed|null
     */
    function getOrDefault(string $path, $default = null) {
        try {
            return $this->get($path);
        } catch (ParameterNotFoundException $e) {
            return $default;
        }
    }

    /**
     * @param string $path
     * @return array|mixed
     * @throws ParameterNotFoundException
     */
    function get(string $path) {
        $parts = explode('.', $path);

        $settings = $this->settings;
        foreach($parts as $part) {
            if (!isset($settings[$part])) {
                $part = '_' . $part;
            }

            if (!isset($settings[$part])) {
                throw new ParameterNotFoundException($path);
            }

            $settings = $settings[$part];
        }

        return $settings;
    }
}