<?php
namespace Saq\Form;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;

class MultiForm extends FormElement implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var string
     */
    private string $name = '';

    /**
     * @var callable $factory
     */
    private $factory;

    /**
     * @var Form[]
     */
    private array $forms = [];

    /**
     * @var int
     */
    private int $numberOfForms = 0;

    /**
     * @var bool
     */
    private bool $skipEmptyValue;

    /**
     * @param callable $factory
     * @param bool $skipEmptyValue
     */
    public function __construct(callable $factory, bool $skipEmptyValue = false)
    {
        $this->factory = $factory;
        $this->skipEmptyValue = $skipEmptyValue;
    }

    /**
     * @return Form
     */
    public function createForm(): Form
    {
        /** @var Form $form */
        $form = call_user_func($this->factory);
        $form->setParent($this);
        $form->setName($this->numberOfForms);
        return $form;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->formName = $name;
    }

    /**
     * @param array $data
     */
    public function populate(array $data): void
    {
        $this->numberOfForms = 0;
        foreach ($data as $dataRow)
        {
            if (is_array($dataRow))
            {
                $form = $this->createForm();
                $form->populate($dataRow);
                $this->forms[] = $form;
                $this->numberOfForms++;
            }
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function isValid(array $data): bool
    {
        $isValid = true;
        $this->forms = [];

        foreach ($data as $dataRow)
        {
            $form = $this->createForm();
            $addRow = true;

            if ($this->skipEmptyValue)
            {
                $addRow = false;

                foreach ($form->getFields() as $name => $field)
                {
                    if (array_key_exists($name, $dataRow) && $dataRow[$name] !== $field->getEmptyValue())
                    {
                        $addRow = true;
                        break;
                    }
                }
            }

            if ($addRow)
            {
                $this->forms[] = $form;
                $this->numberOfForms++;

                if (!$form->isValid($dataRow))
                {
                    $isValid = false;
                }
            }
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        $values = [];

        foreach ($this->forms as $form)
        {
            $values[] = $form->getValues();
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->forms as $index => $form)
        {
            $formErrors = $form->getErrors();

            if (!empty($formErrors))
            {
                $errors[$index] = $formErrors;
            }
        }

        return $errors;
    }

    /**
     * @return Form[]
     */
    public function getForms(): array
    {
        return $this->forms;
    }

    /**
     * @inheritDoc
     */
    public function getFormName(): string
    {
        return parent::getFormName();//.'[]';
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->forms[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): ?Form
    {
        if ($this->offsetExists($offset))
        {
            return $this->forms[$offset];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof Form)
        {
            if (!$this->offsetExists($offset))
            {
                $this->numberOfForms++;
            }

            $this->forms[$offset] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this->offsetExists($offset))
        {
            unset($this->forms[$offset]);
            $this->numberOfForms--;
            $this->forms = array_values((array)$this->forms[$offset]);
        }
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function count(): int
    {
        return $this->numberOfForms;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->forms);
    }
}