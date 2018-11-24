<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * @package App\Entity
 */
class RewardChance
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var RewardType
     */
    private $type;

    /**
     * @var float
     */
    private $weight;

    /**
     * @var float
     */
    private $percent;

    /**
     * @var Interval
     */
    private $interval;

    /**
     * @var RewardInfoInterface
     */
    private $info;

    /**
     * @param int $id
     * @param float $weight
     * @param RewardType $type
     * @param RewardInfoInterface $info
     */
    public function __construct(int $id, float $weight, RewardType $type, RewardInfoInterface $info)
    {
        $this->id = $id;
        $this->type = $type;
        $this->weight = $weight;
        $this->info = $info;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return RewardType
     */
    public function getType(): RewardType
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return RewardInfoInterface
     */
    public function getInfo(): RewardInfoInterface
    {
        return $this->info;
    }

    /**
     * @return float
     */
    public function getPercent(): float
    {
        return $this->percent;
    }

    /**
     * @param float $value
     * @return RewardChance
     */
    public function setPercent(float $value): self
    {
        $this->percent = $value;
        return $this;
    }

    /**
     * @return Interval
     */
    public function getInterval(): Interval
    {
        if (null === $this->interval) {
            $this->interval = new Interval();
        }
        return $this->interval;
    }
}
