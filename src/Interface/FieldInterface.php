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
    public function isTransparent(): bool;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @param string $name
     * @return bool
     */
    public function hasFilter(string $name): bool;

    /**
     * @param string $name
     * @return FilterInterface|null
     */
    public function getFilter(string $name): ?FilterInterface;

    /**
     * @param FilterInterface $filter
     * @return FieldInterface
     */
    public function addFilter(FilterInterface $filter): FieldInterface;

    /**
     * @param string $name
     * @return FieldInterface
     */
    public function removeFilter(string $name): FieldInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function hasValidator(string $name): bool;

    /**
     * @param string $name
     * @return ValidatorInterface|null
     */
    public function getValidator(string $name): ?ValidatorInterface;

    /**
     * @param ValidatorInterface $validator
     * @return FieldInterface
     */
    public function addValidator(ValidatorInterface $validator): FieldInterface;

    /**
     * @param string $name
     * @return FieldInterface
     */
    public function removeValidator(string $name): FieldInterface;
}