<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Reward\PointsReward;
use App\Entity\RewardInfoInterface;
use App\Util\Random;

/**
 * @package App\Service
 */
class PointsRewardService extends AbstractService implements RewardStrategyInterface
{
    /**
     * @var PointsReward[]
     */
    private $rewardList;

    /**
     * @inheritdoc
     */
    public function factory(array $data): RewardInfoInterface
    {
        $id = (int) $data['id'];
        if (!isset($this->rewardList[$id])) {
            $this->rewardList[$id] = new PointsReward($data);
        }
        return $this->rewardList[$id];
    }

    /**
     * @inheritdoc
     */
    public function isApplicable(RewardInfoInterface $reward): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function processAward(RewardInfoInterface $reward): ?array
    {
        if ($reward instanceof PointsReward) {
            return [
                'type' => 'points',
                'amount' => Random::int($reward->getMinAmount(), $reward->getMaxAmount()),
            ];
        }
        return null;
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
        // @todo increase user balance
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {
        $this->rewardList = [];
    }
}