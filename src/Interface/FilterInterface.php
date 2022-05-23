<?php
namespace Saq\Form\Interface;

interface FilterInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function filter(mixed $value): mixed;
}