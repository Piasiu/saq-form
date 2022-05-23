<?php
namespace Saq\Form\Filter;

use Saq\Form\Interface\FilterInterface;

class StringTrim implements FilterInterface
{
    const LEFT = -1;
    const BOTH = 0;
    const RIGHT = 1;

    /**
     * @var array
     */
    private array $patterns = [];

    /**
     * @param string $characters
     * @param int $position
     */
    public function __construct(string $characters = '\s', int $position = self::BOTH)
    {
        if ($position === self::LEFT)
        {
            $this->patterns[] = '/^['.$characters.']+/u';
        }
        elseif ($position === self::RIGHT)
        {
            $this->patterns = ['/['.$characters.']+$/u'];
        }
        else
        {
            $this->patterns = ['/^['.$characters.']+/u', '/['.$characters.']+$/u'];
        }
    }

    /**
     * @inheritDoc
     */
    public function filter(mixed $value): mixed
    {
        if (is_string($value))
        {
            foreach ($this->patterns as $patterns)
            {
                $value = preg_replace($patterns, '', $value);
            }
        }
        elseif (is_array($value))
        {
            foreach ($value as $i => $v)
            {
                $value[$i] = $this->filter($v);
            }
        }

        return $value;
    }
}