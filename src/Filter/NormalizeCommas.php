<?php
namespace Saq\Form\Filter;

use Saq\Form\Interface\FilterInterface;

class NormalizeCommas implements FilterInterface
{
    /**
     * @inheritDoc
     */
    public function filter(mixed $value): mixed
    {
        $result = preg_replace('/\s*,\s*/', ',', $value);

        if ($result === null)
        {
            return $value;
        }

        return $result;
    }
}