<?php
namespace Saq\Form\Validator;

use DateTime;
use JetBrains\PhpStorm\Pure;
use Saq\Form\ContextValidator;
use Saq\Form\Interface\ContextInterface;

class DateCompare extends ContextValidator
{
    const MODE_LATE     = 1;
    const MODE_EARLY    = 2;
    
    const TOO_EARLY             = 'dateCompareTooEarly';
    const TOO_LATE              = 'dateCompareTooLate';
    const TOO_EARLY_INCLUSIVE   = 'dateCompareTooEarlyInclusive';
    const TOO_LATE_INCLUSIVE    = 'dateCompareTooLateInclusive';
    
    /**
     * @var string
     */
    private string $fieldName;
    
    /**
     * @var string
     */
    private string $fieldLabel;
    
    /**
     * @var string
     */
    private string $format = 'Y-m-d';
    
    /**
     * @var int
     */
    private int $mode = self::MODE_LATE;
    
    /**
     * @var bool
     */
    private bool $inclusive = true;

    /**
     * @param ContextInterface $context
     * @param string $fieldName
     * @param string $fieldLabel
     */
    #[Pure]
    public function __construct(ContextInterface $context, string $fieldName, string $fieldLabel = '')
    {
        parent::__construct($context);
        $this->fieldName = $fieldName;
        $this->fieldLabel = $fieldLabel;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        if ($this->getContext()->hasField($this->fieldName))
        {
            $time = DateTime::createFromFormat($this->format, $value)->getTimestamp();
            $otherTime = DateTime::createFromFormat($this->format, $this->getContext()->getField($this->fieldName)->getValue())->getTimestamp();
            
            if ($this->isInclusive())
            {
                if ($this->getMode() == self::MODE_EARLY)
                {
                    if ($time > $otherTime)
                    {
                        $this->addError(self::TOO_LATE_INCLUSIVE, ['field' => $this->fieldLabel]);
                        return false;
                    }
                }
                elseif ($time < $otherTime)
                {
                    $this->addError(self::TOO_EARLY_INCLUSIVE, ['field' => $this->fieldLabel]);
                    return false;
                }
            }
            else
            {
                if ($this->getMode() == self::MODE_EARLY)
                {
                    if ($time >= $otherTime)
                    {
                        $this->addError(self::TOO_LATE, ['field' => $this->fieldLabel]);
                        return false;
                    }
                }
                elseif ($time <= $otherTime)
                {
                    $this->addError(self::TOO_EARLY, ['field' => $this->fieldLabel]);
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * @param string $format
     * @return DateCompare
     */
    public function setFormat(string $format): DateCompare
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
     * @param int $mode
     * @return DateCompare
     */
    public function setMode(int $mode): DateCompare
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @param bool $inclusive
     * @return DateCompare
     */
    public function setInclusive(bool $inclusive): DateCompare
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