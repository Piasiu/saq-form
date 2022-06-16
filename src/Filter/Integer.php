<?php
namespace Saq\Form\Filter;

use Saq\Form\Interface\FilterInterface;

class Integer implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function filter(mixed $value): int
    {
        return (int)$value;
    }
}