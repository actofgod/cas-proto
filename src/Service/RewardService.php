<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\RewardChance;
use App\Entity\RewardInfoInterface;
use App\Entity\RewardType;
use App\Entity\UserReward;
use App\Util\Random;

/**
 * @package App\Service
 */
class RewardService extends AbstractService
{
    /**
     * @var RewardStrategyInterface[]
     */
    private $rewardStrategyList;

    /**
     * @var array
     */
    private $config;

    /**
     * @var RewardChance[]
     */
    private $rewardList;

    /**
     * @var RewardInfoInterface[]
     */
    private $allRewardList;

    /**
     * @var float
     */
    private $max;

    /**
     * @return array|null
     */
    public function rotate(): ?array
    {
        $retriesCount = 0;
        do {
            $this->refresh($this->config);
            if (empty($this->rewardList)) {
                return null;
            }
            $value = Random::float(0.0, $this->max);
            foreach ($this->rewardList as $rewardChance) {
                if ($rewardChance->getInterval()->isValueInInterval($value)) {
                    break;
                }
            }
            $result = $this->processReward($rewardChance);
            $retriesCount++;
            if ($retriesCount > 3) {
                return null;
            }
        } while (null === $result);
        return $result;
    }

    /**
     * @param RewardChance $rewardChance
     * @return array|null
     */
    public function processReward(RewardChance $rewardChance): ?UserReward
    {
        $strategy = $this->rewardStrategyList[$rewardChance->getType()->getId()];
        $result = $strategy->processAward($rewardChance->getInfo());
        if (null !== $result) {
            $result['percent'] = $rewardChance->getPercent();

            $resultInstance = $this->serviceLocator->get(UserRewardService::class)
                ->saveReward($rewardChance->getType(), $rewardChance->getInfo(), $result);
        }
        return $resultInstance;
    }

    /**
     * @param int $id
     * @return RewardInfoInterface|null
     */
    public function findRewardById(int $id): ?RewardInfoInterface
    {
        return $this->allRewardList[$id] ?? null;
    }

    /**
     * @param UserReward $reward
     */
    public function claim(UserReward $reward)
    {
        $this->rewardStrategyList[$reward->getType()->getId()]->claim($reward->getReward(), $reward->getData());
    }

    /**
     * @param UserReward $reward
     */
    public function decline(UserReward $reward)
    {
        $this->rewardStrategyList[$reward->getType()->getId()]->decline($reward->getReward(), $reward->getData());
    }

    /**
     * @param array $config
     */
    protected function init(array $config)
    {
        $this->rewardStrategyList = [
            RewardType::ITEM => $this->serviceLocator->get(ItemRewardService::class),
            RewardType::MONEY => $this->serviceLocator->get(MoneyService::class),
            RewardType::POINTS => $this->serviceLocator->get(PointsRewardService::class),
        ];

        $this->allRewardList = [];
        foreach ($config['rewards'] as $id => $item) {
            $type = RewardType::forType($item['type']);
            $reward = $this->rewardStrategyList[$type->getId()]->factory($item['reward']);
            $this->allRewardList[$reward->getId()] = $reward;
        }

        $this->config = $config;
    }

    /**
     * @param array $config
     */
    private function refresh(array $config): void
    {
        $this->max = 0;
        $this->rewardList = [];
        foreach ($config['rewards'] as $id => $item) {
            $type = RewardType::forType($item['type']);
            $strategy = $this->rewardStrategyList[$type->getId()];
            $chance = new RewardChance($id, $item['weight'], $type, $this->allRewardList[$item['reward']['id']]);
            if ($strategy->isApplicable($chance->getInfo())) {
                $this->rewardList[] = $chance;
                $chance->getInterval()->setLeftBound($this->max);
                $this->max += $chance->getWeight();
                $chance->getInterval()->setRightBound($this->max);
            }
        }
        foreach ($this->rewardList as $reward) {
            $reward->setPercent($reward->getWeight() * 100.0 / $this->max);
        }
    }
}
