<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class Url extends Validator
{
    const INVALID = 'urlInvalid';

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        parent::isValid($value);

        if (filter_var($value, FILTER_VALIDATE_URL) === false)
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }
}