<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class StringLength extends Validator
{
    const INVALID = 'stringLengthInvalid';
    const TOO_SHORT = 'stringLengthTooShort';
    const TOO_LONG = 'stringLengthTooLong';
    
    /**
     * @var int|null
     */
    private ?int $minValue = null;
    
    /**
     * @var int|null
     */
    private ?int $maxValue = null;

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        self::isValid($value);

        if (!is_string($value))
        {
            $this->addError(self::INVALID);
            return false;
        }
        
        $length = mb_strlen($value, 'UTF-8');        

        if ($this->getMinValue() !== null && $length < $this->getMinValue())
        {
            $this->addError(self::TOO_SHORT, ['minValue' => $this->getMinValue()]);
            return false;
        }
        elseif ($this->getMaxValue() !== null && $length > $this->getMaxValue())
        {
            $this->addError(self::TOO_LONG, ['maxValue' => $this->getMaxValue()]);
            return false;
        }

        return true;
    }

    /**
     * @param int $minValue
     * @return StringLength
     */
    public function setMinValue(int $minValue): StringLength
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
     * @return StringLength
     */
    public function setMaxValue(int $maxValue): StringLength
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
}