<?php
declare(strict_types=1);

namespace App\Entity\Reward;

use App\Entity\Item;
use App\Entity\RewardInfoInterface;

/**
 * @package App\Entity\Reward
 */
class ItemReward implements RewardInfoInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Item
     */
    private $item;

    /**
     * @param int $id
     * @param Item $item
     */
    public function __construct(int $id, Item $item)
    {
        $this->id = $id;
        $this->item = $item;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }
}