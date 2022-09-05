<?php
namespace Saq\Form\Interface;

interface ContextInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool;

    /**
     * @param string $name
     * @return FieldInterface|null
     */
    public function getField(string $name): ?FieldInterface;
}