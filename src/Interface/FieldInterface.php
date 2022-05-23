<?php
namespace Saq\Form\Interface;

interface FieldInterface extends FormElementInterface
{
    /**
     * @return mixed
     */
    public function getName(): string;

    /**
     * @param mixed $data
     * @return void
     */
    public function setValue(mixed $data): void;

    /**
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * @return mixed
     */
    public function getEmptyValue(): mixed;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return array
     */
    public function getErrors(): array;
}