<?php
declare(strict_types=1);

namespace App\Service;

/**
 * @package App\Service
 */
class ServiceLocatorService
{
    /**
     * @var array
     */
    private $serviceList;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->serviceList = [
            self::class => $this,
        ];
        foreach ($config['services'] as $className => $config) {
            $this->serviceList[$className] = $config;
        }
    }

    /**
     * @param string $className
     * @param array $config
     */
    public function register(string $className, array $config): void
    {
        $this->serviceList[$className] = $config;
    }

    /**
     * @param string $className
     * @return AbstractService
     */
    public function get(string $className): AbstractService
    {
        if (!isset($this->serviceList[$className])) {
            if (!class_exists($className)) {
                throw new \OutOfBoundsException(sprintf('Service "%s" is not found', $className));
            }
            $service = new $className($this, []);
            if (!$service instanceof AbstractService) {
                throw new \InvalidArgumentException(sprintf('Invalid service class name "%s"', $className));
            }
            $this->serviceList[$className] = $service;
        } elseif (is_array($this->serviceList[$className])) {
            $this->serviceList[$className] = new $className($this, $this->serviceList[$className]);
        }
        return $this->serviceList[$className];
    }

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        return $this->get(UserService::class);
    }

    /**
     * @return ItemService
     */
    public function getItemService(): ItemService
    {
        return $this->get(ItemService::class);
    }

    /**
     * @return RewardService
     */
    public function getRewardService(): RewardService
    {
        return $this->get(RewardService::class);
    }

    /**
     * @return UserRewardService
     */
    public function getUserRewardService(): UserRewardService
    {
        return $this->get(UserRewardService::class);
    }
}
