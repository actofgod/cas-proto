<?php
declare(strict_types=1);

namespace App\Service;

/**
 * @package App\Service
 */
abstract class AbstractService
{
    /**
     * @var ServiceLocatorService
     */
    protected $serviceLocator;

    /**
     * @param ServiceLocatorService $serviceLocator
     * @param array $config
     */
    public function __construct(ServiceLocatorService $serviceLocator, array $config)
    {
        $this->serviceLocator = $serviceLocator;
        $this->init($config);
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {}
}