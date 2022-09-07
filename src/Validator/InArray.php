<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class InArray extends Validator
{
    const NOT_IN_ARRAY = 'notInArray';

    /**
     * @var array
     */
    private array $values = [];

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        parent::isValid($value);

        if (!is_array($value))
        {
            $value = [$value];
        }

        foreach ($value as $v)
        {
            if (!in_array($v, $this->values))
            {
                $this->addError(self::NOT_IN_ARRAY);
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return InArray
     */
    public function setValues(array $values): InArray
    {
        $this->values = $values;
        return $this;
    }
}