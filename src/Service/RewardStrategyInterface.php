<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\RewardInfoInterface;

/**
 * @package App\Service
 */
interface RewardStrategyInterface
{
    /**
     * @param array $data
     * @return RewardInfoInterface
     */
    public function factory(array $data): RewardInfoInterface;

    /**
     * @param RewardInfoInterface $reward
     * @return bool
     */
    public function isApplicable(RewardInfoInterface $reward): bool;

    /**
     * @param RewardInfoInterface $reward
     * @return array|null
     */
    public function processAward(RewardInfoInterface $reward): ?array;

    /**
     * @param RewardInfoInterface $reward
     * @param array $data
     */
    public function claim(RewardInfoInterface $reward, array $data);

    /**
     * @param RewardInfoInterface $reward
     * @param array $data
     */
    public function decline(RewardInfoInterface $reward, array $data);
}
