<?php
namespace Saq\Form\Validator;

use DateTime;
use RuntimeException;
use Saq\Form\Validator;

class Date extends Validator
{
    const INVALID_FORMAT        = 'dateInvalidFormat';
    const TOO_EARLY             = 'dateTooEarly';
    const TOO_LATE              = 'dateTooLate';
    const TOO_EARLY_INCLUSIVE   = 'dateTooEarlyInclusive';
    const TOO_LATE_INCLUSIVE    = 'dateTooLateInclusive';
    
    /**
     * @var string
     */
    private string $format = 'Y-m-d';
    
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
    
    public function isValid($value): bool
    {
        parent::isValid($value);

        if (!$this->isCorrectDate($value))
        {
            $this->addError(self::INVALID_FORMAT);
            return false;
        }
        
        $time = strtotime($value);
        
        if ($this->isInclusive())
        {
            if ($this->getMinValue() !== null && $time < strtotime($this->getMinValue()))
            {
                $this->addError(self::TOO_EARLY_INCLUSIVE, ['minValue' => $this->getMinValue()]);
                return false;
            }
            elseif ($this->getMaxValue() !== null && $time > strtotime($this->getMaxValue()))
            {
                $this->addError(self::TOO_LATE_INCLUSIVE, ['maxValue' => $this->getMaxValue()]);
                return false;
            }
        }
        else
        {
            if ($this->getMinValue() !== null && $time <= strtotime($this->getMinValue()))
            {
                $this->addError(self::TOO_EARLY, ['minValue' => $this->getMinValue()]);
                return false;
            }
            elseif ($this->getMaxValue() !== null && $time >= strtotime($this->getMaxValue()))
            {
                $this->addError(self::TOO_LATE, ['maxValue' => $this->getMaxValue()]);
                return false;
            }
        }
        
        return true;
    }

    /**
     * @param string $format
     * @return Date
     */
    public function setFormat(string $format): Date
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $minValue
     * @return Date
     */
    public function setMinValue(string $minValue): Date
    {
        if (!$this->isCorrectDate($minValue))
        {
            throw new RuntimeException('Incorrect date format for "minValue" option. The correct format is "'.$this->format.'".');
        }
        
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMinValue(): ?string
    {
        return $this->minValue;
    }

    /**
     * @param string $maxValue
     * @return Date
     */
    public function setMaxValue(string $maxValue): Date
    {
        if (!$this->isCorrectDate($maxValue))
        {
            throw new RuntimeException('Incorrect date format for "maxValue" option. The correct format is "'.$this->format.'".');
        }
        
        $this->maxValue = $maxValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaxValue(): ?string
    {
        return $this->maxValue;
    }

    /**
     * @param bool $inclusive
     * @return Date
     */
    public function setInclusive(bool $inclusive): Date
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

    /**
     * @param string $date
     * @return bool
     */
    private function isCorrectDate(string $date): bool
    {
        $d = DateTime::createFromFormat($this->format, $date);
        return $d && $d->format($this->format) === $date;
    }
}