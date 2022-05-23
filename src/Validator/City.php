<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class City extends Validator
{
    const INVALID = 'cityInvalid';

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        if (!is_string($value) || !@preg_match('/^[\p{L}][-\p{L}\s\\\']*$/iu', $value))
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }
}