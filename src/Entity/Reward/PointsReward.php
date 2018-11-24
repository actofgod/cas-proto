<?php
declare(strict_types=1);

namespace App\Entity\Reward;

use App\Entity\RewardInfoInterface;

/**
 * @package App\Entity\Reward
 */
class PointsReward implements RewardInfoInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $minimumAmount;

    /**
     * @var int
     */
    private $maximumAmount;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->minimumAmount = $data['amount']['min'] ?? 0;
        $this->maximumAmount = $data['amount']['max'] ?? PHP_INT_MAX;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMinAmount(): int
    {
        return $this->minimumAmount;
    }

    /**
     * @return int
     */
    public function getMaxAmount(): int
    {
        return $this->maximumAmount;
    }
}
