<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class FullName extends Validator
{
    const INVALID = 'fullNameInvalid';

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        self::isValid($value);

        if (!is_string($value) || !@preg_match('/^[\p{L}][-\p{L}\s\\\']*\s+[\p{L}]{1}[-\p{L}\s\\\']*$/iu', $value))
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }
}