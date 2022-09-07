<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class Decimal extends Validator
{
    const INVALID = 'decimalInvalid';
    const TOO_SMALL = 'decimalTooSmall';
    const TOO_LARGE = 'decimalTooLarge';
    const TOO_SMALL_INCLUSIVE = 'decimalTooSmallInclusive';
    const TOO_LARGE_INCLUSIVE = 'decimalTooLargeInclusive';

    /**
     * @var float|null
     */
    private ?float $minValue = null;
    
    /**
     * @var float|null
     */
    private ?float $maxValue = null;
    /**
     * @var bool
     */
    private bool $inclusive = true;

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        parent::isValid($value);

        if (!@preg_match('/^-?\d+(\.\d+)?$/', $value))
        {
            $this->addError(self::INVALID);
            return false;
        }
        
        if ($this->isInclusive())
        {
            if ($this->getMinValue() !== null && $value < $this->getMinValue())
            {
                $this->addError(self::TOO_SMALL_INCLUSIVE, ['minValue' => $this->getMinValue()]);
                return false;
            }
            elseif ($this->getMaxValue() !== null && $value > $this->getMaxValue())
            {
                $this->addError(self::TOO_LARGE_INCLUSIVE, ['maxValue' => $this->getMaxValue()]);
                return false;
            }
        }
        else
        {
            if ($this->getMinValue() !== null && $value <= $this->getMinValue())
            {
                $this->addError(self::TOO_SMALL, ['minValue' => $this->getMinValue()]);
                return false;
            }
            elseif ($this->getMaxValue() !== null && $value >= $this->getMaxValue())
            {
                $this->addError(self::TOO_LARGE, ['maxValue' => $this->getMaxValue()]);
                return false;
            }
        }

        return true;
    }

    /**
     * @param float $minValue
     * @return Decimal
     */
    public function setMinValue(float $minValue): Decimal
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMinValue(): ?float
    {
        return $this->minValue;
    }

    /**
     * @param float $maxValue
     * @return Decimal
     */
    public function setMaxValue(float $maxValue): Decimal
    {
        $this->maxValue = $maxValue;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getMaxValue(): ?float
    {
        return $this->maxValue;
    }

    /**
     * @param bool $inclusive
     * @return Decimal
     */
    public function setInclusive(bool $inclusive): Decimal
    {
        $this->inclusive = $inclusive;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInclusive(): bool
    {
        return $this->inclusive;
    }
}