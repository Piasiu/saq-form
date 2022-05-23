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
    protected string $name = '';

    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @var bool
     */
    private bool $required;

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
     */
    public function __construct(string $name, bool $required = false, mixed $emptyValue = '')
    {
        $this->name = $name;
        $this->formName = $name;
        $this->required = $required;
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
                    $this->errors[] = $validator->getErrors();

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
     * @return mixed
     */
    public function getEmptyValue(): mixed
    {
        return $this->emptyValue;
    }

    /**
     * @param string $name
     * @return FilterInterface|null
     */
    public function getFilter(string $name): ?FilterInterface
    {
        return isset($this->filters[$name]) ? $this->filters[$name] : null;
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[$filter::class] = $filter;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeFilter(string $name): self
    {
        unset($this->filters[$name]);
        return $this;
    }

    /**
     * @param string $name
     * @return ValidatorInterface|null
     */
    public function getValidator(string $name): ?ValidatorInterface
    {
        return isset($this->validators[$name]) ? $this->validators[$name] : null;
    }

    /**
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function addValidator(ValidatorInterface $validator): self
    {
        $this->validators[$validator::class] = $validator;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeValidator(string $name): self
    {
        unset($this->validators[$name]);
        return $this;
    }
}