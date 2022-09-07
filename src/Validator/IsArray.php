<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class IsArray extends Validator
{
    const INVALID = 'isNotArray';
    
    public function isValid(mixed $value): bool
    {
        parent::isValid($value);

        if (!is_array($value))
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }
}