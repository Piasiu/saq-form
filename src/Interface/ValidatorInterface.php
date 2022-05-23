<?php
namespace Saq\Form\Interface;

use Saq\Form\Error;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool;

    /**
     * @return bool
     */
    public function isInterrupt(): bool;

    /**
     * @return Error[]
     */
    public function getErrors(): array;
}