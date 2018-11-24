<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Item;
use App\Util\Lock\FileLock;

/**
 * @package App\Service
 */
class ItemService extends AbstractService
{
    /**
     * @var
     */
    private $dbPath;

    /**
     * @var Item[]
     */
    private $itemList;

    /**
     * @param int $itemId
     * @return Item|null
     */
    public function findById(int $itemId): ?Item
    {
        return $this->itemList[$itemId] ?? null;
    }

    /**
     * @param Item $item
     * @return int
     */
    public function getAvailableCount(Item $item): int
    {
        $fileName = $this->dbPath . '/item_' . $item->getId() . '.txt';
        return (int) file_get_contents($fileName);
    }

    /**
     * @param Item $item
     * @param int $count
     * @return bool
     */
    public function decreaseQuantity(Item $item, int $count = 1): bool
    {
        $lock = new FileLock($this->dbPath . '/item_' . $item->getId() . '.lock');
        if (!$lock->lock()) {
            return false;
        }
        $fileName = $this->dbPath . '/item_' . $item->getId() . '.txt';
        $currentCount = (int) file_get_contents($fileName);
        if ($currentCount < $count) {
            $lock->unlock();
            return false;
        }
        $currentCount -= $count;
        $result = file_put_contents($fileName, (string)$currentCount);
        $lock->unlock();
        if (false === $result) {
            throw new \RuntimeException(sprintf('Failed to update item count in file "%s"', $fileName));
        }
        return true;
    }

    /**
     * @param Item $item
     * @param int $count
     * @return bool
     */
    public function increaseQuantity(Item $item, int $count = 1): bool
    {
        $lock = new FileLock($this->dbPath . '/item_' . $item->getId() . '.lock');
        if (!$lock->lock()) {
            return false;
        }
        $fileName = $this->dbPath . '/item_' . $item->getId() . '.txt';
        $currentCount = (int) file_get_contents($fileName);
        $currentCount += $count;
        $result = file_put_contents($fileName, (string)$currentCount);
        $lock->unlock();
        if (false === $result) {
            throw new \RuntimeException(sprintf('Failed to update item count in file "%s"', $fileName));
        }
        return true;
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {
        $this->dbPath = __DIR__ . '/../../var/db';
        $this->itemList = [];
        foreach ($config['items'] as $itemConfig) {
            $item = new Item($itemConfig);
            $this->itemList[$item->getId()] = $item;
        }
    }
}
