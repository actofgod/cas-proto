<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * @package App\Entity
 */
class UserReward
{
    /**
     * @var RewardType
     */
    private $type;

    /**
     * @var RewardInfoInterface
     */
    private $info;

    /**
     * @var array
     */
    private $data;

    /**
     * @param RewardType $type
     * @param RewardInfoInterface $info
     * @param array $data
     */
    public function __construct(RewardType $type, RewardInfoInterface $info, array $data)
    {
        $this->type = $type;
        $this->info = $info;
        $this->data = $data;
    }

    /**
     * @return RewardType
     */
    public function getType(): RewardType
    {
        return $this->type;
    }

    /**
     * @return RewardInfoInterface
     */
    public function getReward(): RewardInfoInterface
    {
        return $this->info;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}