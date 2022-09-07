<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class Email extends Validator
{
    const INVALID = 'emaillInvalid';

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        parent::isValid($value);

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false)
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }
}