<?php
namespace Saq\Form;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Saq\Form\Interface\FieldInterface;

class MultiField extends FormElement implements FieldInterface, ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var callable $factory
     */
    private $factory;

    /**
     * @var Field[]
     */
    private array $fields = [];

    /**
     * @var int
     */
    private int $numberOfFields = 0;

    /**
     * @var bool
     */
    private bool $skipEmptyValue;

    /**
     * @var bool
     */
    private bool $required;

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @param string $name
     * @param callable $factory
     * @param bool $skipEmptyValue
     * @param bool $required
     */
    public function __construct(string $name, callable $factory, bool $skipEmptyValue = false, bool $required = false)
    {
        $this->name = $name;
        $this->formName = $name;
        $this->factory = $factory;
        $this->skipEmptyValue = $skipEmptyValue;
        $this->required = $required;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setValue(mixed $data): void
    {
        $this->fields = [];
        $this->numberOfFields = 0;

        if (is_array($data))
        {
            foreach ($data as $value)
            {
                $field = $this->createField();

                if (!$this->skipEmptyValue || $value != $field->getEmptyValue())
                {
                    $field->setValue($value);
                    $this->fields[] = $field;
                    $this->numberOfFields++;
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        $this->errors = [];

        if ($this->numberOfFields == 0 && $this->required)
        {
            $this->errors[] = new Error(Error::IS_REQUIRED);
            return false;
        }

        $isValid = true;

        foreach ($this->fields as $field)
        {
            if (!$field->isValid())
            {
                $isValid = false;
            }
        }

        return $isValid;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): array
    {
        $values = [];

        foreach ($this->fields as $field)
        {
            $values[] = $field->getValue();
        }

        return $values;
    }

    /**
     * @inheritDoc
     */
    public function getErrors(): array
    {
        $errors = $this->errors;

        foreach ($this->fields as $field)
        {
            $fieldErrors = $field->getErrors();

            if (!empty($fieldErrors))
            {
                $errors[] = $fieldErrors;
            }
        }

        return $errors;
    }

    /**
     * @inheritDoc
     */
    public function getEmptyValue(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function getFormName(): string
    {
        return parent::getFormName().'[]';
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->fields[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): ?Field
    {
        if ($this->offsetExists($offset))
        {
            return $this->fields[$offset];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof Field)
        {
            if (!$this->offsetExists($offset))
            {
                $this->numberOfFields++;
            }

            $this->fields[$offset] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this->offsetExists($offset))
        {
            unset($this->fields[$offset]);
            $this->numberOfFields--;
            $this->fields = array_values((array)$this->fields[$offset]);
        }
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function count(): int
    {
        return $this->numberOfFields;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->fields);
    }

    /**
     * @return Field
     */
    private function createField(): Field
    {
        $field = call_user_func($this->factory);

        if (!($field instanceof Field))
        {
            throw new RuntimeException(sprintf('The expected factory output is a %s object.', Field::class));
        }

        $field->setParent($this);
        $field->setFormName('');
        $field->setRequired(true);
        return $field;
    }
}