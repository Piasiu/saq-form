<?php
namespace Saq\Form\Validator;

use Saq\Form\Validator;

class Text extends Validator
{
    const INVALID = 'textInvalid';
    
    /**
     * @var string
     */
    private string $pattern = '!@®#$€%&*\/\(\)\[\]\<\>\|\?\.,\’"\':;–+-=_\s\p{L}0-9\\\\';

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid(mixed $value): bool
    {
        if (!is_string($value) || !@preg_match('/^['.$this->pattern.']+$/ui', $value))
        {
            $this->addError(self::INVALID);
            return false;
        }

        return true;
    }

    /**
     * @param string $pattern
     * @return Text
     */
    public function setPattern(string $pattern): Text
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }
}