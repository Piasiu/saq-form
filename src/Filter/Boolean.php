<?php
namespace Saq\Form\Filter;

use Saq\Form\Interface\FilterInterface;

class Boolean implements FilterInterface
{
    /**
     * @var string
     */
    private string $trueValue = '1';

    /**
     * @var string
     */
    private string $falseValue = '0';

    /**
     * @inheritDoc
     */
    public function filter(mixed $value): string
    {
        return $value == $this->trueValue ? $value : $this->falseValue;
    }

    /**
     * @return string
     */
    public function getTrueValue(): string
    {
        return $this->trueValue;
    }

    /**
     * @param string $trueValue
     */
    public function setTrueValue(string $trueValue): void
    {
        $this->trueValue = $trueValue;
    }

    /**
     * @return string
     */
    public function getFalseValue(): string
    {
        return $this->falseValue;
    }

    /**
     * @param string $falseValue
     */
    public function setFalseValue(string $falseValue): void
    {
        $this->falseValue = $falseValue;
    }
}