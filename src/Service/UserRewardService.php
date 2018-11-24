<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\RewardInfoInterface;
use App\Entity\RewardType;
use App\Entity\UserReward;

/**
 * @package App\Service
 */
class UserRewardService extends AbstractService
{
    /**
     * @var UserReward|null
     */
    private $reward;

    /**
     * @return UserReward|null
     */
    public function getCurrentReward(): ?UserReward
    {
        if (null === $this->reward) {
            if (!empty($_SESSION['reward'])) {
                $type = RewardType::forId($_SESSION['reward']['type']);
                $info = $this->serviceLocator->getRewardService()->findRewardById($_SESSION['reward']['reward_id']);
                $this->reward = new UserReward($type, $info, $_SESSION['reward']['data']);
            } else {
                $this->reward = false;
            }
        }
        return false === $this->reward ? null : $this->reward;
    }

    /**
     * @param RewardType $type
     * @param RewardInfoInterface $rewardInfo
     * @param array $data
     * @return UserReward
     */
    public function saveReward(RewardType $type, RewardInfoInterface $rewardInfo, array $data): UserReward
    {
        if (null !== $this->getCurrentReward()) {
            throw new \BadMethodCallException('User already has the reward');
        }
        $this->reward = new UserReward($type, $rewardInfo, $data);
        $_SESSION['reward'] = [
            'type' => $type->getId(),
            'reward_id' => $rewardInfo->getId(),
            'data' => $data,
        ];
        return $this->reward;
    }

    /**
     * @param UserReward $reward
     */
    public function removeReward(UserReward $reward)
    {
        unset($_SESSION['reward']);
    }
}
