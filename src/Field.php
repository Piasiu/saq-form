<?php
namespace Saq\Form;

use Saq\Form\Interface\FieldInterface;
use Saq\Form\Interface\FilterInterface;
use Saq\Form\Interface\ValidatorInterface;

class Field extends FormElement implements FieldInterface
{
    /**
     * @var string
     */
    private string $name = '';

    /**
     * @var mixed
     */
    private mixed $value;

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @var bool
     */
    private bool $required;

    /**
     * @var bool
     */
    private bool $transparent;

    /**
     * @var mixed
     */
    private mixed $emptyValue;
    /**
     * @var FilterInterface[]
     */
    protected array $filters = [];

    /**
     * @var ValidatorInterface[]
     */
    protected array $validators = [];

    /**
     * @param string $name
     * @param bool $required
     * @param mixed $emptyValue
     * @param bool $transparent
     */
    public function __construct(string $name, bool $required = false, mixed $emptyValue = '', bool $transparent = false)
    {
        $this->name = $name;
        $this->formName = $name;
        $this->required = $required;
        $this->transparent = $transparent;
        $this->emptyValue = $emptyValue;
        $this->value = $emptyValue;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        $isValid = true;
        $this->errors = [];

        if ($this->value !== $this->getEmptyValue())
        {
            $this->errors = [];

            foreach ($this->validators as $validator)
            {
                if (!$validator->isValid($this->value))
                {
                    $isValid = false;
                    $this->errors = array_merge($this->errors, $validator->getErrors());

                    if ($validator->isInterrupt())
                    {
                        break;
                    }
                }
            }
        }
        elseif ($this->isRequired())
        {
            $isValid = false;
            $this->errors[] = new Error(Error::IS_REQUIRED);
        }

        return $isValid;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $formName
     */
    public function setFormName(string $formName): void
    {
        $this->formName = $formName;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function setValue(mixed $data): void
    {
        $this->value = $data;

        foreach ($this->filters as $filter)
        {
            $this->value = $filter->filter($this->value);
        }
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isTransparent(): bool
    {
        return $this->transparent;
    }

    /**
     * @param bool $transparent
     * @return Field
     */
    public function setTransparent(bool $transparent): Field
    {
        $this->transparent = $transparent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmptyValue(): mixed
    {
        return $this->emptyValue;
    }

    /**
     * @inheritDoc
     */
    public function hasFilter(string $name): bool
    {
        return isset($this->filters[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getFilter(string $name): ?FilterInterface
    {
        return $this->hasFilter($name) ? $this->filters[$name] : null;
    }

    /**
     * @inheritDoc
     */
    public function addFilter(FilterInterface $filter): FieldInterface
    {
        $this->filters[$filter::class] = $filter;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeFilter(string $name): FieldInterface
    {
        unset($this->filters[$name]);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasValidator(string $name): bool
    {
        return isset($this->validators[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getValidator(string $name): ?ValidatorInterface
    {
        return $this->hasValidator($name) ? $this->validators[$name] : null;
    }

    /**
     * @inheritDoc
     */
    public function addValidator(ValidatorInterface $validator): FieldInterface
    {
        $this->validators[$validator::class] = $validator;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeValidator(string $name): FieldInterface
    {
        unset($this->validators[$name]);
        return $this;
    }
}