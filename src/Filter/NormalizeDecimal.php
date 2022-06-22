<?php
namespace Saq\Form\Filter;

use Saq\Form\Interface\FilterInterface;

class NormalizeDecimal implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function filter(mixed $value): mixed
    {
        $result = preg_replace('/,/', '.', $value);

        if ($result === null)
        {
            return $value;
        }

        return $result;
    }
}