<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class Number extends Validator
{
    const INVALID = 'numberInvalid';
    const TOO_SMALL = 'numberTooSmall';
    const TOO_LARGE = 'numberTooLarge';
    const TOO_SMALL_INCLUSIVE = 'numberTooSmallInclusive';
    const TOO_LARGE_INCLUSIVE = 'numberTooLargeInclusive';
    
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
        if (!@preg_match('/^\-?\d+\,?\.?\d*$/iu', $value))
        {
            $this->addError(self::INVALID);
            return false;
        }
        
        $value = str_replace(',', '.', $value);

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
     * @return Number
     */
    public function setMinValue(float $minValue): Number
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
     * @return Number
     */
    public function setMaxValue(float $maxValue): Number
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
     * @return Number
     */
    public function setInclusive(bool $inclusive): Number
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