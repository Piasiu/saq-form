<?php
namespace Saq\Form;

use ArrayAccess;
use Saq\Form\Interface\FieldInterface;

class Form extends FormElement implements ArrayAccess
{
    public const ENCTYPE_DEFAULT = 'application/x-www-form-urlencoded';
    public const ENCTYPE_FILE = 'multipart/form-data';

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string
     */
    private string $method = 'GET';

    /**
     * @var string
     */
    private string $action = '';

    /**
     * @var string
     */
    private string $enctype = self::ENCTYPE_DEFAULT;

    /**
     * @var Error[]
     */
    private array $errors = [];

    /**
     * @var FieldInterface[]
     */
    private array $fields = [];

    /**
     * @var Form[]
     */
    private array $subForms = [];

    /**
     * @var MultiForm[]
     */
    private array $multiForms = [];

    /**
     * @param array $data
     */
    public function populate(array $data): void
    {
        foreach ($data as $name => $value)
        {
            if ($this->hasField($name))
            {
                $this->fields[$name]->setValue($value);
            }
            elseif (is_array($value))
            {
                if ($this->hasSubForm($name))
                {
                    $this->subForms[$name]->populate($value);
                }
                elseif ($this->hasMultiForm($name))
                {
                    $this->multiForms[$name]->populate($value);
                }
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

        foreach ($this->fields as $name => $field)
        {
            if (array_key_exists($name, $data))
            {
                $field->setValue($data[$name]);
            }

            if (!$field->isValid())
            {
                $isValid = false;
            }
        }

        foreach ($this->subForms as $name => $subForm)
        {
            $subData = [];

            if (array_key_exists($name, $data))
            {
                $subData = $data[$name];
            }

            if (!$subForm->isValid($subData))
            {
                $isValid = false;
            }
        }

        foreach ($this->multiForms as $name => $multiForm)
        {
            $dataRows = [];

            if (array_key_exists($name, $data))
            {
                $dataRows = $data[$name];
            }

            if (!$multiForm->isValid($dataRows))
            {
                $isValid = false;
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

        foreach ($this->fields as $name => $field)
        {
            $values[$name] = $field->getValue();
        }

        foreach ($this->subForms as $name => $subForm)
        {
            $values[$name] = $subForm->getValues();
        }

        foreach ($this->multiForms as $name => $multiForm)
        {
            $values[$name] = $multiForm->getValues();
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $errors = [];

        if (!empty($this->errors))
        {
            $errors['form'] = $this->errors;
        }

        foreach ($this->fields as $name => $field)
        {
            $fieldErrors = $field->getErrors();

            if (!empty($fieldErrors))
            {
                $errors[$name] = $fieldErrors;
            }
        }

        foreach ($this->subForms as $name => $subForm)
        {
            $formErrors = $subForm->getErrors();

            if (!empty($formErrors))
            {
                $errors[$name] = $formErrors;
            }
        }

        foreach ($this->multiForms as $name => $multiForm)
        {
            $formErrors = $multiForm->getErrors();

            if (!empty($formErrors))
            {
                $errors[$name] = $formErrors;
            }
        }

        return $errors;
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
     * @return array
     */
    public function getFormErrors(): array
    {
        return $this->errors;
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->formName = $this->getParent() !== null ? $name : '';
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = strtoupper($method);
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function encodingForFiles(): void
    {
        $this->enctype = self::ENCTYPE_FILE;
    }

    public function defaultEncoding(): void
    {
        $this->enctype = self::ENCTYPE_DEFAULT;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    /**
     * @param string $name
     * @return FieldInterface|null
     */
    public function getField(string $name): ?FieldInterface
    {
        return $this->hasField($name) ? $this->fields[$name] : null;
    }

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FieldInterface $field
     */
    public function addField(FieldInterface $field): void
    {
        $field->setParent($this);
        $this->fields[$field->getName()] = $field;
        $this->removeSubForm($field->getName());
        $this->removeMultiForm($field->getName());
    }

    /**
     * @param string $name
     */
    public function removeField(string $name): void
    {
        unset($this->fields[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasSubForm(string $name): bool
    {
        return isset($this->subForms[$name]);
    }

    /**
     * @param string $name
     * @return Form|null
     */
    public function getSubForm(string $name): ?Form
    {
        return $this->hasSubForm($name) ? $this->subForms[$name] : null;
    }

    /**
     * @param string $name
     * @param Form $subForm
     */
    public function addSubForm(string $name, Form $subForm): void
    {
        $subForm->setParent($this);
        $subForm->setName($name);
        $this->subForms[$name] = $subForm;
        $this->removeField($name);
        $this->removeMultiForm($name);
    }

    /**
     * @param string $name
     */
    public function removeSubForm(string $name): void
    {
        unset($this->subForms[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasMultiForm(string $name): bool
    {
        return isset($this->multiForms[$name]);
    }

    /**
     * @param string $name
     * @return MultiForm|null
     */
    public function getMultiForm(string $name): ?MultiForm
    {
        return $this->hasMultiForm($name) ? $this->multiForms[$name] : null;
    }

    /**
     * @param string $name
     * @param MultiForm $multiForm
     */
    public function addMultiForm(string $name, MultiForm $multiForm): void
    {
        $multiForm->setParent($this);
        $multiForm->setName($name);
        $this->multiForms[$name] = $multiForm;
        $this->removeField($name);
        $this->removeSubForm($name);
    }

    /**
     * @param string $name
     */
    public function removeMultiForm(string $name): void
    {
        unset($this->multiForms[$name]);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return is_string($offset) && ($this->hasField($offset) || $this->hasSubForm($offset) || $this->hasMultiForm($offset));
    }

    /**
     * @param mixed $offset
     * @return FieldInterface|Form|MultiForm|null
     */
    public function offsetGet(mixed $offset): FieldInterface|Form|MultiForm|null
    {
        if ($this->hasField($offset))
        {
            return $this->fields[$offset];
        }

        if ($this->hasSubForm($offset))
        {
            return $this->subForms[$offset];
        }

        if ($this->hasMultiForm($offset))
        {
            return $this->multiForms[$offset];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof FieldInterface)
        {
            $this->addField($value);
        }
        elseif (is_string($offset))
        {
            if ($value instanceof Form)
            {
                $this->addSubForm($offset, $value);
            }
            elseif ($value instanceof MultiForm)
            {
                $this->addMultiForm($offset, $value);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        if ($this->hasField($offset))
        {
            $this->removeField($offset);
        }

        if ($this->hasSubForm($offset))
        {
            $this->removeSubForm($offset);
        }

        if ($this->hasMultiForm($offset))
        {
            $this->removeMultiForm($offset);
        }
    }
}