<?php
namespace Saq\Form;

use Saq\Form\Interface\ValidatorInterface;

abstract class Validator implements ValidatorInterface
{
    /**
     * @var Error[]
     */
    private array $errors = [];

    /**
     * @var bool
     */
    private bool $interrupt;

    /**
     * @param bool $interrupt
     */
    public function __construct(bool $interrupt = false)
    {
        $this->interrupt = $interrupt;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $name
     * @param array $arguments
     */
    public function addError(string $name, array $arguments = []): void
    {
        $this->errors[] = new Error($name, $arguments);
    }

    /**
     * @inheritDoc
     */
    public function isValid(mixed $value): bool
    {
        $this->errors = [];
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isInterrupt(): bool
    {
        return $this->interrupt;
    }

    /**
     * @param bool $interrupt
     * @return $this
     */
    public function setInterrupt(bool $interrupt): self
    {
        $this->interrupt = $interrupt;
        return $this;
    }
}