<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * @package App\Entity
 */
class Interval
{
    /**
     * @var float
     */
    private $leftBound;

    /**
     * @var float
     */
    private $rightBound;

    /**
     * @param float $leftBound
     * @param float $rightBound
     */
    public function __construct(float $leftBound = PHP_INT_MAX, float $rightBound = PHP_INT_MAX)
    {
        $this->leftBound = $leftBound;
        $this->rightBound = $rightBound;
    }

    /**
     * @param float $value
     * @return Interval
     */
    public function setLeftBound(float $value): Interval
    {
        $this->leftBound = $value;
        return $this;
    }

    /**
     * @param float $value
     * @return Interval
     */
    public function setRightBound(float $value): Interval
    {
        $this->rightBound = $value;
        return $this;
    }

    /**
     * @param float $value
     * @return bool
     */
    public function isValueInInterval(float $value): bool
    {
        return $this->leftBound <= $value && $value < $this->rightBound;
    }
}
