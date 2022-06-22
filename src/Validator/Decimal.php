<?php
namespace App\Field;

use Saq\Form\Validator;

class Decimal extends Validator
{
    const INVALID = 'decimalInvalid';
    const TOO_SMALL = 'decimalTooSmall';
    const TOO_LARGE = 'decimalTooLarge';
    const TOO_SMALL_INCLUSIVE = 'decimalTooSmallInclusive';
    const TOO_LARGE_INCLUSIVE = 'decimalTooLargeInclusive';
    
    /**
     * @var string
     */
    private string $pattern = '-?\d{1,3}(\.\d{1,2})?';

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
        if (!@preg_match('/^'.$this->pattern.'$/iu', $value))
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
     * @param string $symbol
     * @param int $numberOfDigitsBeforeDot
     * @param int $numberOfDigitsAfterDot
     * @return Decimal
     */
    public function setPattern(string $symbol, int $numberOfDigitsBeforeDot, int $numberOfDigitsAfterDot): Decimal
    {
        if ($numberOfDigitsBeforeDot > 0 && $numberOfDigitsAfterDot > 0)
        {
            $this->pattern = sprintf('-?\d{1,%s}(\%s\d{1,%s})?', $numberOfDigitsBeforeDot, $symbol, $numberOfDigitsAfterDot);
        }

        return $this;
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