<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class Integer extends Validator
{
    const INVALID = 'integerInvalid';
    const TOO_SMALL = 'integerTooSmall';
    const TOO_LARGE = 'integerTooLarge';
    const TOO_SMALL_INCLUSIVE = 'integerTooSmallInclusive';
    const TOO_LARGE_INCLUSIVE = 'integerTooLargeInclusive';
    
    /**
     * @var int|null
     */
    private ?int $minValue = null;
    
    /**
     * @var int|null
     */
    private ?int $maxValue = null;
    
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
        self::isValid($value);

        if (!@preg_match('/^(-)?[0-9]+$/', $value))
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
     * @param int $minValue
     * @return Integer
     */
    public function setMinValue(int $minValue): Integer
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinValue(): ?int
    {
        return $this->minValue;
    }

    /**
     * @param int $maxValue
     * @return Integer
     */
    public function setMaxValue(int $maxValue): Integer
    {
        $this->maxValue = $maxValue;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxValue(): ?int
    {
        return $this->maxValue;
    }

    /**
     * @param bool $inclusive
     * @return Integer
     */
    public function setInclusive(bool $inclusive): Integer
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