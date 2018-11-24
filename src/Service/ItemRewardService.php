<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Reward\ItemReward;
use App\Entity\RewardInfoInterface;

/**
 * @package App\Service
 */
class ItemRewardService extends AbstractService implements RewardStrategyInterface
{
    /**
     * @var string
     */
    private $dbPath;

    /**
     * @var ItemService
     */
    private $itemService;

    /**
     * @var ItemReward[]
     */
    private $rewardList;

    /**
     * @inheritdoc
     */
    public function factory(array $data): RewardInfoInterface
    {
        $id = (int) $data['id'];
        if (!isset($this->rewardList[$id])) {
            $item = $this->getItemService()->findById($data['item_id']);
            if (null === $item) {
                throw new \InvalidArgumentException(sprintf('Item with id#%d not exists', $data['item_id']));
            }
            $this->rewardList[$id] = new ItemReward($data['id'], $item);
        }
        return $this->rewardList[$id];
    }

    /**
     * @inheritdoc
     */
    public function isApplicable(RewardInfoInterface $reward): bool
    {
        if ($reward instanceof ItemReward) {
            return $this->getItemService()->getAvailableCount($reward->getItem()) > 0;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function processAward(RewardInfoInterface $reward): ?array
    {
        if ($reward instanceof ItemReward) {
            if ($this->getItemService()->decreaseQuantity($reward->getItem())) {
                return [
                    'type' => 'item',
                    'item_id' => $reward->getItem()->getId(),
                    'count' => 1,
                ];
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function claim(RewardInfoInterface $reward, array $data)
    {
        if ($reward instanceof ItemReward) {
            $fileName = $this->dbPath . 'post.csv';
            $fd = fopen($fileName, 'a+');
            if (!$fd) {
                throw new \RuntimeException(sprintf('Failed to save result to "%s"', $fileName));
            }
            flock($fd, LOCK_EX);
            fseek($fd, 0, SEEK_END);
            $line = [
                $_SESSION['user'],
                $reward->getItem()->getId(),
                $reward->getItem()->getName(),
            ];
            fwrite($fd, implode(',', $line) . PHP_EOL);
        }
    }

    /**
     * @param RewardInfoInterface $reward
     * @param array $data
     */
    public function decline(RewardInfoInterface $reward, array $data)
    {
        if ($reward instanceof ItemReward) {
            $this->getItemService()->increaseQuantity($reward->getItem());
        }
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {
        $this->rewardList = [];
        $this->dbPath = __DIR__ . '/../../var/db/';
    }

    /**
     * @return ItemService
     */
    private function getItemService(): ItemService
    {
        if (null === $this->itemService) {
            $this->itemService = $this->serviceLocator->getItemService();
        }
        return $this->itemService;
    }
}