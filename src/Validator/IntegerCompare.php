<?php
namespace Saq\Form\Validator;

use JetBrains\PhpStorm\Pure;
use Saq\Form\ContextValidator;
use Saq\Form\Interface\ContextInterface;

class IntegerCompare extends ContextValidator
{
    const MODE_LARGE    = 1;
    const MODE_SMALL    = 2;
    
    const TOO_SMALL             = 'integerCompareTooSmall';
    const TOO_LARGE             = 'integerCompareTooLarge';
    const TOO_SMALL_INCLUSIVE   = 'integerCompareTooSmallInclusive';
    const TOO_LARGE_INCLUSIVE   = 'integerCompareTooLargeInclusive';
    
    /**
     * @var string
     */
    private string $fieldName;
    
    /**
     * @var string
     */
    private string $fieldLabel;
    
    /**
     * @var int
     */
    private int $mode = self::MODE_LARGE;
    
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
            $otherValue = $this->getContext()->getField($this->fieldName)->getValue();
            
            if ($this->inclusive)
            {
                if ($this->mode == self::MODE_SMALL)
                {
                    if ($value > $otherValue)
                    {
                        $this->addError(self::TOO_LARGE_INCLUSIVE, ['field' => $this->fieldLabel]);
                        return false;
                    }
                }
                elseif ($value < $otherValue)
                {
                    $this->addError(self::TOO_SMALL_INCLUSIVE, ['field' => $this->fieldLabel]);
                    return false;
                }
            }
            else
            {
                if ($this->mode == self::MODE_SMALL)
                {
                    if ($value >= $otherValue)
                    {
                        $this->addError(self::TOO_LARGE, ['field' => $this->fieldLabel]);
                        return false;
                    }
                }
                elseif ($value <= $otherValue)
                {
                    $this->addError(self::TOO_SMALL, ['field' => $this->fieldLabel]);
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * @param int $mode
     * @return IntegerCompare
     */
    public function setMode(int $mode): IntegerCompare
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
     * @return IntegerCompare
     */
    public function setInclusive(bool $inclusive): IntegerCompare
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