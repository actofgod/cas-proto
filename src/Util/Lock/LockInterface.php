<?php
declare(strict_types=1);

namespace App\Util\Lock;

/**
 * @package App\Util\Lock
 */
interface LockInterface
{
    /**
     * @param bool $block
     * @return bool
     */
    public function lock(bool $block = true): bool;

    /**
     */
    public function unlock();
}