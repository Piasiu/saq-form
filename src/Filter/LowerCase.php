<?php
namespace Saq\Form\Filter;

use JetBrains\PhpStorm\Pure;
use Saq\Form\Interface\FilterInterface;

class LowerCase implements FilterInterface
{
    /**
     * @inheritDoc
     */
    #[Pure]
    public function filter(mixed $value): mixed
    {
        return is_string($value) ? strtolower($value) : $value;
    }
}