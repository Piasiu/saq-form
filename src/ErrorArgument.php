<?php
namespace Saq\Form;

class ErrorArgument
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var array
     */
    private mixed $value;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, mixed $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}