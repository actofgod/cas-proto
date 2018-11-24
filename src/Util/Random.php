<?php
declare(strict_types=1);

namespace App\Util;

/**
 * @package App\Util
 */
class Random
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function int(int $min = 0, int $max = PHP_INT_MAX): int
    {
        return \random_int($min, $max);
    }

    /**
     * @param float $min
     * @param float $max
     * @return float
     */
    public static function float(float $min = 0.0, float $max = 1.0): float
    {
        $value = self::int();
        return $min + $value / PHP_INT_MAX * ($max - $min);
    }
}
