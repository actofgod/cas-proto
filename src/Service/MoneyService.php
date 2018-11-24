<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Reward\MoneyReward;
use App\Entity\RewardInfoInterface;
use App\Util\Lock\FileLock;
use App\Util\Random;

/**
 * @package App\Service
 */
class MoneyService extends AbstractService implements RewardStrategyInterface
{
    /**
     * @var string
     */
    private $dbPath;

    /**
     * @var MoneyReward[]
     */
    private $rewardList;

    /**
     * @inheritdoc
     */
    public function factory(array $data): RewardInfoInterface
    {
        $id = (int) $data['id'];
        if (!isset($this->rewardList[$id])) {
            $this->rewardList[$id] = new MoneyReward($data);
        }
        return $this->rewardList[$id];
    }

    /**
     * @inheritdoc
     */
    public function isApplicable(RewardInfoInterface $reward): bool
    {
        if ($reward instanceof MoneyReward) {
            return $reward->getMinAmount() < $this->getMaxAmount();
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function processAward(RewardInfoInterface $reward): ?array
    {
        if ($reward instanceof MoneyReward) {
            $lock = new FileLock($this->dbPath . '/money.lock');
            if (!$lock->lock()) {
                return null;
            }
            $fileName = $this->dbPath . '/money.txt';
            $currentValue = (int) file_get_contents($fileName);
            if ($currentValue < $reward->getMinAmount()) {
                $lock->unlock();
                return null;
            }
            $max = $currentValue > $reward->getMaxAmount() ? $reward->getMaxAmount() : $currentValue;
            if ($reward->getMinAmount() < $max) {
                $value = Random::int($reward->getMinAmount(), $max);
            } else {
                $value = $max;
            }
            $result = file_put_contents($fileName, (string) ($currentValue - $value));
            $lock->unlock();
            if (false === $result) {
                throw new \RuntimeException(sprintf('Failed to save current money value to file "%s"', $fileName));
            }
            return [
                'type' => 'money',
                'amount' => [
                    'value' => $value,
                    'currency' => $reward->getCurrencyCode(),
                ]
            ];
        }
        return null;
    }

    /**
     * @return int
     */
    public function getMaxAmount(): int
    {
        $fileName = $this->dbPath . '/money.txt';
        return (int) file_get_contents($fileName);
    }

    /**
     * @inheritdoc
     */
    public function claim(RewardInfoInterface $reward, array $data)
    {
    }

    /**
     * @param RewardInfoInterface $reward
     * @param array $data
     */
    public function decline(RewardInfoInterface $reward, array $data)
    {
        if ($reward instanceof MoneyReward) {
            $lock = new FileLock($this->dbPath . '/money.lock');
            if (!$lock->lock()) {
                return null;
            }
            $fileName = $this->dbPath . '/money.txt';
            $currentValue = (int) file_get_contents($fileName);
            $result = file_put_contents($fileName, (string) ($currentValue - $data['amount']['value']));
            $lock->unlock();
            if (false === $result) {
                throw new \RuntimeException(sprintf('Failed to save current money value to file "%s"', $fileName));
            }
        }
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {
        $this->dbPath = __DIR__ . '/../../var/db';
        $this->rewardList = [];
    }
}
