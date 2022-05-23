<?php
namespace Saq\Form\Interface;

interface ContextInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function hasValue(string $name): bool;

    /**
     * @param string $name
     * @return string|array
     */
    public function getValue(string $name): string|array;
}