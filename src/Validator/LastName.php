<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class LastName extends Validator
{
    const INVALID = 'lastNameInvalid';

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        parent::isValid($value);

        if (!is_string($value) || !@preg_match('/^[\p{L}][-\p{L}\s\\\']*$/iu', $value))
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }
}